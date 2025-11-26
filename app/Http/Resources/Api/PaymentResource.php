<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'method' => $this->method,
            'method_display_name' => $this->getMethodDisplayName(),
            'status' => $this->status,
            'proof_path' => $this->proof_path,
            'proof_url' => $this->getProofUrl(),
            'rejection_reason' => $this->rejection_reason,
            'xendit_invoice_id' => $this->xendit_invoice_id,
            'xendit_external_id' => $this->xendit_external_id,
            'xendit_payment_method' => $this->xendit_payment_method,
            'is_xendit_payment' => $this->isXenditPayment(),
            'verified_at' => $this->verified_at?->toIso8601String(),
            'verifier' => $this->when($this->verifier, fn() => [
                'id' => $this->verifier->id,
                'name' => $this->verifier->name,
            ]),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

