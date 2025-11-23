<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Services\FileUploadSecurityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

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
     * Display chat with specific user
     */
    public function show(string $userId): View
    {
        $currentUser = auth()->user();
        $otherUser = User::findOrFail($userId);

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
    public function startChat(string $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);

        // Prevent self-chat
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa chat dengan diri sendiri']);
        }

        // Get or create chat
        $chat = Chat::getOrCreateChat(auth()->id(), $user->id);

        return redirect()->route('chat.show', $user->id);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, string $userId)
    {
        $currentUser = auth()->user();
        $otherUser = User::findOrFail($userId);
        
        // FORCE redirect response type - never return JSON for security
        // This prevents sensitive data exposure even if browser/extension adds AJAX headers

        // Prevent self-chat
        if ($currentUser->id === $otherUser->id) {
            return back()->withErrors(['error' => 'Anda tidak bisa chat dengan diri sendiri']);
        }

        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,gif,webp', 'max:10240'], // Max 10MB
        ], [
            'message.max' => 'Pesan maksimal 2000 karakter',
            'attachment.file' => 'File yang diupload tidak valid',
            'attachment.mimes' => 'Format file tidak didukung. Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, TXT, JPG, JPEG, PNG, GIF, WEBP',
            'attachment.max' => 'Ukuran file maksimal 10MB',
        ]);
        
        // Custom validation: must have either message or attachment
        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return back()->withErrors(['error' => 'Pesan atau file attachment wajib diisi']);
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

            // Create notification for recipient
            \App\Models\Notification::create([
                'user_id' => $otherUser->id,
                'message' => "💬 Pesan baru dari {$currentUser->name}",
                'type' => 'chat_message',
                'is_read' => false,
                'notifiable_type' => Chat::class,
                'notifiable_id' => $chat->id,
            ]);

            DB::commit();

            Log::info('Chat message sent', [
                'chat_id' => $chat->id,
                'sender_id' => $currentUser->id,
                'recipient_id' => $otherUser->id,
                'message_id' => $message->id,
            ]);

            // SECURITY: Always redirect for form submissions - NEVER return JSON
            // Form submissions expose sensitive data (user info, emails, etc) if returned as JSON
            // This is a security risk - always redirect to prevent data exposure
            
            // For POST requests from forms, ALWAYS redirect (never return JSON)
            // This prevents sensitive user data from being exposed in JSON responses
            // Even if browser/extension adds AJAX headers, we still redirect for security
            
            // CRITICAL: Always redirect - NEVER return JSON
            // Bypass ALL Laravel helpers and return raw HTTP 302 redirect
            $url = route('chat.show', $otherUser->id);
            session()->flash('success', 'Pesan berhasil dikirim');
            
            // Force redirect using raw HTTP response - cannot be converted to JSON
            // This is the ONLY way to guarantee redirect, not JSON
            return \Illuminate\Support\Facades\Response::make('', 302, [
                'Location' => $url,
                'Content-Type' => 'text/html; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to send chat message', [
                'sender_id' => $currentUser->id,
                'recipient_id' => $otherUser->id,
                'error' => $e->getMessage(),
            ]);

            // SECURITY: Always redirect for form submissions - NEVER return JSON
            // Even on error, redirect to prevent data exposure
            
            // CRITICAL: Always redirect on error - NEVER return JSON
            $url = route('chat.show', $otherUser->id);
            session()->flash('error', 'Gagal mengirim pesan: ' . $e->getMessage());
            
            // Force redirect using raw HTTP response - cannot be converted to JSON
            return \Illuminate\Support\Facades\Response::make('', 302, [
                'Location' => $url,
                'Content-Type' => 'text/html; charset=UTF-8',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
            ]);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(string $userId): RedirectResponse
    {
        $currentUser = auth()->user();
        $otherUser = User::findOrFail($userId);

        $chat = Chat::getOrCreateChat($currentUser->id, $otherUser->id);

        $unreadMessages = $chat->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('is_read', false)
            ->get();

        foreach ($unreadMessages as $message) {
            $message->markAsRead();
        }

        // Always redirect (never return JSON for security)
        return back();
    }
}
