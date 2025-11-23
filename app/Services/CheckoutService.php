<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use App\Services\SellerService;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        private OrderService $orderService,
        private WalletService $walletService
    ) {}

    public function checkoutProduct(Product $product, array $data): Order
    {
        // Validate payment method
        $paymentMethod = $data['payment_method'] ?? 'wallet';
        $validMethods = ['wallet', 'bank_transfer', 'qris'];
        if (!in_array($paymentMethod, $validMethods)) {
            throw new \Exception('Metode pembayaran tidak valid');
        }

        // Create order (includes atomic stock check & decrement)
        // Note: OrderService already has its own transaction, so we don't nest transactions
        $order = $this->orderService->createProductOrder($product, $data);

        // Create payment record in same transaction context
        return DB::transaction(function () use ($order, $paymentMethod) {
            $payment = $this->createPayment($order, $paymentMethod);

            // Process payment based on method
            if ($paymentMethod === 'wallet') {
                $this->processWalletPayment($order, $payment);
            }
            // For bank_transfer and qris, payment status remains 'pending' until admin verifies

            return $order->load('payment');
        });
    }

    public function checkoutService(Service $service, array $data, ?\Illuminate\Http\UploadedFile $taskFile = null): Order
    {
        // Validate service availability
        if (!$service->isActive()) {
            throw new \Exception('Jasa tidak tersedia atau tidak aktif');
        }

        // Validate payment method
        $paymentMethod = $data['payment_method'] ?? 'wallet';
        $validMethods = ['wallet', 'bank_transfer', 'qris'];
        if (!in_array($paymentMethod, $validMethods)) {
            throw new \Exception('Metode pembayaran tidak valid');
        }

        // Handle task file upload if provided
        $taskFilePath = null;
        if ($taskFile) {
            $taskFilePath = $this->storeTaskFile($taskFile);
            $data['task_file_path'] = $taskFilePath;
        }

        // Create order
        $order = $this->orderService->createServiceOrder($service, $data);

        // Create payment record in transaction
        return DB::transaction(function () use ($order, $paymentMethod) {
            $payment = $this->createPayment($order, $paymentMethod);

            // Process payment based on method
            if ($paymentMethod === 'wallet') {
                $this->processWalletPayment($order, $payment);
            }
            // For bank_transfer and qris, payment status remains 'pending' until admin verifies

            return $order->load('payment');
        });
    }

    /**
     * Store task file uploaded by buyer
     */
    private function storeTaskFile(\Illuminate\Http\UploadedFile $file): string
    {
        return $file->store('orders/tasks', 'public');
    }

    private function createPayment(Order $order, string $method): Payment
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => $method,
            'status' => 'pending', // All payments start as pending, wallet will be verified immediately
        ]);
        
        // Set payment expiry for transfer/QRIS (2 hours timeout)
        if (in_array($method, ['bank_transfer', 'qris'])) {
            $order->update([
                'payment_expires_at' => now()->addHours(2), // 2 hours timeout
            ]);
        }
        
        return $payment;
    }

    private function processWalletPayment(Order $order, Payment $payment): void
    {
        $user = $order->user;
        $currentBalance = $user->wallet_balance ?? 0;

        if (!$this->walletService->hasSufficientBalance($user, $order->total)) {
            $shortage = $order->total - $currentBalance;
            throw new \Exception("Saldo wallet tidak mencukupi. Saldo saat ini: Rp " . number_format($currentBalance, 0, ',', '.') . ". Kekurangan: Rp " . number_format($shortage, 0, ',', '.') . ". Silakan top-up wallet terlebih dahulu.");
        }

        // Deduct wallet balance (now returns WalletTransaction)
        $this->walletService->deductBalance($user, $order->total, "Order #{$order->order_number}");

        // Update payment status
        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        // Update order status
        // For digital products, auto-complete order after wallet payment
        // This allows immediate download access
        if ($order->type === 'product') {
            $order->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            // 🔒 CRITICAL SECURITY: Set download expiry (30 days) for digital products
            $order->setDownloadExpiry(30);
            
            // Create notification for buyer
            \App\Models\Notification::create([
                'user_id' => $order->user_id,
                'message' => "Pembayaran untuk produk #{$order->order_number} berhasil! File dapat langsung diunduh.",
                'type' => 'payment_verified',
                'is_read' => false,
                'notifiable_type' => \App\Models\Order::class,
                'notifiable_id' => $order->id,
            ]);
        } else {
            // For services, keep status as 'paid' (seller needs to work on it)
            $order->update(['status' => 'paid']);
            
            // Auto-set deadline using centralized method
            $order = $this->orderService->applyDeadlineRules($order->fresh());
            
            // Notify seller about new paid order
            $sellerId = $order->service?->user_id;
            if ($sellerId) {
                \App\Models\Notification::create([
                    'user_id' => $sellerId,
                    'message' => "💰 Pembayaran untuk pesanan jasa #{$order->order_number} sudah diverifikasi! Segera mulai proses pesanan. " . ($order->deadline_at ? "Deadline: " . $order->deadline_at->format('d M Y, H:i') : 'Deadline: Belum ditetapkan'),
                    'type' => 'payment_verified_seller',
                    'is_read' => false,
                    'notifiable_type' => \App\Models\Order::class,
                    'notifiable_id' => $order->id,
                ]);
            }
        }
    }
}

