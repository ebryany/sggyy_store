<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use App\Services\WalletService;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(
        private WalletService $walletService,
        private SettingsService $settingsService
    ) {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $balance = $this->walletService->getBalance($user);
        
        // ✅ PHASE 1 FIX: Validate type parameter with whitelist
        $validTypes = ['top_up', 'deduction', 'refund'];
        $type = $request->get('type');
        if ($type && !in_array($type, $validTypes)) {
            \Illuminate\Support\Facades\Log::warning('Invalid type parameter in wallet.index', [
                'user_id' => $user->id,
                'type' => $type,
                'ip' => $request->ip(),
            ]);
            $type = null; // Reset to null if invalid
        }
        
        // ✅ PHASE 1 FIX: Validate status parameter with whitelist
        $validStatuses = ['pending', 'completed', 'rejected'];
        $status = $request->get('status');
        if ($status && !in_array($status, $validStatuses)) {
            \Illuminate\Support\Facades\Log::warning('Invalid status parameter in wallet.index', [
                'user_id' => $user->id,
                'status' => $status,
                'ip' => $request->ip(),
            ]);
            $status = null; // Reset to null if invalid
        }
        
        $transactions = $this->walletService->getTransactionHistory($user, $type, $status);

        return view('wallet.index', compact('balance', 'transactions', 'type', 'status'));
    }

    public function topUpForm(): View
    {
        // Get bank account info and limits from settings
        $bankAccountInfo = $this->settingsService->getBankAccountInfo();
        $limits = $this->settingsService->getLimits();
        $featureFlags = $this->settingsService->getFeatureFlags();
        
        return view('wallet.top-up', compact('bankAccountInfo', 'limits', 'featureFlags'));
    }

    public function topUp(Request $request): RedirectResponse
    {
        // Get limits from settings
        $limits = $this->settingsService->getLimits();
        $minTopup = $limits['min_topup_amount'] ?? 10000;
        $maxTopup = $limits['max_topup_amount'] ?? 10000000;

        // Check feature flags
        $featureFlags = $this->settingsService->getFeatureFlags();
        $availableMethods = ['manual']; // Manual always available for admin
        
        if ($featureFlags['enable_bank_transfer']) {
            $availableMethods[] = 'bank_transfer';
        }
        if ($featureFlags['enable_qris']) {
            $availableMethods[] = 'qris';
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', "min:{$minTopup}", "max:{$maxTopup}"],
            'payment_method' => ['required', 'in:' . implode(',', $availableMethods)],
            'proof_path' => ['required_if:payment_method,bank_transfer', 'nullable', 'image', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $transaction = $this->walletService->requestTopUp(
                auth()->user(),
                $validated,
                $request->file('proof_path')
            );

            // 🔒 SECURITY: All top-ups require admin approval
            // Transaction will always be 'pending' until admin verifies
            $transaction->refresh();
            
            // Always show pending message - no auto-approval
            $message = 'Permintaan top-up berhasil dikirim. Saldo sebesar Rp ' . number_format($transaction->amount, 0, ',', '.') . ' akan ditambahkan setelah admin memverifikasi pembayaran Anda.';

            return redirect()
                ->route('wallet.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            // Sanitize error message for user
            $errorMessage = match(true) {
                str_contains($e->getMessage(), 'Transaction sudah diproses') => 'Transaksi ini sudah diproses sebelumnya. Silakan cek riwayat transaksi Anda.',
                str_contains($e->getMessage(), 'Hanya top-up') => 'Hanya transaksi top-up yang dapat diproses.',
                default => 'Terjadi kesalahan saat memproses top-up. Silakan coba lagi atau hubungi support.',
            };
            
            return back()
                ->withInput()
                ->withErrors(['error' => $errorMessage])
                ->with('error', $errorMessage);
        }
    }

    // Admin methods
    public function approve(WalletTransaction $transaction): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        try {
            $this->walletService->approveTopUp($transaction, auth()->id());

            return back()->with('success', 'Top-up berhasil disetujui. Saldo sebesar Rp ' . number_format($transaction->amount, 0, ',', '.') . ' telah ditambahkan ke wallet user.');
        } catch (\Exception $e) {
            $errorMessage = match(true) {
                str_contains($e->getMessage(), 'Transaction sudah diproses') => 'Transaksi ini sudah diproses sebelumnya.',
                str_contains($e->getMessage(), 'Hanya top-up') => 'Hanya transaksi top-up yang dapat disetujui.',
                default => $e->getMessage() ?: 'Terjadi kesalahan saat menyetujui top-up.',
            };
            
            return back()->withErrors(['error' => $errorMessage])->with('error', $errorMessage);
        }
    }

    public function reject(WalletTransaction $transaction, Request $request): RedirectResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->walletService->rejectTopUp($transaction, $request->reason, auth()->id());

            return back()->with('success', 'Top-up berhasil ditolak. User akan diberitahu tentang penolakan ini.');
        } catch (\Exception $e) {
            $errorMessage = match(true) {
                str_contains($e->getMessage(), 'Transaction sudah diproses') => 'Transaksi ini sudah diproses sebelumnya.',
                default => $e->getMessage() ?: 'Terjadi kesalahan saat menolak top-up.',
            };
            
            return back()->withErrors(['error' => $errorMessage])->with('error', $errorMessage);
        }
    }

    // Admin index
    public function adminIndex(Request $request): View
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

         // Get status filter
        $status = $request->get('status', 'pending');
        $validStatuses = ['all', 'pending', 'approved', 'completed', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            $status = 'pending';
        }

        // Query pending top-up requests
        $query = WalletTransaction::where('type', 'top_up')
            ->with(['user', 'approver'])
            ->latest();

        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Statistics
        $stats = [
            'total_pending' => WalletTransaction::where('type', 'top_up')->where('status', 'pending')->count(),
            'total_approved' => WalletTransaction::where('type', 'top_up')->where('status', 'approved')->count(),
            'total_completed' => WalletTransaction::where('type', 'top_up')->where('status', 'completed')->count(),
            'total_rejected' => WalletTransaction::where('type', 'top_up')->where('status', 'rejected')->count(),
            'total_pending_amount' => WalletTransaction::where('type', 'top_up')->where('status', 'pending')->sum('amount'),
        ];

        return view('admin.wallet.index', compact('transactions', 'status', 'stats'));
    }
}
