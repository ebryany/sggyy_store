<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\VeripayService;
use App\Events\PaymentVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Veripay Webhook Controller
 * 
 * Handle webhook notifications from Veripay
 */
class VeripayWebhookController extends Controller
{
    public function __construct(
        private VeripayService $veripayService,
        private PaymentService $paymentService
    ) {
        // No auth required for webhooks
    }

    /**
     * Handle Veripay webhook
     * 
     * POST /webhooks/veripay
     */
    public function handle(Request $request)
    {
        try {
            // Get headers
            $timestamp = $request->header('x-timestamp');
            $signature = $request->header('x-signature');

            if (!$timestamp || !$signature) {
                Log::warning('Veripay webhook: Missing headers', [
                    'headers' => $request->headers->all(),
                ]);
                return response()->json(['error' => 'Missing required headers'], 400);
            }

            // Verify signature
            if (!$this->veripayService->verifyWebhookSignature($signature, (int) $timestamp)) {
                Log::warning('Veripay webhook: Invalid signature', [
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Get webhook data
            $data = $request->all();

            Log::info('Veripay webhook received', [
                'order_id' => $data['order_id'] ?? null,
                'status' => $data['status'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            // Find payment by order_number
            $orderNumber = $data['order_id'] ?? null;
            if (!$orderNumber) {
                Log::warning('Veripay webhook: Missing order_id');
                return response()->json(['error' => 'Missing order_id'], 400);
            }

            // Check if this is a wallet top-up (reference_number starts with 'WT-')
            $isTopUp = str_starts_with($orderNumber, 'WT-');
            
            if ($isTopUp) {
                // Handle wallet top-up
                return $this->handleTopUpWebhook($data, $orderNumber);
            }
            
            // Handle order payment
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) {
                Log::warning('Veripay webhook: Order not found', [
                    'order_number' => $orderNumber,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Find payment
            $payment = Payment::where('order_id', $order->id)
                ->where('method', 'veripay_qris')
                ->first();

            if (!$payment) {
                Log::warning('Veripay webhook: Payment not found', [
                    'order_id' => $order->id,
                    'order_number' => $orderNumber,
                ]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Process webhook based on status
            $status = strtoupper($data['status'] ?? '');
            
            if ($status === 'PAID') {
                // Payment successful
                DB::transaction(function () use ($payment, $order, $data) {
                    // Lock payment to prevent race condition
                    $payment = Payment::where('id', $payment->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Double-check idempotency
                    if ($payment->status === 'verified') {
                        Log::info('Veripay webhook: Payment already verified (idempotent)', [
                            'payment_id' => $payment->id,
                        ]);
                        return;
                    }

                    // Update payment
                    $payment->update([
                        'status' => 'verified',
                        'verified_at' => $data['payment_time'] ? \Carbon\Carbon::parse($data['payment_time']) : now(),
                        'verified_by' => null, // System verified
                        'xendit_metadata' => array_merge($payment->xendit_metadata ?? [], [
                            'status' => 'PAID',
                            'payment_time' => $data['payment_time'] ?? null,
                            'payment_method' => $data['payment_method'] ?? 'QRIS',
                        ]),
                    ]);

                    // 🔒 REKBER FLOW: Update order status sesuai alur rekber
                    $orderService = app(\App\Services\OrderService::class);
                    
                    // Load product relationship for auto-delivery check
                    if ($order && $order->type === 'product') {
                        $order->load('product');
                    }
                    
                    if ($order->type === 'product') {
                        // Step 1: Payment verified → status: 'paid' (Sudah Dibayar)
                        $orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi via Veripay', 'system');
                        $order = $order->fresh();
                        
                        // Step 2: Create rekber
                        $escrowService = app(\App\Services\EscrowService::class);
                        if (!$order->escrow) {
                            $escrowService->createEscrow($order, $payment);
                        }
                        
                        // Step 3: Update to 'processing' (Diproses)
                        $orderService->updateStatus($order, 'processing', 'Order diproses, seller dapat mengirim produk', 'system');
                        $order = $order->fresh();
                        
                        // 🔒 AUTO-DELIVERY: Untuk produk digital yang sudah punya file, otomatis kirim
                        if ($order->product && $order->product->file_path) {
                            // Auto-deliver digital product
                            $orderService->updateStatus(
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
                    } elseif ($order->type === 'service') {
                        // Service order: update to 'paid'
                        $orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi via Veripay', 'system');
                        $order = $order->fresh();
                        
                        // Create escrow for service
                        $escrowService = app(\App\Services\EscrowService::class);
                        if (!$order->escrow) {
                            $escrowService->createEscrow($order, $payment);
                        }
                    }

                    // Fire event
                    event(new \App\Events\PaymentVerified($payment));
                });

                Log::info('Veripay webhook: Payment verified', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                ]);
            } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
                // Payment failed or cancelled
                $payment->update([
                    'status' => 'failed',
                    'xendit_metadata' => array_merge($payment->xendit_metadata ?? [], [
                        'status' => $status,
                        'payment_time' => $data['payment_time'] ?? null,
                    ]),
                ]);

                Log::info('Veripay webhook: Payment failed/cancelled', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'status' => $status,
                ]);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Veripay webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle wallet top-up webhook
     */
    private function handleTopUpWebhook(array $data, string $referenceNumber)
    {
        $status = strtoupper($data['status'] ?? '');
        
        // Find wallet transaction by reference_number
        $transaction = \App\Models\WalletTransaction::where('reference_number', $referenceNumber)
            ->where('payment_method', 'veripay_qris')
            ->first();
            
        if (!$transaction) {
            Log::warning('Veripay webhook: Wallet transaction not found', [
                'reference_number' => $referenceNumber,
            ]);
            return response()->json(['error' => 'Wallet transaction not found'], 404);
        }
        
        if ($status === 'PAID') {
            DB::transaction(function () use ($transaction, $data) {
                // Lock transaction to prevent race condition
                $transaction = \App\Models\WalletTransaction::where('id', $transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                
                // Double-check idempotency
                if ($transaction->status === 'completed') {
                    Log::info('Veripay webhook: Top-up already completed (idempotent)', [
                        'transaction_id' => $transaction->id,
                    ]);
                    return;
                }
                
                // Update transaction metadata
                $transaction->update([
                    'veripay_metadata' => array_merge($transaction->veripay_metadata ?? [], [
                        'status' => 'PAID',
                        'payment_time' => $data['payment_time'] ?? null,
                        'payment_method' => $data['payment_method'] ?? 'QRIS',
                    ]),
                ]);
                
                // Auto-approve and complete top-up
                $walletService = app(\App\Services\WalletService::class);
                $walletService->approveTopUp($transaction, null); // System approved
                
                // Mark as completed
                $transaction->update(['status' => 'completed']);
                
                Log::info('Veripay webhook: Top-up completed', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                ]);
            });
        } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
            $transaction->update([
                'status' => 'rejected',
                'veripay_metadata' => array_merge($transaction->veripay_metadata ?? [], [
                    'status' => $status,
                    'payment_time' => $data['payment_time'] ?? null,
                ]),
            ]);
            
            Log::info('Veripay webhook: Top-up failed/cancelled', [
                'transaction_id' => $transaction->id,
                'status' => $status,
            ]);
        }
        
        return response()->json(['success' => true], 200);
    }

    /**
     * Health check endpoint
     * 
     * GET /webhooks/veripay/health
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Veripay Webhook',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}


namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use App\Services\VeripayService;
use App\Events\PaymentVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Veripay Webhook Controller
 * 
 * Handle webhook notifications from Veripay
 */
class VeripayWebhookController extends Controller
{
    public function __construct(
        private VeripayService $veripayService,
        private PaymentService $paymentService
    ) {
        // No auth required for webhooks
    }

    /**
     * Handle Veripay webhook
     * 
     * POST /webhooks/veripay
     */
    public function handle(Request $request)
    {
        try {
            // Get headers
            $timestamp = $request->header('x-timestamp');
            $signature = $request->header('x-signature');

            if (!$timestamp || !$signature) {
                Log::warning('Veripay webhook: Missing headers', [
                    'headers' => $request->headers->all(),
                ]);
                return response()->json(['error' => 'Missing required headers'], 400);
            }

            // Verify signature
            if (!$this->veripayService->verifyWebhookSignature($signature, (int) $timestamp)) {
                Log::warning('Veripay webhook: Invalid signature', [
                    'timestamp' => $timestamp,
                    'signature' => $signature,
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            // Get webhook data
            $data = $request->all();

            Log::info('Veripay webhook received', [
                'order_id' => $data['order_id'] ?? null,
                'status' => $data['status'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            // Find payment by order_number
            $orderNumber = $data['order_id'] ?? null;
            if (!$orderNumber) {
                Log::warning('Veripay webhook: Missing order_id');
                return response()->json(['error' => 'Missing order_id'], 400);
            }

            // Check if this is a wallet top-up (reference_number starts with 'WT-')
            $isTopUp = str_starts_with($orderNumber, 'WT-');
            
            if ($isTopUp) {
                // Handle wallet top-up
                return $this->handleTopUpWebhook($data, $orderNumber);
            }
            
            // Handle order payment
            $order = Order::where('order_number', $orderNumber)->first();
            if (!$order) {
                Log::warning('Veripay webhook: Order not found', [
                    'order_number' => $orderNumber,
                ]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Find payment
            $payment = Payment::where('order_id', $order->id)
                ->where('method', 'veripay_qris')
                ->first();

            if (!$payment) {
                Log::warning('Veripay webhook: Payment not found', [
                    'order_id' => $order->id,
                    'order_number' => $orderNumber,
                ]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Process webhook based on status
            $status = strtoupper($data['status'] ?? '');
            
            if ($status === 'PAID') {
                // Payment successful
                DB::transaction(function () use ($payment, $order, $data) {
                    // Lock payment to prevent race condition
                    $payment = Payment::where('id', $payment->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    // Double-check idempotency
                    if ($payment->status === 'verified') {
                        Log::info('Veripay webhook: Payment already verified (idempotent)', [
                            'payment_id' => $payment->id,
                        ]);
                        return;
                    }

                    // Update payment
                    $payment->update([
                        'status' => 'verified',
                        'verified_at' => $data['payment_time'] ? \Carbon\Carbon::parse($data['payment_time']) : now(),
                        'verified_by' => null, // System verified
                        'xendit_metadata' => array_merge($payment->xendit_metadata ?? [], [
                            'status' => 'PAID',
                            'payment_time' => $data['payment_time'] ?? null,
                            'payment_method' => $data['payment_method'] ?? 'QRIS',
                        ]),
                    ]);

                    // 🔒 REKBER FLOW: Update order status sesuai alur rekber
                    $orderService = app(\App\Services\OrderService::class);
                    
                    // Load product relationship for auto-delivery check
                    if ($order && $order->type === 'product') {
                        $order->load('product');
                    }
                    
                    if ($order->type === 'product') {
                        // Step 1: Payment verified → status: 'paid' (Sudah Dibayar)
                        $orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi via Veripay', 'system');
                        $order = $order->fresh();
                        
                        // Step 2: Create rekber
                        $escrowService = app(\App\Services\EscrowService::class);
                        if (!$order->escrow) {
                            $escrowService->createEscrow($order, $payment);
                        }
                        
                        // Step 3: Update to 'processing' (Diproses)
                        $orderService->updateStatus($order, 'processing', 'Order diproses, seller dapat mengirim produk', 'system');
                        $order = $order->fresh();
                        
                        // 🔒 AUTO-DELIVERY: Untuk produk digital yang sudah punya file, otomatis kirim
                        if ($order->product && $order->product->file_path) {
                            // Auto-deliver digital product
                            $orderService->updateStatus(
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
                    } elseif ($order->type === 'service') {
                        // Service order: update to 'paid'
                        $orderService->updateStatus($order, 'paid', 'Pembayaran diverifikasi via Veripay', 'system');
                        $order = $order->fresh();
                        
                        // Create escrow for service
                        $escrowService = app(\App\Services\EscrowService::class);
                        if (!$order->escrow) {
                            $escrowService->createEscrow($order, $payment);
                        }
                    }

                    // Fire event
                    event(new \App\Events\PaymentVerified($payment));
                });

                Log::info('Veripay webhook: Payment verified', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                ]);
            } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
                // Payment failed or cancelled
                $payment->update([
                    'status' => 'failed',
                    'xendit_metadata' => array_merge($payment->xendit_metadata ?? [], [
                        'status' => $status,
                        'payment_time' => $data['payment_time'] ?? null,
                    ]),
                ]);

                Log::info('Veripay webhook: Payment failed/cancelled', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'status' => $status,
                ]);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Veripay webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Handle wallet top-up webhook
     */
    private function handleTopUpWebhook(array $data, string $referenceNumber)
    {
        $status = strtoupper($data['status'] ?? '');
        
        // Find wallet transaction by reference_number
        $transaction = \App\Models\WalletTransaction::where('reference_number', $referenceNumber)
            ->where('payment_method', 'veripay_qris')
            ->first();
            
        if (!$transaction) {
            Log::warning('Veripay webhook: Wallet transaction not found', [
                'reference_number' => $referenceNumber,
            ]);
            return response()->json(['error' => 'Wallet transaction not found'], 404);
        }
        
        if ($status === 'PAID') {
            DB::transaction(function () use ($transaction, $data) {
                // Lock transaction to prevent race condition
                $transaction = \App\Models\WalletTransaction::where('id', $transaction->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                
                // Double-check idempotency
                if ($transaction->status === 'completed') {
                    Log::info('Veripay webhook: Top-up already completed (idempotent)', [
                        'transaction_id' => $transaction->id,
                    ]);
                    return;
                }
                
                // Update transaction metadata
                $transaction->update([
                    'veripay_metadata' => array_merge($transaction->veripay_metadata ?? [], [
                        'status' => 'PAID',
                        'payment_time' => $data['payment_time'] ?? null,
                        'payment_method' => $data['payment_method'] ?? 'QRIS',
                    ]),
                ]);
                
                // Auto-approve and complete top-up
                $walletService = app(\App\Services\WalletService::class);
                $walletService->approveTopUp($transaction, null); // System approved
                
                // Mark as completed
                $transaction->update(['status' => 'completed']);
                
                Log::info('Veripay webhook: Top-up completed', [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                ]);
            });
        } elseif ($status === 'FAILED' || $status === 'CANCELLED') {
            $transaction->update([
                'status' => 'rejected',
                'veripay_metadata' => array_merge($transaction->veripay_metadata ?? [], [
                    'status' => $status,
                    'payment_time' => $data['payment_time'] ?? null,
                ]),
            ]);
            
            Log::info('Veripay webhook: Top-up failed/cancelled', [
                'transaction_id' => $transaction->id,
                'status' => $status,
            ]);
        }
        
        return response()->json(['success' => true], 200);
    }

    /**
     * Health check endpoint
     * 
     * GET /webhooks/veripay/health
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Veripay Webhook',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
