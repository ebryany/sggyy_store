<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'method' => ['required', 'in:wallet,bank_transfer,qris,manual'],
            'proof_path' => ['required_if:method,bank_transfer', 'nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
        ];
    }
}
