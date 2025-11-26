<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'price' => (float) $this->price,
            'duration_hours' => $this->duration_hours,
            'status' => $this->status,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'completed_count' => $this->completed_count,
            'average_rating' => round($this->averageRating(), 2),
            'total_ratings' => $this->ratings->count(),
            'seller' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'store_slug' => $this->user->store_slug,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

