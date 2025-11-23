<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\WithdrawalRequest;
use App\Models\SellerWithdrawal;
use App\Services\SellerService;
use App\Services\SettingsService;
use App\Services\SecurityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SellerWithdrawalController extends Controller
{
    public function __construct(
        private SellerService $sellerService,
        private SettingsService $settingsService
    ) {
        $this->middleware(['auth', 'seller']);
    }

    public function index(Request $request): View
    {
        $seller = auth()->user();
        
        // Get withdrawable balance
        $withdrawableBalance = $this->sellerService->getWithdrawableBalance($seller);
        
        // Get limits from settings
        $limits = $this->settingsService->getLimits();
        $minWithdrawal = $limits['min_withdrawal_amount'] ?? 50000; // Default 50k
        $maxWithdrawal = $limits['max_withdrawal_amount'] ?? 50000000; // Default 50M
        
        // Get withdrawal history with eager loading
        $withdrawals = SellerWithdrawal::where('seller_id', $seller->id)
            ->with(['seller', 'processor'])
            ->latest()
            ->paginate(15)
            ->withQueryString();
        
        // Get pending earnings (for info)
        $pendingEarnings = $seller->sellerEarnings()
            ->where('status', 'pending')
            ->sum('amount');
        
        return view('seller.withdrawal.index', compact(
            'withdrawableBalance',
            'minWithdrawal',
            'maxWithdrawal',
            'withdrawals',
            'pendingEarnings'
        ));
    }

    public function store(WithdrawalRequest $request): RedirectResponse
    {
        $seller = auth()->user();
        
        // ✅ PHASE 2: Validation already done in FormRequest (WithdrawalRequest)
        // Business logic validation (amount, balance check, multiple of 1000) is handled in the request class
        
        $validated = $request->validated();
        
        // Get limits from settings (for logging)
        $limits = $this->settingsService->getLimits();
        $minWithdrawal = $limits['min_withdrawal_amount'] ?? 50000;
        $maxWithdrawal = $limits['max_withdrawal_amount'] ?? 50000000;
        
        // Get withdrawable balance (for double-check in transaction)
        $withdrawableBalance = $this->sellerService->getWithdrawableBalance($seller);

        try {
            return DB::transaction(function () use ($seller, $validated, $withdrawableBalance) {
                // Double-check balance (prevent race condition)
                $currentBalance = $this->sellerService->getWithdrawableBalance($seller);
                
                if ($validated['amount'] > $currentBalance) {
                    return back()
                        ->withInput()
                        ->withErrors(['amount' => 'Saldo yang dapat ditarik tidak mencukupi. Silakan refresh halaman.']);
                }

                // Create withdrawal request
                $withdrawal = SellerWithdrawal::create([
                    'seller_id' => $seller->id,
                    'amount' => $validated['amount'],
                    'method' => $validated['method'],
                    'bank_name' => $validated['method'] === 'bank_transfer' 
                        ? ($validated['bank_name'] ?? null) 
                        : (ucfirst($validated['e_wallet_type'] ?? 'e_wallet')),
                    'account_number' => $validated['method'] === 'bank_transfer' 
                        ? ($validated['account_number'] ?? null) 
                        : ($validated['e_wallet_number'] ?? null),
                    'account_name' => $validated['method'] === 'bank_transfer' 
                        ? ($validated['account_name'] ?? null) 
                        : null,
                    'status' => 'pending',
                ]);

                // Mark earnings as withdrawn (lock earnings to prevent double withdrawal)
                // Use FIFO (First In First Out) - oldest earnings first
                $earningsToWithdraw = $seller->sellerEarnings()
                    ->where('status', 'available')
                    ->orderBy('created_at', 'asc')
                    ->lockForUpdate()
                    ->get();

                $remainingAmount = $validated['amount'];
                $withdrawnEarnings = [];

                foreach ($earningsToWithdraw as $earning) {
                    if ($remainingAmount <= 0) {
                        break;
                    }

                    if ($earning->amount <= $remainingAmount) {
                        // Mark entire earning as withdrawn
                        $earning->update(['status' => 'withdrawn']);
                        $withdrawnEarnings[] = $earning->id;
                        $remainingAmount -= $earning->amount;
                    } else {
                        // Partial withdrawal - mark whole earning as withdrawn
                        // (In future, can implement split earning for partial withdrawal)
                        $earning->update(['status' => 'withdrawn']);
                        $withdrawnEarnings[] = $earning->id;
                        $remainingAmount = 0;
                    }
                }

                // Validate that we have enough earnings
                if ($remainingAmount > 0) {
                    throw new \Exception('Saldo yang dapat ditarik tidak mencukupi. Silakan refresh halaman.');
                }

                // ✅ PHASE 2: Security logging
                SecurityLogger::logSecurityEvent('Seller withdrawal requested', [
                    'withdrawal_id' => $withdrawal->id,
                    'reference_number' => $withdrawal->reference_number,
                    'seller_id' => $seller->id,
                    'amount' => $validated['amount'],
                    'method' => $validated['method'],
                    'earnings_used' => $withdrawnEarnings,
                ]);
                
                Log::info('Seller withdrawal requested', [
                    'withdrawal_id' => $withdrawal->id,
                    'seller_id' => $seller->id,
                    'amount' => $validated['amount'],
                    'method' => $validated['method'],
                    'earnings_used' => $withdrawnEarnings,
                ]);

                return back()->with('success', 'Permintaan penarikan saldo berhasil dikirim. Admin akan memproses dalam 1x24 jam.');
            });
        } catch (\Exception $e) {
            Log::error('Seller withdrawal failed', [
                'seller_id' => $seller->id,
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Gagal mengajukan penarikan saldo: ' . $e->getMessage()]);
        }
    }
}

