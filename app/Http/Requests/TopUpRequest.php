<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxAmount = 10000000; // 10 juta max per top-up
        $minAmount = 10000; // 10 ribu minimum

        return [
            'amount' => ['required', 'numeric', 'min:' . $minAmount, 'max:' . $maxAmount],
            'payment_method' => ['required', 'in:bank_transfer,qris,manual'],
            'proof_path' => ['required_if:payment_method,bank_transfer', 'nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Minimum top-up adalah Rp 10.000',
            'amount.max' => 'Maximum top-up adalah Rp 10.000.000',
            'proof_path.required_if' => 'Bukti pembayaran wajib diunggah untuk transfer bank',
            'proof_path.max' => 'Ukuran file maksimal 2MB',
        ];
    }
}
