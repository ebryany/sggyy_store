<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\Product;
use App\Models\Service;
use App\Services\SellerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    public function __construct(
        private SellerService $sellerService
    ) {}
    public function createProductOrder(Product $product, array $data): Order
    {
        return DB::transaction(function () use ($product, $data) {
            // Use pessimistic locking to prevent race condition
            // This locks the row until transaction completes
            $product = Product::where('id', $product->id)
                ->lockForUpdate()
                ->first();
            
            if (!$product) {
                throw new \Exception('Product not found');
            }
            
            // Atomic check: stock must be > 0 AND product must be active
            if ($product->stock <= 0 || !$product->is_active) {
                throw new \Exception('Product tidak tersedia atau stok habis');
            }

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'type' => 'product',
                'product_id' => $product->id,
                'total' => $product->price,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            // Decrease stock (atomic with check above)
            // Use decrement with validation to prevent negative stock
            $newStock = $product->stock - 1;
            if ($newStock < 0) {
                throw new \Exception('Stok tidak mencukupi. Stok saat ini: ' . $product->stock);
            }
            
            $product->update(['stock' => $newStock]);
            $product->increment('sold_count');
            
            Log::info('Product order created with stock decremented', [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'remaining_stock' => $product->fresh()->stock,
                'user_id' => auth()->id(),
            ]);

            return $order;
        });
    }

    public function createServiceOrder(Service $service, array $data): Order
    {
        return DB::transaction(function () use ($service, $data) {
            if (!$service->isActive()) {
                throw new \Exception('Service is not active');
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'type' => 'service',
                'service_id' => $service->id,
                'total' => $service->price,
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
                'task_file_path' => $data['task_file_path'] ?? null,
            ]);

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status, ?string $notes = null, ?string $createdByType = 'system'): Order
    {
        return DB::transaction(function () use ($order, $status, $notes, $createdByType) {
            $oldStatus = $order->status;
            
            // Logging BEFORE update for audit trail
            Log::info('Order status update initiated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $order->user_id,
                'old_status' => $oldStatus,
                'new_status' => $status,
                'updated_by' => auth()->id(),
                'updated_by_type' => $createdByType,
                'notes' => $notes,
            ]);
            
            // Validate status transition (prevent invalid state changes)
            // Pass order to allow special transitions for digital products
            $this->validateStatusTransition($oldStatus, $status, $order);
            
            $order->update(['status' => $status]);

            // Create order history
            OrderHistory::create([
                'order_id' => $order->id,
                'status_from' => $oldStatus,
                'status_to' => $status,
                'notes' => $notes,
                'created_by' => auth()->id(),
                'created_by_type' => $createdByType,
            ]);

            // If cancelled, restore stock for product orders and refund wallet if payment was verified
            if ($status === 'cancelled') {
                // Restore stock for product orders
                if ($order->type === 'product' && $order->product) {
                    $order->product->increment('stock');
                    $order->product->decrement('sold_count');
                    
                    Log::info('Product stock restored due to order cancellation', [
                        'order_id' => $order->id,
                        'product_id' => $order->product->id,
                        'product_title' => $order->product->title,
                    ]);
                }
                
                // Refund wallet if payment was verified (wallet payment or verified bank/qris)
                $payment = $order->payment;
                if ($payment && $payment->status === 'verified') {
                    // Only refund if payment was made via wallet OR if it was verified (meaning money was deducted)
                    if ($payment->method === 'wallet' || ($payment->method !== 'wallet' && $payment->verified_at)) {
                        $walletService = app(\App\Services\WalletService::class);
                        $walletService->addBalance(
                            $order->user,
                            $order->total,
                            "Refund untuk order #{$order->order_number} (Dibatalkan)",
                            'refund'
                        );
                        
                        Log::info('Wallet refunded due to order cancellation', [
                            'order_id' => $order->id,
                            'order_number' => $order->order_number,
                            'user_id' => $order->user_id,
                            'refund_amount' => $order->total,
                            'payment_method' => $payment->method,
                        ]);
                    }
                }
            }

            // If order completed, create seller earning and mark as available
            if ($status === 'completed' && !$order->sellerEarning) {
                try {
                    $earning = $this->sellerService->createEarning($order);
                    
                    // Auto-mark as available immediately after creation
                    // (You can change this to delay availability, e.g., after 7 days)
                    $this->sellerService->markEarningAvailable($earning);
                    
                    Log::info('Seller earning created and marked as available', [
                        'order_id' => $order->id,
                        'seller_id' => $earning->seller_id ?? null,
                        'amount' => $earning->amount ?? $order->total,
                        'earning_id' => $earning->id,
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the transaction
                    Log::error('Failed to create seller earning', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                    
                    // Re-throw if critical
                    throw $e;
                }
            }
            
            // 🔒 CRITICAL SECURITY: Set download expiry for product orders
            // Set download_expires_at (30 days) when order is completed for digital products
            if ($status === 'completed' && $order->type === 'product') {
                // Set completed_at if not already set
                if (!$order->completed_at) {
                    $order->update(['completed_at' => now()]);
                }
                
                // Set download expiry (30 days after completed_at)
                if (!$order->download_expires_at) {
                    $order->setDownloadExpiry(30); // 30 days
                    
                    Log::info('Download expiry set for product order', [
                        'order_id' => $order->id,
                        'product_id' => $order->product_id,
                        'download_expires_at' => $order->download_expires_at,
                    ]);
                }
            }
            
            // Create rating reminder notification when order is completed
            if ($status === 'completed') {
                // Set completed_at if not already set
                if (!$order->completed_at) {
                    $order->update(['completed_at' => now()]);
                }
                
                // Check if order can be rated (completed and no rating yet)
                $order = $order->fresh()->load('rating');
                if ($order->canBeRated()) {
                    \App\Models\Notification::create([
                        'user_id' => $order->user_id,
                        'message' => "Pesanan #{$order->order_number} telah selesai! Beri rating untuk membantu seller lain.",
                        'type' => 'order_completed',
                        'is_read' => false,
                        'notifiable_type' => \App\Models\Order::class,
                        'notifiable_id' => $order->id,
                    ]);
                    
                    Log::info('Rating reminder notification created', [
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                    ]);
                }
            }
            
            // Logging AFTER successful update
            Log::info('Order status updated successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'final_status' => $status,
            ]);

            return $order->fresh();
        });
    }
    
    /**
     * Validate status transition to prevent invalid state changes
     * 
     * @param string $from Current status
     * @param string $to Target status
     * @param Order|null $order Order instance (optional, for special transitions)
     */
    private function validateStatusTransition(string $from, string $to, ?Order $order = null): void
    {
        // Define valid transitions for service orders (new flow)
        $validTransitions = [
            'pending' => ['paid', 'cancelled'],
            'paid' => ['accepted', 'cancelled'], // Seller must accept first
            'accepted' => ['processing', 'cancelled'], // After accept, seller can start work
            'processing' => ['waiting_confirmation', 'cancelled', 'needs_revision'], // After deliverable upload
            'waiting_confirmation' => ['completed', 'needs_revision', 'disputed'], // Buyer can approve/reject/complain
            'needs_revision' => ['processing', 'waiting_confirmation', 'disputed'], // After revision, back to processing or waiting
            'completed' => [], // Completed orders cannot be changed
            'cancelled' => [], // Cancelled orders cannot be changed
            'disputed' => ['completed', 'cancelled'], // Admin can resolve dispute
        ];
        
        // Special cases for digital products (simpler flow):
        // 1. Allow pending → completed (for instant completion after payment verification)
        // 2. Allow paid → completed (for wallet payments that auto-complete)
        if ($order && $order->type === 'product') {
            if (($from === 'pending' && $to === 'completed') || 
                ($from === 'paid' && $to === 'completed')) {
                // This is a valid transition for digital products
                return;
            }
        }
        
        // Special cases for service orders:
        // 1. Allow paid → processing (backward compatibility: skip accept step if needed)
        // 2. Allow paid → completed when progress is 100% (auto-complete when work is done)
        // 3. Allow processing → completed when progress is 100% (auto-complete when work is done)
        // 4. Allow waiting_confirmation → completed after auto_complete_at expires
        if ($order && $order->type === 'service') {
            // Allow paid → processing (backward compatibility)
            if ($from === 'paid' && $to === 'processing') {
                return;
            }
            
            // Allow paid → completed when progress is 100% (auto-complete when work is done)
            // Allow processing → completed when progress is 100% (auto-complete when work is done)
            if ($to === 'completed') {
                if (in_array($from, ['paid', 'processing']) && $order->progress === 100) {
                    return;
                }
                // Allow waiting_confirmation → completed after auto_complete_at expires
                if ($from === 'waiting_confirmation' && $order->auto_complete_at && $order->auto_complete_at->isPast()) {
                    return;
                }
            }
        }
        
        // Check if transition is valid
        if (isset($validTransitions[$from]) && !in_array($to, $validTransitions[$from])) {
            Log::warning('Invalid order status transition attempted', [
                'from' => $from,
                'to' => $to,
                'order_id' => $order?->id,
                'order_type' => $order?->type,
                'user_id' => auth()->id(),
            ]);
            
            throw new \Exception("Invalid status transition from {$from} to {$to}");
        }
    }

    public function completeOrder(Order $order): Order
    {
        return $this->updateStatus($order, 'completed');
    }
    
    /**
     * Apply deadline rules for service orders
     * Centralized method to set deadline based on service duration_hours
     * 
     * @param Order $order Service order
     * @return Order Updated order with deadline set
     */
    public function applyDeadlineRules(Order $order): Order
    {
        // Only apply to service orders
        if ($order->type !== 'service') {
            return $order;
        }
        
        // Only set deadline if not already set
        if ($order->deadline_at) {
            return $order;
        }
        
        // Load service relationship if not loaded
        if (!$order->relationLoaded('service')) {
            $order->load('service');
        }
        
        // Auto-set deadline based on duration_hours from service
        if ($order->service && $order->service->duration_hours) {
            // Auto-set deadline: now + duration_hours, minimum 24 hours
            $deadlineHours = max($order->service->duration_hours, 24);
            $deadlineAt = now()->addHours($deadlineHours);
            
            $order->update(['deadline_at' => $deadlineAt]);
            
            Log::info('Auto-set deadline for service order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'duration_hours' => $order->service->duration_hours,
                'deadline_at' => $deadlineAt,
            ]);
        }
        
        return $order->fresh();
    }
    
    /**
     * Seller accepts order (paid → accepted → processing)
     * 
     * @param Order $order
     * @return Order
     */
    public function acceptOrder(Order $order): Order
    {
        if ($order->status !== 'paid') {
            throw new \Exception("Order must be in 'paid' status to be accepted");
        }
        
        if ($order->type !== 'service') {
            throw new \Exception("Only service orders can be accepted");
        }
        
        // Update to accepted status
        $order = $this->updateStatus($order, 'accepted', 'Order diterima oleh seller', 'seller');
        
        // Set accepted_at timestamp
        $order->update(['accepted_at' => now()]);
        
        // Auto-transition to processing (seller can start work immediately)
        $order = $this->updateStatus($order, 'processing', 'Seller mulai mengerjakan pesanan', 'seller');
        
        // Notify buyer
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'message' => "✅ Seller telah menerima pesanan #{$order->order_number} dan mulai mengerjakan.",
            'type' => 'order_accepted',
            'is_read' => false,
            'notifiable_type' => \App\Models\Order::class,
            'notifiable_id' => $order->id,
        ]);
        
        return $order->fresh();
    }
    
    /**
     * Seller rejects order (paid → cancelled with refund)
     * 
     * @param Order $order
     * @param string $reason
     * @return Order
     */
    public function rejectOrder(Order $order, string $reason = 'Seller menolak pesanan'): Order
    {
        if ($order->status !== 'paid') {
            throw new \Exception("Order must be in 'paid' status to be rejected");
        }
        
        if ($order->type !== 'service') {
            throw new \Exception("Only service orders can be rejected");
        }
        
        // Cancel order with full refund
        $order = $this->cancelOrder($order, $reason, true);
        
        // Notify buyer
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'message' => "❌ Seller menolak pesanan #{$order->order_number}. Dana akan dikembalikan ke wallet Anda.",
            'type' => 'order_rejected',
            'is_read' => false,
            'notifiable_type' => \App\Models\Order::class,
            'notifiable_id' => $order->id,
        ]);
        
        return $order->fresh();
    }
    
    /**
     * Move order to waiting_confirmation after deliverable upload
     * 
     * @param Order $order
     * @return Order
     */
    public function markAsWaitingConfirmation(Order $order): Order
    {
        if (!in_array($order->status, ['processing', 'needs_revision'])) {
            throw new \Exception("Order must be in 'processing' or 'needs_revision' status");
        }
        
        if (!$order->deliverable_path) {
            throw new \Exception("Deliverable must be uploaded before marking as waiting confirmation");
        }
        
        // Update status
        $order = $this->updateStatus($order, 'waiting_confirmation', 'Hasil pekerjaan telah diupload, menunggu konfirmasi buyer', 'seller');
        
        // Set delivered_at timestamp
        $order->update(['delivered_at' => now()]);
        
        // Set auto_complete_at (buyer has 24 hours to confirm, otherwise auto-complete)
        $autoCompleteAt = now()->addHours(24);
        $order->update(['auto_complete_at' => $autoCompleteAt]);
        
        // Notify buyer
        \App\Models\Notification::create([
            'user_id' => $order->user_id,
            'message' => "📦 Hasil pekerjaan untuk pesanan #{$order->order_number} telah diupload! Silakan review dan konfirmasi dalam 24 jam.",
            'type' => 'deliverable_uploaded',
            'is_read' => false,
            'notifiable_type' => \App\Models\Order::class,
            'notifiable_id' => $order->id,
        ]);
        
        return $order->fresh();
    }
    
    /**
     * Buyer confirms order completion (waiting_confirmation → completed)
     * 
     * @param Order $order
     * @return Order
     */
    public function confirmCompletion(Order $order): Order
    {
        if ($order->status !== 'waiting_confirmation') {
            throw new \Exception("Order must be in 'waiting_confirmation' status");
        }
        
        return $this->updateStatus($order, 'completed', 'Buyer mengkonfirmasi pesanan selesai', 'buyer');
    }
    
    /**
     * Cancel order with refund rules based on phase
     * 
     * @param Order $order
     * @param string $reason
     * @param bool $forceFullRefund Force full refund (for seller rejection)
     * @return Order
     */
    public function cancelOrder(Order $order, string $reason = '', bool $forceFullRefund = false): Order
    {
        if (in_array($order->status, ['completed', 'cancelled'])) {
            throw new \Exception("Cannot cancel order in {$order->status} status");
        }
        
        $shouldRefund = false;
        $refundAmount = 0;
        
        // Determine refund based on phase
        if ($forceFullRefund) {
            // Force full refund (e.g., seller rejection)
            $shouldRefund = true;
            $refundAmount = $order->total;
        } elseif ($order->status === 'paid' && !$order->accepted_at) {
            // Paid but seller hasn't accepted yet → full refund
            $shouldRefund = true;
            $refundAmount = $order->total;
        } elseif ($order->status === 'accepted' || ($order->status === 'processing' && $order->progress < 20)) {
            // Accepted or just started (progress < 20%) → full refund
            $shouldRefund = true;
            $refundAmount = $order->total;
        } elseif ($order->status === 'processing' && $order->progress >= 20 && $order->progress < 80) {
            // Mid-work (20-80%) → partial refund (50%)
            $shouldRefund = true;
            $refundAmount = $order->total * 0.5;
        } elseif ($order->status === 'processing' && $order->progress >= 80) {
            // Almost done (80%+) → no refund or minimal (10%)
            $shouldRefund = true;
            $refundAmount = $order->total * 0.1;
        } elseif ($order->status === 'waiting_confirmation') {
            // Already delivered → no refund, must use dispute
            throw new \Exception("Cannot cancel order that is waiting for confirmation. Please use dispute system.");
        }
        
        // Update order status
        $order = $this->updateStatus($order, 'cancelled', $reason ?: 'Pesanan dibatalkan', auth()->user()->isAdmin() ? 'admin' : 'buyer');
        
        // Save cancel reason
        $order->update(['cancel_reason' => $reason]);
        
        // Process refund if needed
        if ($shouldRefund && $refundAmount > 0) {
            $walletService = app(\App\Services\WalletService::class);
            $walletService->addBalance(
                $order->user,
                $refundAmount,
                'refund',
                "Refund untuk pembatalan pesanan #{$order->order_number}"
            );
        }
        
        return $order->fresh();
    }
    
    /**
     * Check and enforce revision limit
     * 
     * @param Order $order
     * @return bool True if revision is allowed
     */
    public function canRequestRevision(Order $order): bool
    {
        if ($order->type !== 'service') {
            return false;
        }
        
        $maxRevisions = $order->max_revisions ?? 2;
        
        return $order->revision_count < $maxRevisions;
    }
    
    /**
     * Request revision (waiting_confirmation → needs_revision)
     * 
     * @param Order $order
     * @param string $notes
     * @return Order
     */
    public function requestRevision(Order $order, string $notes): Order
    {
        if ($order->status !== 'waiting_confirmation') {
            throw new \Exception("Order must be in 'waiting_confirmation' status to request revision");
        }
        
        if (!$this->canRequestRevision($order)) {
            throw new \Exception("Maximum revision limit ({$order->max_revisions}) has been reached");
        }
        
        // Update revision count
        $order->increment('revision_count');
        
        // Update revision notes
        $order->update([
            'needs_revision' => true,
            'revision_notes' => $notes,
        ]);
        
        // Reset deadline for revision (add 24 hours from now)
        $revisionDeadline = now()->addHours(24);
        $order->update(['deadline_at' => $revisionDeadline]);
        
        // Update status back to processing
        $order = $this->updateStatus($order, 'processing', "Revisi diminta: {$notes}", 'buyer');
        
        // Notify seller
        $sellerId = $order->service?->user_id;
        if ($sellerId) {
            \App\Models\Notification::create([
                'user_id' => $sellerId,
                'message' => "🔄 Buyer meminta revisi untuk pesanan #{$order->order_number}. Catatan: {$notes}",
                'type' => 'revision_requested',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
        }
        
        return $order->fresh();
    }
    
    /**
     * Process payment timeout (auto-cancel pending payments)
     * Should be called by scheduled command
     * 
     * @return int Number of orders cancelled
     */
    public function processPaymentTimeouts(): int
    {
        $expiredOrders = \App\Models\Order::where('status', 'pending')
            ->whereNotNull('payment_expires_at')
            ->where('payment_expires_at', '<', now())
            ->with('payment')
            ->get();
        
        $cancelledCount = 0;
        
        foreach ($expiredOrders as $order) {
            try {
                // Only cancel if payment is still pending
                if ($order->payment && $order->payment->status === 'pending') {
                    $this->cancelOrder($order, 'Pembayaran melewati batas waktu (timeout)', false);
                    $cancelledCount++;
                    
                    // Notify buyer
                    \App\Models\Notification::create([
                        'user_id' => $order->user_id,
                        'message' => "⏰ Pesanan #{$order->order_number} dibatalkan karena pembayaran melewati batas waktu.",
                        'type' => 'payment_timeout',
                        'is_read' => false,
                        'notifiable_type' => \App\Models\Order::class,
                        'notifiable_id' => $order->id,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to process payment timeout', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $cancelledCount;
    }
    
    /**
     * Process auto-complete for waiting_confirmation orders
     * Should be called by scheduled command
     * 
     * @return int Number of orders auto-completed
     */
    public function processAutoCompletions(): int
    {
        $expiredOrders = \App\Models\Order::where('status', 'waiting_confirmation')
            ->whereNotNull('auto_complete_at')
            ->where('auto_complete_at', '<', now())
            ->get();
        
        $completedCount = 0;
        
        foreach ($expiredOrders as $order) {
            try {
                $this->updateStatus($order, 'completed', 'Auto-complete: Buyer tidak memberikan respon dalam 24 jam', 'system');
                $completedCount++;
                
                // Notify buyer
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'message' => "✅ Pesanan #{$order->order_number} otomatis diselesaikan karena tidak ada respon dalam 24 jam.",
                    'type' => 'order_auto_completed',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to process auto-completion', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $completedCount;
    }
}

