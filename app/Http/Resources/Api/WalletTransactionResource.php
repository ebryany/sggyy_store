<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'reference_number' => $this->reference_number,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'description' => $this->description,
            'rejection_reason' => $this->rejection_reason,
            'proof_path' => $this->proof_path,
            'approved_by' => $this->when($this->approver, fn() => [
                'id' => $this->approver->id,
                'name' => $this->approver->name,
            ]),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

