<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'store_name' => $this->store_name,
            'store_slug' => $this->store_slug,
            'store_description' => $this->store_description,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'store_banner' => $this->store_banner ? asset('storage/' . $this->store_banner) : null,
            'store_logo' => $this->store_logo ? asset('storage/' . $this->store_logo) : null,
            'phone' => $this->phone,
            'address' => $this->address,
            'balance' => $this->when($this->role === 'seller' || $this->role === 'admin', (float) $this->balance),
            'total_sales' => $this->when($this->role === 'seller', (float) ($this->total_sales ?? 0)),
            'seller_rating' => $this->when($this->role === 'seller', (float) ($this->seller_rating ?? 0)),
            'is_seller_verified' => $this->isVerifiedSeller(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

