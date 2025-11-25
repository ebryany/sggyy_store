<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Escrow;
use App\Models\SellerEarning;
use App\Services\SettingsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EscrowService
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    /**
     * Create escrow after payment verified
     * 
     * @param Order $order
     * @param Payment $payment
     * @return Escrow
     */
    public function createEscrow(Order $order, Payment $payment): Escrow
    {
        return DB::transaction(function () use ($order, $payment) {
            // Check if escrow already exists (idempotency)
            if ($order->escrow) {
                Log::info('Escrow already exists for order', [
                    'order_id' => $order->id,
                    'escrow_id' => $order->escrow->id,
                ]);
                return $order->escrow;
            }

            // Calculate amounts
            $totalAmount = $order->total;
            $platformFeePercent = $this->settingsService->getCommissionForType($order->type);
            $platformFee = ($totalAmount * $platformFeePercent) / 100;
            $sellerEarning = $totalAmount - $platformFee;

            // Get hold period from settings (default 7 days)
            $holdPeriodDays = $this->getHoldPeriodDays();
            $holdUntil = now()->addDays($holdPeriodDays);

            // Create escrow
            $escrow = Escrow::create([
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'amount' => $totalAmount,
                'platform_fee' => $platformFee,
                'seller_earning' => $sellerEarning,
                'status' => 'holding',
                'hold_until' => $holdUntil,
                'xendit_invoice_id' => $payment->xendit_invoice_id,
                'xendit_external_id' => $payment->xendit_external_id,
            ]);

            // Update order
            $order->update([
                'escrow_id' => $escrow->id,
            ]);

            Log::info('Escrow created', [
                'escrow_id' => $escrow->id,
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'hold_until' => $holdUntil->toDateTimeString(),
            ]);

            return $escrow;
        });
    }

    /**
     * Release escrow (early release by buyer or auto release)
     * 
     * @param Escrow $escrow
     * @param string $releaseType early|auto|manual
     * @param int|null $releasedBy User ID who released
     * @return Escrow
     */
    public function releaseEscrow(Escrow $escrow, string $releaseType = 'auto', ?int $releasedBy = null): Escrow
    {
        return DB::transaction(function () use ($escrow, $releaseType, $releasedBy) {
            // Lock escrow to prevent race condition
            $escrow = Escrow::where('id', $escrow->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate escrow can be released
            if (!$escrow->canBeReleased()) {
                throw new \Exception('Escrow cannot be released. Status: ' . $escrow->status . ', Disputed: ' . ($escrow->is_disputed ? 'yes' : 'no'));
            }

            // Update escrow status
            $escrow->update([
                'status' => 'released',
                'released_at' => now(),
                'released_by' => $releasedBy ?? auth()->id(),
                'release_type' => $releaseType,
            ]);

            // Create seller earning (available for withdrawal)
            $order = $escrow->order;
            if (!$order->sellerEarning) {
                $sellerService = app(SellerService::class);
                $earning = $sellerService->createEarning($order);
                
                // Mark as available immediately (escrow already held the funds)
                $sellerService->markEarningAvailable($earning);
            }

            Log::info('Escrow released', [
                'escrow_id' => $escrow->id,
                'order_id' => $order->id,
                'release_type' => $releaseType,
                'released_by' => $releasedBy ?? auth()->id(),
            ]);

            return $escrow->fresh();
        });
    }

    /**
     * Early release by buyer (when buyer confirms completion)
     * 
     * @param Escrow $escrow
     * @return Escrow
     */
    public function earlyRelease(Escrow $escrow): Escrow
    {
        return $this->releaseEscrow($escrow, 'early', auth()->id());
    }

    /**
     * Auto release after hold period expires
     * 
     * @param Escrow $escrow
     * @return Escrow
     */
    public function autoRelease(Escrow $escrow): Escrow
    {
        return $this->releaseEscrow($escrow, 'auto', null);
    }

    /**
     * Dispute escrow (freeze funds)
     * 
     * @param Escrow $escrow
     * @param string $reason
     * @param int|null $disputedBy
     * @return Escrow
     */
    public function disputeEscrow(Escrow $escrow, string $reason, ?int $disputedBy = null): Escrow
    {
        return DB::transaction(function () use ($escrow, $reason, $disputedBy) {
            // Lock escrow
            $escrow = Escrow::where('id', $escrow->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate escrow can be disputed
            if (!$escrow->canBeDisputed()) {
                throw new \Exception('Escrow cannot be disputed. Status: ' . $escrow->status);
            }

            // Update escrow
            $escrow->update([
                'status' => 'disputed',
                'is_disputed' => true,
                'disputed_at' => now(),
                'dispute_reason' => $reason,
                'disputed_by' => $disputedBy ?? auth()->id(),
            ]);

            // Update order
            $escrow->order->update([
                'is_disputed' => true,
            ]);

            Log::info('Escrow disputed', [
                'escrow_id' => $escrow->id,
                'order_id' => $escrow->order_id,
                'reason' => $reason,
                'disputed_by' => $disputedBy ?? auth()->id(),
            ]);

            return $escrow->fresh();
        });
    }

    /**
     * Resolve dispute (admin can release or refund)
     * 
     * @param Escrow $escrow
     * @param string $resolution release|refund
     * @param string|null $notes
     * @return Escrow
     */
    public function resolveDispute(Escrow $escrow, string $resolution, ?string $notes = null): Escrow
    {
        return DB::transaction(function () use ($escrow, $resolution, $notes) {
            // Lock escrow
            $escrow = Escrow::where('id', $escrow->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate escrow is disputed
            if (!$escrow->isDisputed()) {
                throw new \Exception('Escrow is not disputed');
            }

            if ($resolution === 'release') {
                // Release to seller
                $escrow->update([
                    'status' => 'released',
                    'released_at' => now(),
                    'released_by' => auth()->id(),
                    'release_type' => 'manual',
                    'dispute_reason' => ($escrow->dispute_reason ?? '') . ' | Resolved: ' . ($notes ?? 'Released by admin'),
                ]);

                // Create seller earning
                $order = $escrow->order;
                if (!$order->sellerEarning) {
                    $sellerService = app(SellerService::class);
                    $earning = $sellerService->createEarning($order);
                    $sellerService->markEarningAvailable($earning);
                }

                // Update order
                $order->update([
                    'is_disputed' => false,
                ]);
            } elseif ($resolution === 'refund') {
                // Refund to buyer
                $this->refundEscrow($escrow, $notes);
            } else {
                throw new \Exception('Invalid resolution: ' . $resolution);
            }

            Log::info('Dispute resolved', [
                'escrow_id' => $escrow->id,
                'resolution' => $resolution,
                'resolved_by' => auth()->id(),
            ]);

            return $escrow->fresh();
        });
    }

    /**
     * Refund escrow to buyer
     * 
     * @param Escrow $escrow
     * @param string|null $reason
     * @return Escrow
     */
    public function refundEscrow(Escrow $escrow, ?string $reason = null): Escrow
    {
        return DB::transaction(function () use ($escrow, $reason) {
            // Lock escrow
            $escrow = Escrow::where('id', $escrow->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate escrow can be refunded
            if ($escrow->isRefunded()) {
                throw new \Exception('Escrow already refunded');
            }

            // Update escrow status
            $escrow->update([
                'status' => 'refunded',
                'dispute_reason' => ($escrow->dispute_reason ?? '') . ' | Refunded: ' . ($reason ?? 'Refunded by admin'),
            ]);

            // Refund to buyer wallet
            $order = $escrow->order;
            $walletService = app(WalletService::class);
            
            $walletService->addBalance(
                $order->user,
                $escrow->amount,
                'refund',
                "Refund untuk order #{$order->order_number}" . ($reason ? " - {$reason}" : '')
            );

            // Update order
            $order->update([
                'is_disputed' => false,
            ]);

            Log::info('Escrow refunded', [
                'escrow_id' => $escrow->id,
                'order_id' => $order->id,
                'amount' => $escrow->amount,
                'reason' => $reason,
            ]);

            return $escrow->fresh();
        });
    }

    /**
     * Get hold period days from settings
     */
    private function getHoldPeriodDays(): int
    {
        $holdPeriod = $this->settingsService->get('escrow_hold_period_days', 7);
        return (int) max(1, min(30, $holdPeriod)); // Minimum 1 day, maximum 30 days
    }

    /**
     * Get escrows ready for auto-release
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEscrowsReadyForAutoRelease(int $limit = 100)
    {
        return Escrow::where('status', 'holding')
            ->where('is_disputed', false)
            ->whereNotNull('hold_until')
            ->where('hold_until', '<=', now())
            ->limit($limit)
            ->get();
    }
}

