<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
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
            
            // Get seller for split payment (xenPlatform)
            $seller = $order->product ? $order->product->user : $order->service->user;
            
            // Check if xenPlatform is enabled
            $xenditSettings = $this->settingsService->getXenditSettings();
            $useXenPlatform = $xenditSettings['enable_xenplatform'] ?? false;
            
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
            // Reference: https://docs.xendit.co/apidocs/quick-setup
            if ($paymentMethod === 'VA') {
                // Virtual Account - Bank Transfer
                $invoiceData['payment_methods'] = ['BANK_TRANSFER'];
            } elseif ($paymentMethod === 'QRIS') {
                // QRIS - Use QR_CODE payment method (Xendit supports QRIS via QR_CODE)
                $invoiceData['payment_methods'] = ['QR_CODE'];
            } elseif ($paymentMethod === 'E_WALLET') {
                // E-Wallet (OVO, DANA, LINKAJA, etc.)
                $invoiceData['payment_methods'] = ['EWALLET'];
            }

            // xenPlatform: Add split payment configuration
            if ($useXenPlatform && $seller->isSeller()) {
                // Ensure seller has sub-account
                if (!$seller->xendit_subaccount_id) {
                    $this->createSubAccount($seller);
                    $seller->refresh();
                }

                if ($seller->xendit_subaccount_id) {
                    // Calculate split amounts
                    $totalAmount = (float) $order->total;
                    $commissionPercent = $this->settingsService->getCommissionForType($order->type);
                    $platformFee = ($totalAmount * $commissionPercent) / 100;
                    $sellerEarning = $totalAmount - $platformFee;

                    // Add split payment items (xenPlatform)
                    // Reference: https://docs.xendit.co/xenplatform/split-payment
                    $invoiceData['items'] = [
                        [
                            'name' => "Order #{$order->order_number}",
                            'quantity' => 1,
                            'price' => $totalAmount,
                            'category' => $order->type === 'product' ? 'Digital Product' : 'Service',
                        ]
                    ];

                    // Split payment configuration
                    $invoiceData['split'] = [
                        [
                            'reference_id' => $seller->xendit_subaccount_id,
                            'amount' => $sellerEarning,
                            'type' => 'ACCOUNT',
                        ],
                        // Platform fee stays in main account (no split needed, it's automatic)
                    ];

                    Log::info('Xendit invoice with split payment (xenPlatform)', [
                        'order_id' => $order->id,
                        'seller_id' => $seller->id,
                        'subaccount_id' => $seller->xendit_subaccount_id,
                        'total_amount' => $totalAmount,
                        'platform_fee' => $platformFee,
                        'seller_earning' => $sellerEarning,
                    ]);
                }
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
     * Create Xendit sub-account for seller (xenPlatform)
     * 
     * Reference: https://docs.xendit.co/xenplatform/accounts-misc-introduction
     * 
     * @param User $seller
     * @return array Xendit sub-account response
     * @throws \Exception
     */
    public function createSubAccount(User $seller): array
    {
        // Check if sub-account already exists
        if ($seller->xendit_subaccount_id) {
            Log::info('Xendit sub-account already exists', [
                'user_id' => $seller->id,
                'subaccount_id' => $seller->xendit_subaccount_id,
            ]);
            
            // Try to get sub-account details
            try {
                return $this->getSubAccount($seller->xendit_subaccount_id);
            } catch (\Exception $e) {
                Log::warning('Existing sub-account not found, creating new', [
                    'user_id' => $seller->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Prepare sub-account data
        $subAccountData = [
            'email' => $seller->email,
            'type' => 'INDIVIDUAL', // or 'BUSINESS' if seller has business entity
            'individual_detail' => [
                'given_names' => $seller->name,
                'surname' => '', // Optional
                'nationality' => 'ID', // Indonesia
                'date_of_birth' => null, // Optional, but recommended for KYC
                'place_of_birth' => null, // Optional
            ],
            'business_detail' => null, // Can be added if seller has business entity
            'country' => 'ID', // Indonesia
            'mobile_number' => $seller->phone ?? null,
            'phone_number' => $seller->phone ?? null,
            'addresses' => $seller->address ? [
                [
                    'country' => 'ID',
                    'street_line1' => $seller->address,
                    'street_line2' => '',
                    'city' => '', // Optional
                    'province' => '', // Optional
                    'postal_code' => '', // Optional
                ]
            ] : [],
        ];

        // Call Xendit API to create sub-account
        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->post("{$this->apiUrl}/v2/accounts", $subAccountData);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Xendit sub-account creation failed', [
                'user_id' => $seller->id,
                'status' => $response->status(),
                'response' => $errorBody,
            ]);
            throw new \Exception('Gagal membuat sub-account Xendit: ' . $errorBody);
        }

        $subAccount = $response->json();

        // Update seller with sub-account info
        $seller->update([
            'xendit_subaccount_id' => $subAccount['id'],
            'xendit_subaccount_email' => $subAccount['email'] ?? $seller->email,
            'xendit_subaccount_status' => $subAccount['status'] ?? 'pending',
            'xendit_subaccount_metadata' => $subAccount,
        ]);

        Log::info('Xendit sub-account created', [
            'user_id' => $seller->id,
            'subaccount_id' => $subAccount['id'],
            'status' => $subAccount['status'] ?? 'pending',
        ]);

        return $subAccount;
    }

    /**
     * Get sub-account details from Xendit
     * 
     * @param string $subAccountId
     * @return array
     * @throws \Exception
     */
    public function getSubAccount(string $subAccountId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->get("{$this->apiUrl}/v2/accounts/{$subAccountId}");

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan sub-account Xendit: ' . $response->body());
        }

        return $response->json();
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
     * Create disbursement to seller sub-account (xenPlatform)
     * 
     * Reference: https://docs.xendit.co/xenplatform/disbursements
     * 
     * @param User $seller
     * @param float $amount
     * @param string $externalId Unique external ID for idempotency
     * @param string $description
     * @return array Xendit disbursement response
     * @throws \Exception
     */
    public function createDisbursement(User $seller, float $amount, string $externalId, string $description): array
    {
        if (!$seller->xendit_subaccount_id) {
            throw new \Exception('Seller belum memiliki Xendit sub-account. Silakan create sub-account terlebih dahulu.');
        }

        // Check if disbursement already exists (idempotency)
        // Note: Xendit uses external_id for idempotency, so we can check our database
        // But for now, we'll rely on Xendit's idempotency via external_id

        $disbursementData = [
            'reference_id' => $seller->xendit_subaccount_id,
            'amount' => $amount,
            'external_id' => $externalId,
            'description' => $description,
            'type' => 'ACCOUNT', // Disburse to sub-account
        ];

        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->post("{$this->apiUrl}/v2/disbursements", $disbursementData);

        if (!$response->successful()) {
            $errorBody = $response->body();
            Log::error('Xendit disbursement creation failed', [
                'seller_id' => $seller->id,
                'subaccount_id' => $seller->xendit_subaccount_id,
                'amount' => $amount,
                'external_id' => $externalId,
                'status' => $response->status(),
                'response' => $errorBody,
            ]);
            throw new \Exception('Gagal membuat disbursement Xendit: ' . $errorBody);
        }

        $disbursement = $response->json();

        Log::info('Xendit disbursement created', [
            'seller_id' => $seller->id,
            'disbursement_id' => $disbursement['id'] ?? null,
            'external_id' => $externalId,
            'amount' => $amount,
        ]);

        return $disbursement;
    }

    /**
     * Get disbursement details from Xendit
     * 
     * @param string $disbursementId
     * @return array
     * @throws \Exception
     */
    public function getDisbursement(string $disbursementId): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->timeout(30)
            ->get("{$this->apiUrl}/v2/disbursements/{$disbursementId}");

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan disbursement Xendit: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Normalize Xendit webhook payload structure
     * 
     * Handles different payload formats:
     * - Invoice: { id, external_id, status, ... }
     * - E-Wallet: { data: { id, status, ... }, event: '...' }
     * - Disbursement: { id, external_id, status, ... }
     * 
     * @param array $payload Raw webhook payload
     * @return array Normalized payload
     */
    private function normalizeWebhookPayload(array $payload): array
    {
        // If payload has 'data' wrapper (e.g., E-Wallet webhooks)
        if (isset($payload['data']) && is_array($payload['data'])) {
            $normalized = $payload['data'];
            
            // Preserve event type and other root-level fields
            if (isset($payload['event'])) {
                $normalized['_event_type'] = $payload['event'];
            }
            
            // Extract external_id from metadata if present
            if (isset($normalized['metadata']['external_id'])) {
                $normalized['external_id'] = $normalized['metadata']['external_id'];
            }
            
            // For E-Wallet, map status to invoice-like format
            if (isset($normalized['status'])) {
                $ewalletStatus = strtoupper($normalized['status']);
                if ($ewalletStatus === 'SUCCEEDED') {
                    $normalized['status'] = 'PAID';
                } elseif ($ewalletStatus === 'FAILED') {
                    $normalized['status'] = 'FAILED';
                } elseif ($ewalletStatus === 'PENDING') {
                    $normalized['status'] = 'PENDING';
                }
            }
            
            return $normalized;
        }
        
        // If payload is already in root level (Invoice, Disbursement)
        return $payload;
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
        // Xendit webhook payload bisa memiliki struktur berbeda:
        // 1. Invoice webhook: { id, external_id, status, ... }
        // 2. E-Wallet webhook: { data: { id, status, ... }, event: '...' }
        // 3. Disbursement webhook: { id, external_id, status, ... }
        
        // Normalize payload structure
        $normalizedPayload = $this->normalizeWebhookPayload($payload);
        
        // Idempotency: Check if already processed
        $externalId = $normalizedPayload['external_id'] ?? null;
        $invoiceId = $normalizedPayload['id'] ?? null;
        
        if (!$externalId && !$invoiceId) {
            Log::error('Xendit webhook: Invalid payload - missing external_id and id', [
                'payload' => $payload,
                'normalized' => $normalizedPayload,
            ]);
            throw new \Exception('Invalid webhook payload: missing external_id or id');
        }
        
        // Use invoice_id as fallback for external_id if not present
        if (!$externalId && $invoiceId) {
            // Try to extract from metadata or use invoice_id as external_id
            $externalId = $normalizedPayload['metadata']['external_id'] ?? $invoiceId;
        }

        // Find payment by external_id or invoice_id (idempotency check)
        $payment = Payment::where('xendit_external_id', $externalId)
            ->orWhere('xendit_invoice_id', $invoiceId)
            ->first();
        
        if (!$payment) {
            Log::warning('Xendit webhook: Payment not found', [
                'external_id' => $externalId,
                'invoice_id' => $invoiceId,
                'normalized_payload' => $normalizedPayload,
            ]);
            
            // For E-Wallet webhooks that might not have payment record yet,
            // we should handle gracefully (maybe it's a different webhook type)
            if (isset($normalizedPayload['_event_type']) && str_contains($normalizedPayload['_event_type'], 'ewallet')) {
                Log::info('Xendit webhook: E-Wallet webhook received but payment not found - might be different flow', [
                    'external_id' => $externalId,
                    'invoice_id' => $invoiceId,
                ]);
                return ['status' => 'ignored', 'message' => 'E-Wallet webhook - payment not found, might be different flow'];
            }
            
            // Payment not found - this is an error for production webhooks
            throw new \Exception('Payment not found for external_id: ' . $externalId . ' or invoice_id: ' . $invoiceId);
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

        // Process webhook based on status (use normalized payload)
        $status = $normalizedPayload['status'] ?? null;
        
        if (!$status) {
            Log::error('Xendit webhook: Missing status in payload', [
                'normalized_payload' => $normalizedPayload,
            ]);
            throw new \Exception('Missing status in webhook payload');
        }
        
        return match(strtoupper($status)) {
            'PAID' => $this->processPaidWebhook($payment, $normalizedPayload),
            'EXPIRED' => $this->processExpiredWebhook($payment, $normalizedPayload),
            'FAILED' => $this->processFailedWebhook($payment, $normalizedPayload),
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

            // Check if using xenPlatform (split payment)
            $xenditSettings = $this->settingsService->getXenditSettings();
            $useXenPlatform = $xenditSettings['enable_xenplatform'] ?? false;
            
            $escrow = null;
            if ($useXenPlatform) {
                // xenPlatform: Payment already split by Xendit
                // Seller's portion is already in their sub-account
                // Platform fee is in main account
                // We still create escrow record for tracking, but funds are already split
                $escrowService = app(EscrowService::class);
                $escrow = $escrowService->createEscrow($order, $payment);
                
                // For xenPlatform, escrow is "virtual" - funds already split
                // We mark it as "released" immediately if order is completed (product)
                // For services, we still hold until buyer confirms
                if ($order->type === 'product') {
                    // Product: Auto-release immediately (no escrow needed for products with xenPlatform)
                    $escrowService->releaseEscrow($escrow, 'auto', null);
                }
                // Service: Escrow remains holding until buyer confirms or hold period expires
                
                Log::info('Xendit webhook: Payment verified with xenPlatform split payment', [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'escrow_id' => $escrow->id,
                    'split_payment' => true,
                ]);
            } else {
                // Manual escrow: Create escrow as before
                $escrowService = app(EscrowService::class);
                $escrow = $escrowService->createEscrow($order, $payment);

                Log::info('Xendit webhook: Payment verified and escrow created (manual)', [
                    'payment_id' => $payment->id,
                    'order_id' => $order->id,
                    'escrow_id' => $escrow->id,
                ]);
            }

            return [
                'status' => 'processed',
                'payment_id' => $payment->id,
                'order_id' => $order->id,
                'escrow_id' => $escrow->id ?? null,
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

