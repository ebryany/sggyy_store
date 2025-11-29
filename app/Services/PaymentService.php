<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use App\Services\OrderService;
use App\Events\PaymentVerified;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function uploadProof(Payment $payment, UploadedFile $file, ?string $secureFilename = null): Payment
    {
        return DB::transaction(function () use ($payment, $file, $secureFilename) {
            // Validate payment status
            if ($payment->status !== 'pending') {
                throw new \Exception('Payment sudah diproses');
            }

            // Delete old proof if exists
            $disk = config('filesystems.default');
            if ($payment->proof_path) {
                Storage::disk($disk)->delete($payment->proof_path);
            }

            // ✅ PHASE 2: Use secure filename if provided, otherwise use default
            if ($secureFilename) {
                // Store with secure filename
                $path = $file->storeAs('payments/proofs', $secureFilename, $disk);
            } else {
                // Fallback to default storage
                $path = $file->store('payments/proofs', $disk);
            }

            $payment->update([
                'proof_path' => $path,
            ]);

            return $payment->fresh();
        });
    }

    public function verifyPayment(Payment $payment, ?int $verifiedBy = null): Payment
    {
        return DB::transaction(function () use ($payment, $verifiedBy) {
            // Lock payment record to prevent race condition
            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate payment status
            if ($payment->status !== 'pending') {
                throw new \Exception('Payment sudah diproses');
            }

            // Verify payment
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $verifiedBy ?? auth()->id(),
            ]);

            // Clear admin dashboard cache to update pending payments count
            $adminDashboardService = app(\App\Services\AdminDashboardService::class);
            $adminDashboardService->clearCache();

            // Update order status using OrderService for consistency
            $order = $payment->order;
            
            // Load product relationship for auto-delivery check
            if ($order && $order->type === 'product') {
                $order->load('product');
            }
            
            // Check if payment is via Xendit/Veripay (has escrow) or manual (no escrow)
            $isXenditPayment = $payment->isXenditPayment();
            $isVeripayPayment = $payment->isVeripayPayment();
            
            if ($isXenditPayment) {
                // Xendit payment: escrow will be created by XenditService webhook handler
                // This method is for manual verification only
                // For Xendit, webhook handler already processed everything
                \Illuminate\Support\Facades\Log::info('Payment verified manually but is Xendit payment - escrow should be handled by webhook', [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                ]);
            } elseif ($isVeripayPayment) {
                // Veripay payment: escrow will be created by VeripayWebhookController
                // This method is for manual verification only
                // For Veripay, webhook handler already processed everything
                \Illuminate\Support\Facades\Log::info('Payment verified manually but is Veripay payment - escrow should be handled by webhook', [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                ]);
            } else {
                // Manual payment verification: create escrow if enabled
                $escrowService = app(\App\Services\EscrowService::class);
                if (!$order->escrow) {
                    $escrowService->createEscrow($order, $payment);
                }
            }
            
            // 🔒 REKBER FLOW: Update order status sesuai alur rekber
            if ($order->type === 'product') {
                // Step 1: Payment verified → status: 'paid' (Sudah Dibayar)
                $this->orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi oleh admin', 'admin');
                $order = $order->fresh();
                
                // Step 2: Create rekber (jika belum ada)
                if (!$order->escrow) {
                    $escrowService = app(\App\Services\EscrowService::class);
                    $escrowService->createEscrow($order, $payment);
                }
                
                // Step 3: Update to 'processing' (Diproses) - seller dapat mengirim produk
                $this->orderService->updateStatus($order, 'processing', 'Order diproses, seller dapat mengirim produk', 'admin');
                $order = $order->fresh();
                
                // 🔒 AUTO-DELIVERY: Untuk produk digital yang sudah punya file, otomatis kirim
                if ($order->product && $order->product->file_path) {
                    // Auto-deliver digital product
                    $this->orderService->updateStatus(
                        $order,
                        'waiting_confirmation',
                        'Produk digital otomatis dikirim setelah pembayaran diverifikasi',
                        'system'
                    );
                    $order = $order->fresh();
                    
                    // Set download expiry (30 days)
                    $order->setDownloadExpiry(30);
                    
                    // Notify buyer
                    $notificationService = app(\App\Services\NotificationService::class);
                    $notificationService->createNotificationIfNotExists(
                        $order->user,
                        'product_sent',
                        "📦 Produk digital untuk pesanan #{$order->order_number} telah otomatis dikirim! File dapat langsung diunduh.",
                        $order,
                        10
                    );
                }
            } else {
                // For services, update to 'paid' status (seller needs to work on it)
                $this->orderService->updateStatus($order, 'paid', 'Payment verified by admin', 'admin');
                $order = $order->fresh();
                
                // Auto-set deadline using centralized method
                $order = $this->orderService->applyDeadlineRules($order);
            }

            // 🔒 FIX: Dispatch PaymentVerified event to trigger listener (which will create notifications)
            // This ensures notifications are created consistently via listener, preventing duplication
            // Note: Order status already updated above, listener will handle notifications only
            event(new PaymentVerified($payment));

            return $payment->fresh();
        });
    }

    public function rejectPayment(Payment $payment, string $reason, ?int $rejectedBy = null): Payment
    {
        return DB::transaction(function () use ($payment, $reason, $rejectedBy) {
            // Lock payment record to prevent race condition
            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Validate payment status
            if ($payment->status !== 'pending') {
                throw new \Exception('Payment sudah diproses');
            }

            // Reject payment
            $payment->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'verified_by' => $rejectedBy ?? auth()->id(),
            ]);

            // Clear admin dashboard cache to update pending payments count
            $adminDashboardService = app(\App\Services\AdminDashboardService::class);
            $adminDashboardService->clearCache();

            // Update order status - keep as pending but add note
            $order = $payment->order;
            if ($order->status === 'pending') {
                // Order stays pending, but we log the rejection
                \App\Models\Notification::create([
                    'user_id' => $order->user_id,
                    'message' => "Pembayaran untuk pesanan #{$order->order_number} ditolak. Alasan: {$reason}. Silakan upload bukti pembayaran yang valid.",
                    'type' => 'payment_rejected',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }

            return $payment->fresh();
        });
    }

    public function getProofUrl(Payment $payment): ?string
    {
        if (!$payment->proof_path) {
            return null;
        }
        
        $disk = config('filesystems.default');
        return Storage::disk($disk)->url($payment->proof_path);
    }
}




