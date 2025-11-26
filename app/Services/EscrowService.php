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

            // Create notifications
            $buyer = $order->user;
            $seller = $order->product ? $order->product->user : $order->service->user;
            
            // Notify buyer
            \App\Models\Notification::create([
                'user_id' => $buyer->id,
                'message' => "🔒 Dana untuk pesanan #{$order->order_number} telah ditahan di escrow. Dana akan dilepas setelah periode hold atau saat Anda konfirmasi selesai.",
                'type' => 'escrow_created',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify seller
            \App\Models\Notification::create([
                'user_id' => $seller->id,
                'message' => "💰 Dana untuk pesanan #{$order->order_number} telah ditahan di escrow. Dana akan dilepas setelah periode hold ({$holdPeriodDays} hari) atau saat buyer konfirmasi selesai.",
                'type' => 'escrow_created',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);

            // Send email notifications
            try {
                \Illuminate\Support\Facades\Mail::to($buyer->email)->send(
                    new \App\Mail\EscrowCreatedMail($order, $holdPeriodDays)
                );
                \Illuminate\Support\Facades\Mail::to($seller->email)->send(
                    new \App\Mail\EscrowCreatedMail($order, $holdPeriodDays)
                );
            } catch (\Exception $e) {
                Log::warning('Failed to send escrow created email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Broadcast real-time event
            try {
                broadcast(new \App\Events\EscrowCreated($escrow, $order))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast escrow created event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

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

            // Handle escrow release based on xenPlatform or manual
            $order = $escrow->order;
            $seller = $order->product ? $order->product->user : $order->service->user;
            
            $settingsService = app(SettingsService::class);
            $xenditSettings = $settingsService->getXenditSettings();
            $useXenPlatform = $xenditSettings['enable_xenplatform'] ?? false;
            
            if ($useXenPlatform && $seller->xendit_subaccount_id) {
                // xenPlatform: Use Xendit disbursement API
                $xenditService = app(\App\Services\XenditService::class);
                
                try {
                    $externalId = 'DISB-' . $order->order_number . '-' . time();
                    $description = "Pembayaran untuk Order #{$order->order_number}";
                    
                    $disbursement = $xenditService->createDisbursement(
                        $seller,
                        (float) $escrow->seller_earning,
                        $externalId,
                        $description
                    );
                    
                    // Update escrow with disbursement info
                    $escrow->update([
                        'xendit_disbursement_id' => $disbursement['id'] ?? null,
                        'xendit_disbursement_external_id' => $externalId,
                    ]);
                    
                    Log::info('Escrow released via Xendit disbursement (xenPlatform)', [
                        'escrow_id' => $escrow->id,
                        'order_id' => $order->id,
                        'disbursement_id' => $disbursement['id'] ?? null,
                        'amount' => $escrow->seller_earning,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create Xendit disbursement', [
                        'escrow_id' => $escrow->id,
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            } else {
                // Manual escrow: Create seller earning (available for withdrawal)
                if (!$order->sellerEarning) {
                    $sellerService = app(SellerService::class);
                    $earning = $sellerService->createEarning($order);
                    
                    // Mark as available immediately (escrow already held the funds)
                    $sellerService->markEarningAvailable($earning);
                }
            }

            Log::info('Escrow released', [
                'escrow_id' => $escrow->id,
                'order_id' => $order->id,
                'release_type' => $releaseType,
                'released_by' => $releasedBy ?? auth()->id(),
            ]);

            // Create notifications
            $buyer = $order->user;
            $seller = $order->product ? $order->product->user : $order->service->user;
            
            $releaseTypeLabels = [
                'early' => 'dilepas lebih awal saat Anda konfirmasi selesai',
                'auto' => 'dilepas otomatis setelah periode hold selesai',
                'manual' => 'dilepas secara manual oleh admin',
            ];
            
            // Notify buyer
            \App\Models\Notification::create([
                'user_id' => $buyer->id,
                'message' => "✅ Escrow untuk pesanan #{$order->order_number} telah {$releaseTypeLabels[$releaseType]}. Dana telah dikirim ke seller.",
                'type' => 'escrow_released',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify seller
            \App\Models\Notification::create([
                'user_id' => $seller->id,
                'message' => "💰 Escrow untuk pesanan #{$order->order_number} telah {$releaseTypeLabels[$releaseType]}. Dana sebesar Rp " . number_format($escrow->seller_earning, 0, ',', '.') . " telah tersedia untuk withdrawal.",
                'type' => 'escrow_released',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);

            // Send email notifications
            try {
                \Illuminate\Support\Facades\Mail::to($buyer->email)->send(
                    new \App\Mail\EscrowReleasedMail($order, $releaseType)
                );
                \Illuminate\Support\Facades\Mail::to($seller->email)->send(
                    new \App\Mail\EscrowReleasedMail($order, $releaseType)
                );
            } catch (\Exception $e) {
                Log::warning('Failed to send escrow released email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Broadcast real-time event
            try {
                broadcast(new \App\Events\EscrowReleased($escrow->fresh(), $order, $releaseType))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast escrow released event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

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

            // Create notifications
            $order = $escrow->order;
            $buyer = $order->user;
            $seller = $order->product ? $order->product->user : $order->service->user;
            $disputedByUser = \App\Models\User::find($disputedBy ?? auth()->id());
            $isBuyerDispute = $disputedByUser->id === $buyer->id;
            
            // Notify the other party
            $otherUserId = $isBuyerDispute ? $seller->id : $buyer->id;
            \App\Models\Notification::create([
                'user_id' => $otherUserId,
                'message' => "⚠️ Dispute dibuat untuk pesanan #{$order->order_number} oleh " . ($isBuyerDispute ? 'buyer' : 'seller') . ". Admin akan meninjau dispute ini.",
                'type' => 'escrow_disputed',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify admins
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                \App\Models\Notification::create([
                    'user_id' => $admin->id,
                    'message' => "🚨 Dispute baru untuk pesanan #{$order->order_number}. Segera tinjau dan selesaikan.",
                    'type' => 'admin_dispute_created',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }

            // Send email notifications
            try {
                \Illuminate\Support\Facades\Mail::to($buyer->email)->send(
                    new \App\Mail\EscrowDisputedMail($order, $isBuyerDispute ? 'buyer' : 'seller')
                );
                \Illuminate\Support\Facades\Mail::to($seller->email)->send(
                    new \App\Mail\EscrowDisputedMail($order, $isBuyerDispute ? 'buyer' : 'seller')
                );
            } catch (\Exception $e) {
                Log::warning('Failed to send escrow disputed email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Broadcast real-time event
            try {
                broadcast(new \App\Events\EscrowDisputed($escrow->fresh(), $order, $disputedBy ?? auth()->id()))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast escrow disputed event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

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

            // Create notifications
            $buyer = $order->user;
            $seller = $order->product ? $order->product->user : $order->service->user;
            
            // Notify buyer
            \App\Models\Notification::create([
                'user_id' => $buyer->id,
                'message' => "💰 Dana untuk pesanan #{$order->order_number} telah dikembalikan ke wallet Anda sebesar Rp " . number_format($escrow->amount, 0, ',', '.') . ".",
                'type' => 'escrow_refunded',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify seller
            \App\Models\Notification::create([
                'user_id' => $seller->id,
                'message' => "⚠️ Dispute untuk pesanan #{$order->order_number} telah diselesaikan dengan refund ke buyer.",
                'type' => 'escrow_refunded',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);

            // Send email notifications
            try {
                \Illuminate\Support\Facades\Mail::to($buyer->email)->send(
                    new \App\Mail\EscrowRefundedMail($order, $escrow->amount)
                );
                \Illuminate\Support\Facades\Mail::to($seller->email)->send(
                    new \App\Mail\EscrowRefundedMail($order, $escrow->amount)
                );
            } catch (\Exception $e) {
                Log::warning('Failed to send escrow refunded email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

            // Broadcast real-time event
            try {
                broadcast(new \App\Events\EscrowRefunded($escrow->fresh(), $order))->toOthers();
            } catch (\Exception $e) {
                Log::warning('Failed to broadcast escrow refunded event', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }

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

