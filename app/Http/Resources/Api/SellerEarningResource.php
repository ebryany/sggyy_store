<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerEarningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'amount' => (float) $this->amount,
            'platform_fee' => (float) $this->platform_fee,
            'net_amount' => (float) ($this->amount - $this->platform_fee),
            'status' => $this->status,
            'available_at' => $this->available_at?->toIso8601String(),
            'order' => $this->whenLoaded('order', fn() => [
                'uuid' => $this->order->uuid,
                'order_number' => $this->order->order_number,
                'type' => $this->order->type,
            ]),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

