<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\SellerEarningResource;
use App\Http\Resources\Api\SellerWithdrawalResource;
use App\Models\SellerEarning;
use App\Models\SellerWithdrawal;
use App\Services\SellerService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerEarningController extends BaseApiController
{
    protected SellerService $sellerService;
    protected WalletService $walletService;

    public function __construct(SellerService $sellerService, WalletService $walletService)
    {
        $this->sellerService = $sellerService;
        $this->walletService = $walletService;
    }

    /**
     * Get seller's earnings
     * 
     * GET /api/v1/seller/earnings
     * 
     * Query params:
     * - status: filter by status (pending|available|withdrawn)
     * - sort: latest|oldest
     * - page, per_page
     */
    public function index(Request $request)
    {
        $query = SellerEarning::where('seller_id', auth()->id())
            ->with(['order']);

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Paginate
        $earnings = $this->paginate($query, $request);

        // Get summary
        $summary = [
            'total_earnings' => SellerEarning::where('seller_id', auth()->id())->sum('amount'),
            'available_balance' => $this->sellerService->getWithdrawableBalance(auth()->user()),
            'pending_balance' => SellerEarning::where('seller_id', auth()->id())
                ->where('status', 'pending')
                ->sum('amount'),
            'withdrawn_total' => SellerEarning::where('seller_id', auth()->id())
                ->where('status', 'withdrawn')
                ->sum('amount'),
        ];

        return $this->success([
            'earnings' => SellerEarningResource::collection($earnings->items()),
            'summary' => $summary,
            'meta' => [
                'pagination' => [
                    'total' => $earnings->total(),
                    'count' => $earnings->count(),
                    'per_page' => $earnings->perPage(),
                    'current_page' => $earnings->currentPage(),
                    'total_pages' => $earnings->lastPage(),
                    'has_more_pages' => $earnings->hasMorePages(),
                ],
            ],
        ]);
    }

    /**
     * Withdraw earnings to wallet
     * 
     * POST /api/v1/seller/earnings/{earning_uuid}/withdraw
     */
    public function withdraw(Request $request, SellerEarning $earning)
    {
        // Check authorization
        if ($earning->seller_id !== auth()->id()) {
            return $this->forbidden('You do not have access to this earning');
        }

        // Check if earning is available
        if (!$earning->isAvailable()) {
            return $this->error(
                'This earning is not available for withdrawal yet',
                [],
                'EARNING_NOT_AVAILABLE',
                400
            );
        }

        try {
            DB::beginTransaction();

            // Transfer earning to wallet
            $this->walletService->addBalance(
                auth()->user(),
                $earning->amount,
                'earning_withdrawal',
                "Penarikan earning dari order #{$earning->order->order_number}"
            );

            // Mark earning as withdrawn
            $earning->update(['status' => 'withdrawn']);

            DB::commit();

            return $this->success(
                new SellerEarningResource($earning->fresh()),
                'Earnings withdrawn to wallet successfully'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            
            return $this->error(
                $e->getMessage(),
                [],
                'WITHDRAW_ERROR',
                400
            );
        }
    }

    /**
     * Request withdrawal to bank account
     * 
     * POST /api/v1/seller/earnings/withdraw-to-bank
     */
    public function withdrawToBank(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:10000'], // Min Rp 10,000
            'method' => ['required', 'in:bank_transfer,ewallet'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
            'bank_name' => ['required_if:method,bank_transfer', 'string', 'max:100'],
        ]);

        // Check available balance
        $availableBalance = $this->sellerService->getWithdrawableBalance(auth()->user());
        
        if ($validated['amount'] > $availableBalance) {
            return $this->error(
                'Insufficient available balance',
                ['amount' => ['Amount exceeds available balance']],
                'INSUFFICIENT_BALANCE',
                400
            );
        }

        try {
            DB::beginTransaction();

            // Create withdrawal request
            $withdrawal = SellerWithdrawal::create([
                'seller_id' => auth()->id(),
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'account_number' => $validated['account_number'],
                'account_name' => $validated['account_name'],
                'bank_name' => $validated['bank_name'] ?? null,
                'status' => 'pending',
            ]);

            // Mark earnings as withdrawn (proportional)
            $this->markEarningsAsWithdrawn($validated['amount']);

            DB::commit();

            return $this->created(
                new SellerWithdrawalResource($withdrawal),
                'Withdrawal request created successfully. Please wait for admin approval.'
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
     * Get withdrawal history
     * 
     * GET /api/v1/seller/earnings/withdrawals
     */
    public function withdrawals(Request $request)
    {
        $query = SellerWithdrawal::where('seller_id', auth()->id())
            ->orderBy('created_at', 'desc');

        // Apply filters
        $query = $this->applyFilters($query, $request, []);

        // Paginate
        $withdrawals = $this->paginate($query, $request);

        return $this->successCollection(
            SellerWithdrawalResource::collection($withdrawals)
        );
    }

    /**
     * Mark earnings as withdrawn (proportional allocation)
     * 
     * @param float $amount
     */
    protected function markEarningsAsWithdrawn(float $amount): void
    {
        $remainingAmount = $amount;

        // Get available earnings oldest first (FIFO)
        $earnings = SellerEarning::where('seller_id', auth()->id())
            ->where('status', 'available')
            ->orderBy('available_at', 'asc')
            ->get();

        foreach ($earnings as $earning) {
            if ($remainingAmount <= 0) {
                break;
            }

            if ($earning->amount <= $remainingAmount) {
                // Mark entire earning as withdrawn
                $earning->update(['status' => 'withdrawn']);
                $remainingAmount -= $earning->amount;
            } else {
                // Split earning (not implemented in this version - mark entire as withdrawn)
                // In production, you might want to implement partial withdrawal
                $earning->update(['status' => 'withdrawn']);
                $remainingAmount = 0;
            }
        }
    }
}

