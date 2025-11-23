<?php

namespace App\Listeners;

use App\Events\PaymentVerified;
use App\Models\Notification;
use App\Models\Order;
use App\Models\SellerEarning;
use App\Services\OrderService;
use App\Services\SettingsService;
use App\Services\SecurityLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateOrderStatusOnPayment
{
    public function __construct(
        private OrderService $orderService,
        private SettingsService $settingsService
    ) {}
    
    /**
     * Handle the event.
     * 🔒 SECURITY: Enhanced with DB transaction and race condition protection
     */
    public function handle(PaymentVerified $event): void
    {
        $payment = $event->payment;
        
        // 🔒 SECURITY: Wrap entire logic in DB transaction
        DB::transaction(function () use ($payment) {
            // 🔒 SECURITY: Lock order row to prevent race condition
            $order = Order::where('id', $payment->order_id)
                ->lockForUpdate()
                ->with(['product', 'service'])
                ->firstOrFail();
            
            // 🔒 IDEMPOTENCY: Check if already processed
            if (in_array($order->status, ['paid', 'completed'])) {
                Log::info('PaymentVerified event - order already processed (idempotency check)', [
                    'order_id' => $order->id,
                    'order_status' => $order->status,
                    'payment_id' => $payment->id,
                ]);
                return; // Already processed, skip
            }
            
            $this->processPaymentVerification($order, $payment);
        });
    }
    
    /**
     * Process payment verification logic
     */
    private function processPaymentVerification(Order $order, $payment): void
    {
        
        // Auto-complete untuk digital products
        if ($order->type === 'product') {
            $this->orderService->updateStatus($order, 'completed');
            
            // 🔒 CRITICAL SECURITY: Set download expiry (30 days) - handled in OrderService
            // Download expiry is automatically set in OrderService::updateStatus() when status becomes 'completed'
            
            // Create seller earning (with duplicate prevention)
            $this->createSellerEarning($order);
            
            // Create notification for buyer
            Notification::create([
                'user_id' => $order->user_id,
                'message' => "Pembayaran untuk produk #{$order->order_number} telah dikonfirmasi! File dapat langsung diunduh.",
                'type' => 'payment_verified',
                'is_read' => false,
                'notifiable_type' => Order::class,
                'notifiable_id' => $order->id,
            ]);
        } else {
            // For services, update to 'paid' status
            $this->orderService->updateStatus($order, 'paid');
            
            // Create seller earning (with duplicate prevention)
            $this->createSellerEarning($order);
            
            // Auto-set deadline based on duration_hours from service
            $order = $order->fresh()->load('service');
            if ($order->service && $order->service->duration_hours && !$order->deadline_at) {
                // Auto-set deadline: now + duration_hours, minimum 24 hours
                $deadlineHours = max($order->service->duration_hours, 24);
                $deadlineAt = now()->addHours($deadlineHours);
                
                $order->update(['deadline_at' => $deadlineAt]);
            }
            
            // Create notification for buyer
            Notification::create([
                'user_id' => $order->user_id,
                'message' => "Pembayaran untuk jasa #{$order->order_number} telah dikonfirmasi! Seller akan segera memproses pesanan Anda." . ($order->deadline_at ? " Deadline: " . $order->deadline_at->format('d M Y, H:i') : ''),
                'type' => 'payment_verified',
                'is_read' => false,
                'notifiable_type' => Order::class,
                'notifiable_id' => $order->id,
            ]);
            
            // Notify seller about new paid order
            $sellerId = $order->service?->user_id;
            if ($sellerId) {
                Notification::create([
                    'user_id' => $sellerId,
                    'message' => "💰 Pembayaran untuk pesanan jasa #{$order->order_number} sudah diverifikasi! Segera mulai proses pesanan. " . ($order->deadline_at ? "Deadline: " . $order->deadline_at->format('d M Y, H:i') : 'Deadline: Belum ditetapkan'),
                    'type' => 'payment_verified_seller',
                    'is_read' => false,
                    'notifiable_type' => Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
        }
    }
    
    /**
     * Create seller earning with duplicate prevention
     * 🔒 SECURITY: Protected by unique constraint on seller_earnings.order_id
     */
    private function createSellerEarning(Order $order): void
    {
        try {
            $sellerId = $order->type === 'product' 
                ? $order->product?->user_id 
                : $order->service?->user_id;
            
            if (!$sellerId) {
                Log::warning('Cannot create seller earning - seller not found', [
                    'order_id' => $order->id,
                    'order_type' => $order->type,
                ]);
                return;
            }
            
            // Get commission rate from settings
            $commissionRate = $this->settingsService->getCommissionSettings()['rate'] ?? 10;
            
            $commission = $order->total * ($commissionRate / 100);
            $sellerEarning = $order->total - $commission;
            
            // 🔒 SECURITY: Will throw exception if duplicate (unique constraint)
            SellerEarning::create([
                'seller_id' => $sellerId,
                'order_id' => $order->id,
                'amount' => $sellerEarning,
                'commission' => $commission,
                'status' => 'pending',
            ]);
            
            SecurityLogger::logFinancialActivity('Seller earning created', [
                'order_id' => $order->id,
                'seller_id' => $sellerId,
                'amount' => $sellerEarning,
                'commission' => $commission,
            ]);
            
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // 🔒 SECURITY: Duplicate prevented by database constraint
            Log::warning('Duplicate seller earning prevented (race condition handled)', [
                'order_id' => $order->id,
                'seller_id' => $sellerId ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create seller earning', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            // Don't throw - let the payment verification complete
        }
    }
}
