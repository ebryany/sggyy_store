<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerWithdrawalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'reference_number' => $this->reference_number,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'method' => $this->method,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'rejection_reason' => $this->rejection_reason,
            'processed_by' => $this->when($this->processor, fn() => [
                'id' => $this->processor->id,
                'name' => $this->processor->name,
            ]),
            'processed_at' => $this->processed_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

