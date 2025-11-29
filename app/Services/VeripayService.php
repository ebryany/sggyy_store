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
        $this->baseUrl = rtrim($veripaySettings['base_url'] ?? config('services.veripay.base_url', 'https://veripay.site'), '/');
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
                Log::error('Veripay API credentials missing', [
                    'order_id' => $order->id,
                    'has_api_key' => !empty($this->apiKey),
                    'has_secret_key' => !empty($this->secretKey),
                ]);
                throw new \Exception('Veripay API credentials belum dikonfigurasi. Silakan hubungi administrator.');
            }

            // Make API request
            try {
                $response = Http::withHeaders($this->prepareHeaders())
                    ->timeout(30)
                    ->post($this->baseUrl . '/api/v1/merchant/payments', $paymentData);
            } catch (\Exception $e) {
                Log::error('Veripay API request failed (network/connection)', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'base_url' => $this->baseUrl,
                ]);
                throw new \Exception('Gagal membuat pembayaran Veripay: Koneksi ke server Veripay gagal. Silakan coba lagi.');
            }

            if (!$response->successful()) {
                $error = $response->json();
                $responseBody = $response->body();
                
                Log::error('Veripay payment creation failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $response->status(),
                    'error' => $error,
                    'response_body' => $responseBody,
                    'api_key_prefix' => substr($this->apiKey, 0, 8) . '...',
                ]);

                // Build error message
                $errorMessage = 'Gagal membuat pembayaran Veripay';
                
                if (isset($error['message'])) {
                    $errorMessage .= ': ' . $error['message'];
                } elseif (isset($error['errors']) && is_array($error['errors'])) {
                    $errorMessage .= ': ' . implode(', ', array_values($error['errors']));
                } elseif ($responseBody && strlen($responseBody) < 200) {
                    // Include short response body for debugging
                    $errorMessage .= ': ' . $responseBody;
                } else {
                    // Generic error based on HTTP status
                    switch ($response->status()) {
                        case 401:
                            $errorMessage .= ': Autentikasi gagal. Periksa API Key dan Secret Key.';
                            break;
                        case 403:
                            $errorMessage .= ': Akses ditolak. Periksa konfigurasi API.';
                            break;
                        case 422:
                            $errorMessage .= ': Data tidak valid. Periksa format request.';
                            break;
                        case 500:
                            $errorMessage .= ': Server error. Silakan coba lagi nanti.';
                            break;
                        default:
                            $errorMessage .= ': HTTP ' . $response->status();
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
     * Create payment for wallet top-up
     * 
     * @param object $orderData Object with order_number, total, user
     * @return array Veripay response
     * @throws \Exception
     */
    public function createPaymentForTopUp(object $orderData): array
    {
        // Validate API credentials before making request
        if (empty($this->apiKey) || empty($this->secretKey)) {
            Log::error('Veripay API credentials missing for top-up', [
                'order_number' => $orderData->order_number ?? null,
            ]);
            throw new \Exception('Veripay API credentials belum dikonfigurasi. Silakan hubungi administrator.');
        }

        // Prepare payment data
        $paymentData = [
            'order_id' => $orderData->order_number,
            'amount' => (int) $orderData->total,
            'description' => "Top-up Wallet #{$orderData->order_number}",
            'return_url' => route('wallet.index'),
            'product_detail' => [
                [
                    'name' => 'Top-up Wallet',
                    'price' => (int) $orderData->total,
                    'qty' => 1,
                ],
            ],
            'customer_detail' => [
                'name' => $orderData->user->name ?? 'Customer',
                'email' => $orderData->user->email ?? '',
                'phone' => $orderData->user->phone ?? '',
            ],
        ];

        // Make API request
        try {
            $response = Http::withHeaders($this->prepareHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/api/v1/merchant/payments', $paymentData);
        } catch (\Exception $e) {
            Log::error('Veripay top-up API request failed (network/connection)', [
                'order_number' => $orderData->order_number ?? null,
                'error' => $e->getMessage(),
                'base_url' => $this->baseUrl,
            ]);
            throw new \Exception('Gagal membuat pembayaran Veripay: Koneksi ke server Veripay gagal. Silakan coba lagi.');
        }

        if (!$response->successful()) {
            $error = $response->json();
            $responseBody = $response->body();
            
            Log::error('Veripay top-up payment creation failed', [
                'order_number' => $orderData->order_number ?? null,
                'status' => $response->status(),
                'error' => $error,
                'response_body' => $responseBody,
            ]);

            $errorMessage = 'Gagal membuat pembayaran Veripay';
            if (isset($error['message'])) {
                $errorMessage .= ': ' . $error['message'];
            } elseif ($responseBody && strlen($responseBody) < 200) {
                $errorMessage .= ': ' . $responseBody;
            }

            throw new \Exception($errorMessage);
        }

        $responseData = $response->json();

        if (!($responseData['success'] ?? false)) {
            throw new \Exception(
                $responseData['message'] ?? 'Gagal membuat pembayaran Veripay'
            );
        }

        Log::info('Veripay top-up payment created', [
            'order_number' => $orderData->order_number ?? null,
            'transaction_ref' => $responseData['data']['transaction_ref'] ?? null,
        ]);

        return $responseData;
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
            ->get($this->baseUrl . '/api/v1/merchant/payments/' . $transactionRef);

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
            ->get($this->baseUrl . '/api/v1/merchant/payments');

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
            ->get($this->baseUrl . '/api/v1/merchant/references/my-banks');

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
            ->post($this->baseUrl . '/api/v1/merchant/payments/payout', $data);

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

