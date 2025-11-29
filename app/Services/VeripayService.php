<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Veripay Service
 * 
 * Service untuk integrasi dengan Veripay Payment Gateway (khusus QRIS)
 */
class VeripayService
{
    private string $apiKey;
    private string $secretKey;
    private string $baseUrl;
    private SettingsService $settingsService;

    public function __construct(
        SettingsService $settingsService
    ) {
        $this->settingsService = $settingsService;
        $veripaySettings = $this->settingsService->getVeripaySettings();
        $this->apiKey = $veripaySettings['api_key'] ?? config('services.veripay.api_key', '');
        $this->secretKey = $veripaySettings['secret_key'] ?? config('services.veripay.secret_key', '');
        $this->baseUrl = rtrim($veripaySettings['base_url'] ?? config('services.veripay.base_url', 'https://veripay.site/api/v1'), '/');
    }

    /**
     * Generate signature untuk request
     * 
     * @param int $timestamp Unix timestamp
     * @return string Base64 encoded signature
     */
    private function generateSignature(int $timestamp): string
    {
        $payload = $this->apiKey . $timestamp;
        $rawHash = hash_hmac('sha256', $payload, $this->secretKey, true);
        return base64_encode($rawHash);
    }

    /**
     * Verify webhook signature
     * 
     * @param string $receivedSignature Signature dari webhook
     * @param int $timestamp Timestamp dari webhook
     * @return bool
     */
    public function verifyWebhookSignature(string $receivedSignature, int $timestamp): bool
    {
        $expectedSignature = $this->generateSignature($timestamp);
        return hash_equals($expectedSignature, $receivedSignature);
    }

