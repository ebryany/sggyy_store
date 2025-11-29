<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'type' => ['required', 'in:product,service'],
            'product_id' => ['required_if:type,product', 'exists:products,id'],
            'service_id' => ['required_if:type,service', 'exists:services,id'],
            'payment_method' => ['required', 'in:wallet,bank_transfer,qris,xendit_va,xendit_qris,veripay_qris'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'task_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png', 'max:10240'], // Max 10MB, optional for service orders
        ];
    }
}
