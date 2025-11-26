<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $otherUser = $this->getOtherUser();

        return [
            'uuid' => $this->uuid,
            'other_user' => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email,
                'avatar' => $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : null,
            ],
            'last_message' => $this->whenLoaded('messages', fn() => 
                $this->messages->last() ? [
                    'uuid' => $this->messages->last()->uuid,
                    'message' => $this->messages->last()->message,
                    'created_at' => $this->messages->last()->created_at->toIso8601String(),
                ] : null
            ),
            'unread_count' => $this->getUnreadCount(auth()->id()),
            'last_message_at' => $this->last_message_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

