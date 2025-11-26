<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'message' => $this->message,
            'attachment_url' => $this->getAttachmentUrl(),
            'is_read' => $this->is_read,
            'read_at' => $this->read_at?->toIso8601String(),
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'avatar' => $this->sender->avatar ? asset('storage/' . $this->sender->avatar) : null,
            ],
            'is_from_current_user' => $this->isFromCurrentUser(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

