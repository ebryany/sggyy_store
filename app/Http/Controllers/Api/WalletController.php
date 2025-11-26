<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\WalletTransactionResource;
use App\Http\Resources\Api\SellerWithdrawalResource;
use App\Models\WalletTransaction;
use App\Models\SellerWithdrawal;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends BaseApiController
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get wallet summary
     * 
     * GET /api/v1/wallet
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return $this->success([
            'balance' => (float) $user->balance,
            'total_topup' => (float) WalletTransaction::where('user_id', $user->id)
                ->where('type', 'topup')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_withdrawal' => (float) WalletTransaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
            'pending_topup' => (float) WalletTransaction::where('user_id', $user->id)
                ->where('type', 'topup')
                ->where('status', 'pending')
                ->sum('amount'),
        ]);
    }

    /**
     * Get wallet transactions
     * 
     * GET /api/v1/wallet/transactions
     */
    public function transactions(Request $request)
    {
        $query = WalletTransaction::where('user_id', auth()->id())
            ->with('approver')
            ->orderBy('created_at', 'desc');

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Filter by type if provided
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $transactions = $this->paginate($query, $request);

        return $this->successCollection(
            WalletTransactionResource::collection($transactions)
        );
    }

    /**
     * Create wallet top-up
     * 
     * POST /api/v1/wallet/topups
     */
    public function topup(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000', 'max:10000000'],
            'payment_method' => ['required', 'in:bank_transfer,qris'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        try {
            DB::beginTransaction();

            $transaction = $this->walletService->createTopup(
                auth()->user(),
                $validated['amount'],
                $validated['payment_method'],
                $validated['proof']
            );

            DB::commit();

            return $this->created(
                new WalletTransactionResource($transaction),
                'Top-up request created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'TOPUP_ERROR',
                400
            );
        }
    }

    /**
     * Create withdrawal
     * 
     * POST /api/v1/wallet/withdrawals
     */
    public function withdrawal(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:50000'],
            'method' => ['required', 'in:bank_transfer'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:100'],
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = $this->walletService->createWithdrawal(
                auth()->user(),
                $validated
            );

            DB::commit();

            return $this->created(
                new SellerWithdrawalResource($withdrawal),
                'Withdrawal request created successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'WITHDRAWAL_ERROR',
                400
            );
        }
    }

    /**
     * Get withdrawal details
     * 
     * GET /api/v1/wallet/withdrawals/{withdrawal_uuid}
     */
    public function getWithdrawal(SellerWithdrawal $withdrawal)
    {
        // Check authorization
        if ($withdrawal->seller_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this withdrawal');
        }

        return $this->success(
            new SellerWithdrawalResource($withdrawal->load('processor'))
        );
    }
}

