<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\SecurityLogger;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WalletService
{
    // Top-up methods
    /**
     * Request top-up - ALWAYS requires admin approval
     * 🔒 SECURITY: No auto-approval, all top-ups must be verified by admin
     */
    public function requestTopUp(User $user, array $data, ?UploadedFile $proof = null): WalletTransaction
    {
        return DB::transaction(function () use ($user, $data, $proof) {
            // 🔒 SECURITY: ALL top-ups require admin approval - NO auto-approval
            // Create transaction with 'pending' status - admin must verify payment proof
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'top_up',
                'amount' => $data['amount'],
                'status' => 'pending', // Always pending - requires admin verification
                'payment_method' => $data['payment_method'],
                'description' => $data['description'] ?? 'Top up wallet',
                'proof_path' => $proof ? $this->storeProof($proof) : null,
            ]);

            // 🔒 SECURITY: Log all top-up requests
            SecurityLogger::logFinancialActivity('Top-up requested', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'has_proof' => $proof !== null,
            ]);

            return $transaction->fresh();
        });
    }

    /**
     * Approve top-up transaction
     * 🔒 SECURITY: Enhanced with row-level locking to prevent race conditions
     */
    public function approveTopUp(WalletTransaction $transaction, ?int $approvedBy = null): WalletTransaction
    {
        return DB::transaction(function () use ($transaction, $approvedBy) {
            // 🔒 SECURITY: Lock transaction row to prevent concurrent approval
            $transaction = WalletTransaction::where('id', $transaction->id)
                ->lockForUpdate()
                ->firstOrFail();
            
            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaction sudah diproses');
            }

            if ($transaction->type !== 'top_up') {
                throw new \Exception('Hanya top-up yang bisa di-approve');
            }

            // 🔒 SECURITY: Lock user row to prevent race condition on balance update
            $user = User::where('id', $transaction->user_id)
                ->lockForUpdate()
                ->firstOrFail();

            // Update transaction status FIRST (idempotency)
            $updated = WalletTransaction::where('id', $transaction->id)
                ->where('status', 'pending') // Double check
                ->update([
                    'status' => 'approved',
                    'approved_by' => $approvedBy ?? auth()->id(),
                    'approved_at' => now(),
                ]);
            
            if ($updated === 0) {
                // Transaction already processed (race condition detected)
                throw new \Exception('Transaction sudah diproses oleh admin lain');
            }

            // Add balance to user
            $user->increment('wallet_balance', $transaction->amount);

            // Mark as completed
            $transaction->update(['status' => 'completed']);

            // 🔒 SECURITY: Log financial activity
            SecurityLogger::logFinancialActivity('Wallet top-up approved', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $transaction->amount,
                'old_balance' => $user->wallet_balance - $transaction->amount,
                'new_balance' => $user->wallet_balance,
                'approved_by' => $approvedBy ?? auth()->id(),
            ]);

            return $transaction->fresh();
        });
    }

    /**
     * Reject top-up transaction
     * 🔒 SECURITY: Enhanced with row-level locking
     */
    public function rejectTopUp(WalletTransaction $transaction, string $reason, ?int $rejectedBy = null): WalletTransaction
    {
        return DB::transaction(function () use ($transaction, $reason, $rejectedBy) {
            // 🔒 SECURITY: Lock transaction row
            $transaction = WalletTransaction::where('id', $transaction->id)
                ->lockForUpdate()
                ->firstOrFail();
            
            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaction sudah diproses');
            }

            $transaction->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'approved_by' => $rejectedBy ?? auth()->id(),
                'approved_at' => now(),
            ]);

            // 🔒 SECURITY: Log financial activity
            SecurityLogger::logFinancialActivity('Wallet top-up rejected', [
                'transaction_id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'amount' => $transaction->amount,
                'reason' => $reason,
                'rejected_by' => $rejectedBy ?? auth()->id(),
            ]);

            return $transaction->fresh();
        });
    }

    // Balance operations
    /**
     * Add balance to user wallet
     * 🔒 SECURITY: Enhanced with row-level locking
     */
    public function addBalance(User $user, float $amount, string $description = 'Top up', ?string $type = 'top_up'): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description, $type) {
            // 🔒 SECURITY: Lock user row
            $user = User::where('id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();
            
            $oldBalance = $user->wallet_balance;
            $user->increment('wallet_balance', $amount);

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => $type,
                'amount' => $amount,
                'status' => 'completed',
                'description' => $description,
            ]);

            // 🔒 SECURITY: Log financial activity
            SecurityLogger::logFinancialActivity('Balance added', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'old_balance' => $oldBalance,
                'new_balance' => $user->fresh()->wallet_balance,
                'type' => $type,
            ]);

            return $transaction;
        });
    }

    /**
     * Deduct balance from user wallet
     * 🔒 SECURITY: Enhanced with row-level locking and balance validation
     */
    public function deductBalance(User $user, float $amount, string $description = 'Purchase'): WalletTransaction
    {
        return DB::transaction(function () use ($user, $amount, $description) {
            // 🔒 SECURITY: Lock user row to prevent race condition
            $user = User::where('id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();
            
            // Double check balance after lock
            if ($user->wallet_balance < $amount) {
                SecurityLogger::logBusinessLogicViolation('Insufficient balance attempt', [
                    'user_id' => $user->id,
                    'current_balance' => $user->wallet_balance,
                    'attempted_deduction' => $amount,
                ]);
                throw new \Exception('Saldo wallet tidak mencukupi');
            }

            $oldBalance = $user->wallet_balance;
            $user->decrement('wallet_balance', $amount);

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'amount' => $amount,
                'status' => 'completed',
                'description' => $description,
            ]);

            // 🔒 SECURITY: Log financial activity
            SecurityLogger::logFinancialActivity('Balance deducted', [
                'transaction_id' => $transaction->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'old_balance' => $oldBalance,
                'new_balance' => $user->fresh()->wallet_balance,
                'description' => $description,
            ]);

            return $transaction;
        });
    }

    public function getBalance(User $user): float
    {
        return (float) $user->wallet_balance;
    }

    public function hasSufficientBalance(User $user, float $amount): bool
    {
        return $user->wallet_balance >= $amount;
    }

    // Transaction history
    public function getTransactionHistory(User $user, ?string $type = null, ?string $status = null)
    {
        $query = WalletTransaction::where('user_id', $user->id)
            ->with('approver')
            ->latest();

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate(15);
    }

    // File storage
    private function storeProof(UploadedFile $file): string
    {
        return $file->store('wallet/proofs', 'public');
    }
}

