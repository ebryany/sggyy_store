<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\ChatResource;
use App\Http\Resources\Api\ChatMessageResource;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends BaseApiController
{
    /**
     * Get user's chats
     * 
     * GET /api/v1/chats
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Chat::where(function ($q) use ($userId) {
                $q->where('user1_id', $userId)
                  ->orWhere('user2_id', $userId);
            })
            ->with(['user1', 'user2', 'messages' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->orderBy('last_message_at', 'desc');

        $chats = $this->paginate($query, $request);

        return $this->successCollection(
            ChatResource::collection($chats)
        );
    }

    /**
     * Create or get existing chat
     * 
     * POST /api/v1/chats
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        // Cannot chat with yourself
        if ($validated['user_id'] == auth()->id()) {
            return $this->error(
                'Cannot create chat with yourself',
                [],
                'INVALID_USER',
                400
            );
        }

        // Check if user exists
        $otherUser = User::find($validated['user_id']);
        if (!$otherUser) {
            return $this->notFound('User');
        }

        $chat = Chat::getOrCreateChat(auth()->id(), $validated['user_id']);

        return $this->success(
            new ChatResource($chat->load(['user1', 'user2']))
        );
    }

    /**
     * Get chat messages
     * 
     * GET /api/v1/chats/{chat_uuid}/messages
     */
    public function messages(Request $request, Chat $chat)
    {
        // Check authorization
        if ($chat->user1_id !== auth()->id() && $chat->user2_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this chat');
        }

        $query = $chat->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc');

        $messages = $this->paginate($query, $request);

        // Mark messages as read
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return $this->successCollection(
            ChatMessageResource::collection($messages)
        );
    }

    /**
     * Send message
     * 
     * POST /api/v1/chats/{chat_uuid}/messages
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        // Check authorization
        if ($chat->user1_id !== auth()->id() && $chat->user2_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this chat');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:10240'], // 10MB
        ]);

        try {
            DB::beginTransaction();

            $message = ChatMessage::create([
                'chat_id' => $chat->id,
                'sender_id' => auth()->id(),
                'message' => $validated['message'],
            ]);

            // Handle attachment if provided
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('chat_attachments', 'public');
                $message->update(['attachment_path' => $path]);
            }

            // Update chat last message timestamp
            $chat->updateLastMessageAt();

            DB::commit();

            return $this->created(
                new ChatMessageResource($message->load('sender')),
                'Message sent successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'MESSAGE_ERROR',
                400
            );
        }
    }
}