    /**
     * Prepare headers untuk request
     * 
     * @return array
     */
    private function prepareHeaders(): array
    {
        $timestamp = time();
        $signature = $this->generateSignature($timestamp);

        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'x-api-key' => $this->apiKey,
            'x-timestamp' => (string) $timestamp,
            'x-signature' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create payment request (QRIS)
     * 
     * @param Order $order
     * @return array Veripay response
     * @throws \Exception
     */
    public function createPayment(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            // Check if payment already exists with Veripay
            $existingPayment = Payment::where('order_id', $order->id)
                ->where('method', 'veripay_qris')
                ->whereNotNull('xendit_external_id') // Reuse field untuk veripay transaction_ref
                ->first();

            if ($existingPayment && $existingPayment->xendit_external_id) {
                // Try to get payment status
                try {
                    return $this->getPaymentStatus($existingPayment->xendit_external_id);
                } catch (\Exception $e) {
                    Log::warning('Existing Veripay payment not found, creating new', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Load relationships if not already loaded
            if (!$order->relationLoaded('user')) {
                $order->load('user');
            }
            if ($order->type === 'product' && !$order->relationLoaded('product')) {
                $order->load('product');
            }
            if ($order->type === 'service' && !$order->relationLoaded('service')) {
                $order->load('service');
            }

            // Prepare payment data
            $paymentData = [
                'order_id' => $order->order_number,
                'amount' => (int) $order->total,
                'description' => "Pembayaran untuk Order #{$order->order_number}",
                'return_url' => route('orders.show', $order->order_number),
                'product_detail' => [
                    [
                        'name' => $order->type === 'product' 
                            ? ($order->product?->title ?? 'Product')
                            : ($order->service?->title ?? 'Service'),
                        'price' => (int) $order->total,
                        'qty' => 1,
                    ],
                ],
                'customer_detail' => [
                    'name' => $order->user?->name ?? 'Customer',
                    'email' => $order->user?->email ?? '',
                    'phone' => $order->user?->phone ?? '',
                ],
            ];

            // Validate API credentials before making request
            if (empty($this->apiKey) || empty($this->secretKey)) {
                throw new \Exception('Veripay API credentials belum dikonfigurasi. Silakan hubungi administrator.');
            }

            // Make API request
            $response = Http::withHeaders($this->prepareHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/merchant/payments', $paymentData);

            if (!$response->successful()) {
                $error = $response->json();
                Log::error('Veripay payment creation failed', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'error' => $error,
                    'response_body' => $response->body(),
                ]);

                $errorMessage = $error['message'] ?? 'Gagal membuat pembayaran Veripay';
                if (isset($error['errors'])) {
                    $errorMessage .= ': ' . json_encode($error['errors']);
                } elseif ($response->body() && $response->status() !== 200) {
                    // Include response body if available
                    $body = $response->body();
                    if (strlen($body) < 500) { // Only include if not too long
                        $errorMessage .= ' (' . $body . ')';
                    }
                }

                throw new \Exception($errorMessage);
            }

            $responseData = $response->json();

            if (!($responseData['success'] ?? false)) {
                throw new \Exception(
                    $responseData['message'] ?? 'Gagal membuat pembayaran Veripay'
                );
            }

            Log::info('Veripay payment created', [
                'order_id' => $order->id,
                'transaction_ref' => $responseData['data']['transaction_ref'] ?? null,
            ]);

            return $responseData;
        });
    }

    /**
     * Get payment status
     * 
     * @param string $transactionRef Transaction reference
     * @return array Veripay response
     * @throws \Exception
     */
    public function getPaymentStatus(string $transactionRef): array
    {
        $response = Http::withHeaders($this->prepareHeaders())
            ->timeout(30)
            ->get($this->baseUrl . '/merchant/payments/' . $transactionRef);

        if (!$response->successful()) {
            $error = $response->json();
            Log::error('Veripay payment status check failed', [
                'transaction_ref' => $transactionRef,
                'status' => $response->status(),
                'error' => $error,
            ]);

            throw new \Exception(
                $error['message'] ?? 'Gagal mendapatkan status pembayaran Veripay'
            );
        }

        $responseData = $response->json();

        if (!($responseData['success'] ?? false)) {
            throw new \Exception(
                $responseData['message'] ?? 'Gagal mendapatkan status pembayaran Veripay'
            );
        }

        return $responseData;
    }

    /**
     * Get payment transactions list
     * 
     * @return array Veripay response
     * @throws \Exception
     */
    public function getPaymentTransactions(): array
    {
        $response = Http::withHeaders($this->prepareHeaders())
            ->timeout(30)
            ->get($this->baseUrl . '/merchant/payments');

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan daftar transaksi Veripay');
        }

        return $response->json();
    }

    /**
     * Get merchant banks
     * 
     * @return array Veripay response
     * @throws \Exception
     */
    public function getMyBanks(): array
    {
        $response = Http::withHeaders($this->prepareHeaders())
            ->timeout(30)
            ->get($this->baseUrl . '/merchant/references/my-banks');

        if (!$response->successful()) {
            throw new \Exception('Gagal mendapatkan daftar bank merchant');
        }

        return $response->json();
    }

    /**
     * Create payout (withdraw)
     * 
     * @param array $data Payout data (amount, bank_code, account_number, account_name, notes)
     * @return array Veripay response
     * @throws \Exception
     */
    public function createPayout(array $data): array
    {
        $response = Http::withHeaders($this->prepareHeaders())
            ->timeout(30)
            ->post($this->baseUrl . '/merchant/payments/payout', $data);

        if (!$response->successful()) {
            $error = $response->json();
            Log::error('Veripay payout creation failed', [
                'status' => $response->status(),
                'error' => $error,
            ]);

            throw new \Exception(
                $error['message'] ?? 'Gagal membuat payout Veripay: ' . $response->body()
            );
        }

        $responseData = $response->json();

        if (!($responseData['success'] ?? false)) {
            throw new \Exception(
                $responseData['message'] ?? 'Gagal membuat payout Veripay'
            );
        }

        Log::info('Veripay payout created', [
            'transaction_ref' => $responseData['data']['transaction_ref'] ?? null,
        ]);

        return $responseData;
    }
}

