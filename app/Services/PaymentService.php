<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Order;
use App\Services\OrderService;
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
            
            // For digital products, directly complete order (skip 'paid' status)
            // This prevents double update and creates cleaner audit trail
            if ($order->type === 'product') {
                // Direct transition: pending → completed (valid for digital products)
                $this->orderService->updateStatus($order, 'completed', 'Digital product - payment verified, order completed automatically', 'admin');
            } else {
                // For services, update to 'paid' status (seller needs to work on it)
                $this->orderService->updateStatus($order, 'paid', 'Payment verified by admin', 'admin');
                $order = $order->fresh();
                
                // Auto-set deadline using centralized method
                $order = $this->orderService->applyDeadlineRules($order);
                
                // Notify seller about new paid order
                $sellerId = $order->service?->user_id;
                if ($sellerId) {
                    \App\Models\Notification::create([
                        'user_id' => $sellerId,
                        'message' => "💰 Pembayaran untuk pesanan jasa #{$order->order_number} sudah diverifikasi! Segera mulai proses pesanan. Deadline: " . ($order->deadline_at ? $order->deadline_at->format('d M Y, H:i') : 'Belum ditetapkan'),
                        'type' => 'payment_verified_seller',
                        'is_read' => false,
                        'notifiable_type' => \App\Models\Order::class,
                        'notifiable_id' => $order->id,
                    ]);
                }
            }

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




