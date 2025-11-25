<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class XenditService
{
    private string $apiKey;
    private string $apiUrl;
    private bool $isProduction;
    private SettingsService $settingsService;

    public function __construct(
        SettingsService $settingsService
    ) {
        $this->settingsService = $settingsService;
        $xenditSettings = $this->settingsService->getXenditSettings();
        $this->apiKey = $xenditSettings['secret_key'] ?: config('services.xendit.secret_key', '');
        $this->apiUrl = $xenditSettings['api_url'] ?: config('services.xendit.api_url', 'https://api.xendit.co');
        $this->isProduction = $xenditSettings['production'] ?? config('services.xendit.production', false);
    }

    /**
     * Create Xendit invoice
     * 
     * @param Order $order
     * @param string $paymentMethod VA, QRIS, E_WALLET
     * @return array Xendit invoice response
     * @throws \Exception
     */
    public function createInvoice(Order $order, string $paymentMethod = 'VA'): array
    {
        return DB::transaction(function () use ($order, $paymentMethod) {
            // Generate unique external ID (idempotency key)
            $externalId = 'EBR-' . $order->order_number . '-' . time();
            
            // Check if payment already exists with Xendit
            $existingPayment = Payment::where('order_id', $order->id)
                ->whereNotNull('xendit_external_id')
                ->first();
            
            if ($existingPayment && $existingPayment->xendit_external_id) {
                // Return existing invoice if already created
                Log::info('Xendit invoice already exists', [
                    'order_id' => $order->id,
                    'external_id' => $existingPayment->xendit_external_id,
                ]);
                
                // Try to get invoice details from Xendit
                try {
                    return $this->getInvoice($existingPayment->xendit_invoice_id);
                } catch (\Exception $e) {
                    // If invoice not found, create new one
                    Log::warning('Existing Xendit invoice not found, creating new', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            
            // Prepare invoice data
            $invoiceData = [
                'external_id' => $externalId,
                'amount' => (float) $order->total,
                'description' => "Pembayaran untuk Order #{$order->order_number}",
                'invoice_duration' => 7200, // 2 hours (same as current payment_expires_at)
                'customer' => [
                    'given_names' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'success_redirect_url' => route('orders.show', $order->order_number),
                'failure_redirect_url' => route('orders.show', $order->order_number),
            ];

            // Add payment method specific config
            if ($paymentMethod === 'VA') {
                $invoiceData['payment_methods'] = ['BANK_TRANSFER'];
            } elseif ($paymentMethod === 'QRIS') {
                $invoiceData['payment_methods'] = ['EWALLET'];
                // QRIS typically uses OVO/DANA/LINKAJA
                $invoiceData['ewallet_type'] = 'OVO'; // Can be made dynamic
            } elseif ($paymentMethod === 'E_WALLET') {
                $invoiceData['payment_methods'] = ['EWALLET'];
            }

            // Call Xendit API
            $response = Http::withBasicAuth($this->apiKey, '')
                ->timeout(30)
                ->post("{$this->apiUrl}/v2/invoices", $invoiceData);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('Xendit invoice creation failed', [
                    'order_id' => $order->id,
                    'external_id' => $externalId,
                    'status' => $response->status(),
                    'response' => $errorBody,
                ]);
                throw new \Exception('Gagal membuat invoice Xendit: ' . $errorBody);
            }

            $invoice = $response->json();

            Log::info('Xendit invoice created', [
                'order_id' => $order->id,
                'invoice_id' => $invoice['id'],
                'external_id' => $externalId,
                'invoice_url' => $invoice['invoice_url'] ?? null,
            ]);

            return $invoice;
        });
    }

    /**
     * Get invoice details from Xendit
     */
    public function getInvoice(string $invoiceId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->get("{$this->apiUrl}/v2/invoices/{$invoiceId}");

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan invoice Xendit: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Verify Xendit webhook signature
     * 
     * @param string $payload Raw request body
     * @param string $signature X-Callback-Token header
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $xenditSettings = $this->settingsService->getXenditSettings();
        $expectedToken = $xenditSettings['webhook_token'] ?: config('services.xendit.webhook_token', '');
        
        if (empty($expectedToken)) {
            Log::warning('Xendit webhook token not configured');
            return false;
        }
        
        return hash_equals($expectedToken, $signature);
    }

    /**
     * Handle Xendit webhook (idempotent)
     * 
     * @param array $payload Webhook payload
     * @return array
     */
    public function handleWebhook(array $payload): array
    {
        // Idempotency: Check if already processed
        $externalId = $payload['external_id'] ?? null;
        $invoiceId = $payload['id'] ?? null;
        
        if (!$externalId || !$invoiceId) {
            Log::error('Xendit webhook: Invalid payload', ['payload' => $payload]);
            throw new \Exception('Invalid webhook payload: missing external_id or id');
        }

        // Find payment by external_id (idempotency check)
        $payment = Payment::where('xendit_external_id', $externalId)->first();
        
        if (!$payment) {
            Log::warning('Xendit webhook: Payment not found', [
                'external_id' => $externalId,
                'invoice_id' => $invoiceId,
            ]);
            throw new \Exception('Payment not found for external_id: ' . $externalId);
        }

        // Check if already processed (idempotency)
        if ($payment->status === 'verified' && $payment->xendit_invoice_id === $invoiceId) {
            Log::info('Xendit webhook: Already processed (idempotent)', [
                'payment_id' => $payment->id,
                'external_id' => $externalId,
                'invoice_id' => $invoiceId,
            ]);
            return ['status' => 'already_processed', 'payment_id' => $payment->id];
        }

        // Process webhook based on status
        $status = $payload['status'] ?? null;
        
        return match($status) {
            'PAID' => $this->processPaidWebhook($payment, $payload),
            'EXPIRED' => $this->processExpiredWebhook($payment, $payload),
            'FAILED' => $this->processFailedWebhook($payment, $payload),
            default => throw new \Exception('Unknown webhook status: ' . $status),
        };
    }

    /**
     * Process PAID webhook (idempotent)
     */
    private function processPaidWebhook(Payment $payment, array $payload): array
    {
        return DB::transaction(function () use ($payment, $payload) {
            // Lock payment to prevent race condition
            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Double-check idempotency
            if ($payment->status === 'verified' && $payment->xendit_invoice_id === $payload['id']) {
                Log::info('Xendit webhook: Payment already verified (idempotent)', [
                    'payment_id' => $payment->id,
                ]);
                return ['status' => 'already_processed', 'payment_id' => $payment->id];
            }

            // Update payment
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => null, // System verified
                'xendit_invoice_id' => $payload['id'],
                'xendit_payment_method' => $payload['payment_method'] ?? null,
                'xendit_metadata' => $payload,
            ]);

            // Update order status
            $order = $payment->order;
            $orderService = app(OrderService::class);
            
            if ($order->type === 'product') {
                $orderService->updateStatus($order, 'completed', 'Payment verified via Xendit', 'system');
            } else {
                $orderService->updateStatus($order, 'paid', 'Payment verified via Xendit', 'system');
            }

            // Create escrow
            $escrowService = app(EscrowService::class);
            $escrow = $escrowService->createEscrow($order, $payment);

            Log::info('Xendit webhook: Payment verified and escrow created', [
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'escrow_id' => $escrow->id,
            ]);

            return [
                'status' => 'processed',
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'escrow_id' => $escrow->id,
            ];
        });
    }

    /**
     * Process EXPIRED webhook
     */
    private function processExpiredWebhook(Payment $payment, array $payload): array
    {
        return DB::transaction(function () use ($payment, $payload) {
            // Lock payment
            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only update if still pending
            if ($payment->status === 'pending') {
                $payment->update([
                    'status' => 'expired',
                    'xendit_metadata' => $payload,
                ]);

                // Cancel order if still pending
                $order = $payment->order;
                if ($order->status === 'pending') {
                    $orderService = app(OrderService::class);
                    $orderService->updateStatus($order, 'cancelled', 'Payment expired via Xendit', 'system');
                }
            }

            return ['status' => 'expired', 'payment_id' => $payment->id];
        });
    }

    /**
     * Process FAILED webhook
     */
    private function processFailedWebhook(Payment $payment, array $payload): array
    {
        return DB::transaction(function () use ($payment, $payload) {
            // Lock payment
            $payment = Payment::where('id', $payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only update if still pending
            if ($payment->status === 'pending') {
                $payment->update([
                    'status' => 'failed',
                    'xendit_metadata' => $payload,
                ]);
            }

            return ['status' => 'failed', 'payment_id' => $payment->id];
        });
    }
}

