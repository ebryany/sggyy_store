<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'order_number' => $this->order_number,
            'type' => $this->type,
            'status' => $this->status,
            'total' => (float) $this->total,
            'progress' => $this->progress,
            'notes' => $this->notes,
            'cancel_reason' => $this->cancel_reason,
            'priority' => $this->priority,
            'needs_revision' => $this->needs_revision,
            'revision_count' => $this->revision_count,
            'revision_notes' => $this->revision_notes,
            'max_revisions' => $this->max_revisions,
            'is_disputed' => $this->is_disputed,
            'download_limit' => $this->download_limit,
            'download_count' => $this->download_count,
            'product' => $this->whenLoaded('product', fn() => [
                'uuid' => $this->product->uuid,
                'slug' => $this->product->slug,
                'title' => $this->product->title,
                'image' => $this->product->image ? asset('storage/' . $this->product->image) : null,
            ]),
            'service' => $this->whenLoaded('service', fn() => [
                'uuid' => $this->service->uuid,
                'slug' => $this->service->slug,
                'title' => $this->service->title,
                'image' => $this->service->image ? asset('storage/' . $this->service->image) : null,
            ]),
            'buyer' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'avatar' => $this->user->avatar ? asset('storage/' . $this->user->avatar) : null,
            ],
            'seller' => $this->when($this->product || $this->service, fn() => [
                'id' => ($this->product ?? $this->service)->user->id,
                'name' => ($this->product ?? $this->service)->user->name,
                'store_slug' => ($this->product ?? $this->service)->user->store_slug,
            ]),
            'payment' => $this->whenLoaded('payment', fn() => new PaymentResource($this->payment)),
            'rating' => $this->whenLoaded('rating', fn() => new RatingResource($this->rating)),
            'deliverable_path' => $this->deliverable_path,
            'task_file_path' => $this->task_file_path,
            'has_deliverable' => !empty($this->deliverable_path),
            'has_task_file' => !empty($this->task_file_path),
            'deadline_at' => $this->deadline_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'accepted_at' => $this->accepted_at?->toIso8601String(),
            'delivered_at' => $this->delivered_at?->toIso8601String(),
            'auto_complete_at' => $this->auto_complete_at?->toIso8601String(),
            'download_expires_at' => $this->download_expires_at?->toIso8601String(),
            'payment_expires_at' => $this->payment_expires_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

