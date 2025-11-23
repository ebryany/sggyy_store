<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\SettingsService;
use App\Services\SellerService;

class WithdrawalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSeller();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $settingsService = app(SettingsService::class);
        $sellerService = app(SellerService::class);
        
        $limits = $settingsService->getLimits();
        $minWithdrawal = $limits['min_withdrawal_amount'] ?? 50000;
        $maxWithdrawal = $limits['max_withdrawal_amount'] ?? 50000000;
        
        $seller = auth()->user();
        $withdrawableBalance = $sellerService->getWithdrawableBalance($seller);
        
        return [
            'amount' => [
                'required',
                'numeric',
                "min:{$minWithdrawal}",
                "max:{$maxWithdrawal}",
                // ✅ PHASE 2: Business logic validation - must not exceed balance
                function ($attribute, $value, $fail) use ($withdrawableBalance) {
                    if ($value > $withdrawableBalance) {
                        \App\Services\SecurityLogger::logBusinessLogicViolation('Withdrawal amount exceeds balance', [
                            'requested_amount' => $value,
                            'available_balance' => $withdrawableBalance,
                        ]);
                        $fail("Saldo yang dapat ditarik tidak mencukupi. Saldo tersedia: Rp " . number_format($withdrawableBalance, 0, ',', '.'));
                    }
                },
                // ✅ PHASE 2: Business logic validation - must be multiple of 1000
                function ($attribute, $value, $fail) {
                    if ($value % 1000 !== 0) {
                        \App\Services\SecurityLogger::logBusinessLogicViolation('Withdrawal amount not multiple of 1000', [
                            'requested_amount' => $value,
                        ]);
                        $fail("Jumlah penarikan harus kelipatan Rp 1.000");
                    }
                },
            ],
            'method' => ['required', 'in:bank_transfer,e_wallet'],
            'bank_name' => ['required_if:method,bank_transfer', 'nullable', 'string', 'max:255'],
            'account_number' => [
                'required_if:method,bank_transfer', 
                'nullable', 
                'string', 
                'regex:/^[0-9]+$/', 
                'min:8', 
                'max:50'
            ],
            'account_name' => ['required_if:method,bank_transfer', 'nullable', 'string', 'max:255'],
            'e_wallet_type' => ['required_if:method,e_wallet', 'nullable', 'string', 'in:dana,ovo,gopay,linkaja'],
            'e_wallet_number' => ['required_if:method,e_wallet', 'nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        $limits = app(SettingsService::class)->getLimits();
        $minWithdrawal = $limits['min_withdrawal_amount'] ?? 50000;
        $maxWithdrawal = $limits['max_withdrawal_amount'] ?? 50000000;
        
        return [
            'amount.required' => 'Jumlah penarikan wajib diisi',
            'amount.numeric' => 'Jumlah penarikan harus berupa angka',
            'amount.min' => "Minimum penarikan adalah Rp " . number_format($minWithdrawal, 0, ',', '.'),
            'amount.max' => "Maksimum penarikan adalah Rp " . number_format($maxWithdrawal, 0, ',', '.'),
            'method.required' => 'Metode penarikan wajib dipilih',
            'method.in' => 'Metode penarikan tidak valid',
            'bank_name.required_if' => 'Nama bank wajib diisi untuk transfer bank',
            'bank_name.max' => 'Nama bank maksimal 255 karakter',
            'account_number.required_if' => 'Nomor rekening wajib diisi',
            'account_number.regex' => 'Nomor rekening hanya boleh berisi angka',
            'account_number.min' => 'Nomor rekening minimal 8 karakter',
            'account_number.max' => 'Nomor rekening maksimal 50 karakter',
            'account_name.required_if' => 'Nama pemilik rekening wajib diisi',
            'account_name.max' => 'Nama pemilik rekening maksimal 255 karakter',
            'e_wallet_type.required_if' => 'Jenis e-wallet wajib dipilih',
            'e_wallet_type.in' => 'Jenis e-wallet tidak valid',
            'e_wallet_number.required_if' => 'Nomor e-wallet wajib diisi',
            'e_wallet_number.max' => 'Nomor e-wallet maksimal 255 karakter',
        ];
    }
}

