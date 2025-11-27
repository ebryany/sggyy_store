<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\FileUploadSecurityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Events\MessageSent;

class ChatController extends Controller
{
    public function __construct(
        private FileUploadSecurityService $fileSecurityService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display list of conversations
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get all chats where user is participant
        $chats = Chat::where('user1_id', $user->id)
            ->orWhere('user2_id', $user->id)
            ->with(['user1', 'user2', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get unread count for each chat
        $chats->each(function ($chat) use ($user) {
            $chat->unread_count = $chat->getUnreadCount($user->id);
            $chat->other_user = $chat->getOtherUser($user->id);
        });

        return view('chat.index', compact('chats'));
    }

    /**
     * Display chat with specific user (username-based)
     * 
     * URL: /chat/@{username}
     */
    public function show(string $username): View
    {
        // Remove @ if present
        $username = ltrim($username, '@');

        $currentUser = auth()->user();
        $otherUser = User::where('username', $username)->firstOrFail();

        // Prevent self-chat
        if ($currentUser->id === $otherUser->id) {
            abort(403, 'Anda tidak bisa chat dengan diri sendiri');
        }

        // Get or create chat
        $chat = Chat::getOrCreateChat($currentUser->id, $otherUser->id);

        // Get messages
        $messages = $chat->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read (only messages from other user)
        $unreadMessages = $chat->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsRead();
        }

        return view('chat.show', compact('chat', 'messages', 'otherUser', 'currentUser'));
    }

    /**
     * Start chat with a user (redirects to show)
     */
    public function startChat(string $username): RedirectResponse
    {
        // Remove @ if present
        $username = ltrim($username, '@');
        
        $user = User::where('username', $username)->firstOrFail();

        // Prevent self-chat
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa chat dengan diri sendiri']);
        }

        // Get or create chat
        $chat = Chat::getOrCreateChat(auth()->id(), $user->id);

        return redirect()->route('chat.show', $user->username);
    }

    /**
     * Send a message (AJAX endpoint)
     * 
     * Returns JSON for AJAX requests
     */
    public function sendMessage(Request $request, string $username): JsonResponse
    {
        // Remove @ if present
        $username = ltrim($username, '@');
        
        $currentUser = auth()->user();
        $otherUser = User::where('username', $username)->firstOrFail();

        // Prevent self-chat
        if ($currentUser->id === $otherUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa chat dengan diri sendiri'
            ], 403);
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,gif,webp', 'max:10240'], // Max 10MB
        ], [
            'message.max' => 'Pesan maksimal 2000 karakter',
            'attachment.file' => 'File yang diupload tidak valid',
            'attachment.mimes' => 'Format file tidak didukung',
            'attachment.max' => 'Ukuran file maksimal 10MB',
        ]);
        
        // Custom validation: must have either message or attachment
        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json([
                'success' => false,
                'message' => 'Pesan atau file attachment wajib diisi'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get or create chat
            $chat = Chat::getOrCreateChat($currentUser->id, $otherUser->id);

            // Handle file attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $allowedMimeTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/zip',
                    'application/x-rar-compressed',
                    'text/plain',
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                ];

                $validationErrors = $this->fileSecurityService->validateFile(
                    $request->file('attachment'),
                    $allowedMimeTypes,
                    10240 // 10MB
                );

                if (!empty($validationErrors)) {
                    throw new \Exception('File tidak valid: ' . implode(', ', $validationErrors));
                }

                $attachmentPath = $request->file('attachment')->store('chats/attachments', 'public');
            }

            // Create message
            $message = ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_id' => $currentUser->id,
                'message' => $validated['message'] ?? null,
                'attachment_path' => $attachmentPath,
                'is_read' => false,
            ]);

            // Update chat last message timestamp
            $chat->updateLastMessageAt();

            // 🔒 FIX: Use NotificationService with idempotency check
            $notificationService = app(\App\Services\NotificationService::class);
            $notificationService->createNotificationIfNotExists(
                $otherUser,
                'chat_message',
                "💬 Pesan baru dari {$currentUser->name}",
                $chat,
                2 // 2 minutes window for duplicate check (chat messages can be frequent)
            );

            // Broadcast message via Laravel Echo (real-time)
            broadcast(new MessageSent($message, $chat, $otherUser))->toOthers();

            DB::commit();

            Log::info('Chat message sent via AJAX', [
                'chat_id' => $chat->id,
                'sender_id' => $currentUser->id,
                'recipient_id' => $otherUser->id,
                'message_id' => $message->id,
            ]);

            // Return JSON response for AJAX
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'attachment_path' => $message->attachment_path,
                    'attachment_url' => $message->getAttachmentUrl(),
                    'sender_id' => $message->sender_id,
                    'sender_name' => $currentUser->name,
                    'created_at' => $message->created_at->format('H:i'),
                    'is_from_me' => true,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to send chat message via AJAX', [
                'sender_id' => $currentUser->id,
                'recipient_id' => $otherUser->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(string $username): JsonResponse
    {
        // Remove @ if present
        $username = ltrim($username, '@');
        
        $currentUser = auth()->user();
        $otherUser = User::where('username', $username)->firstOrFail();

        $chat = Chat::getOrCreateChat($currentUser->id, $otherUser->id);

        $unreadMessages = $chat->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
            'unread_count' => 0
        ]);
    }
}
