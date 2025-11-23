<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $user = auth()->user();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ];

        // Add store fields for sellers
        if ($user->role === 'seller' || $user->role === 'admin') {
            $rules['store_name'] = ['nullable', 'string', 'max:255'];
            $rules['store_description'] = ['nullable', 'string', 'max:1000'];
            $rules['social_instagram'] = ['nullable', 'string', 'max:255'];
            $rules['social_twitter'] = ['nullable', 'string', 'max:255'];
            $rules['social_facebook'] = ['nullable', 'string', 'max:255'];
            $rules['bank_name'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_number'] = ['nullable', 'string', 'max:255'];
            $rules['bank_account_name'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ];
    }
}
