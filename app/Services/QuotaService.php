<?php

namespace App\Services;

use App\Models\QuotaTransaction;
use App\Models\User;
use App\Services\WalletService;
use App\Services\SettingsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class QuotaService
{
    private const API_BASE_URL = 'https://panel.khfy-store.com/api_v2';
    private const API_V3_BASE_URL = 'https://panel.khfy-store.com/api_v3';

    public function __construct(
        private WalletService $walletService,
        private SettingsService $settingsService
    ) {}

    /**
     * Get API key from settings
     */
    private function getApiKey(): ?string
    {
        $apiKey = $this->settingsService->get('khfy_api_key');
        
        // Log for debugging (without exposing full key)
        if ($apiKey) {
            Log::debug('QuotaService: API key retrieved', [
                'key_length' => strlen($apiKey),
                'key_prefix' => substr($apiKey, 0, 8) . '...',
            ]);
        } else {
            Log::warning('QuotaService: API key not found in settings');
        }
        
        return $apiKey;
    }

    /**
     * Get list of available products from API
     * Cached for 30 minutes to reduce API calls and improve reliability
     */
    public function getProducts(): array
    {
        // Cache products for 30 minutes to reduce API calls
        // Cache key includes API key to ensure fresh data if API key changes
        $apiKey = $this->getApiKey();
        
        if (!$apiKey) {
            throw new \Exception('API key belum dikonfigurasi. Silakan hubungi admin.');
        }
        
        $cacheKey = 'quota_products_' . md5($apiKey);
        
        return Cache::remember($cacheKey, 30 * 60, function () use ($apiKey) {
            return $this->fetchProductsFromApi($apiKey);
        });
    }
    
    /**
     * Clear products cache (useful after syncing products)
     */
    public function clearProductsCache(): void
    {
        $apiKey = $this->getApiKey();
        if ($apiKey) {
            $cacheKey = 'quota_products_' . md5($apiKey);
            Cache::forget($cacheKey);
            Log::info('QuotaService: Products cache cleared');
        }
    }
    
    /**
     * Fetch products from API (without cache)
     */
    private function fetchProductsFromApi(string $apiKey): array
    {
        if (!$apiKey) {
            throw new \Exception('API key belum dikonfigurasi. Silakan hubungi admin.');
        }

        try {
            // Log API call attempt
            Log::info('QuotaService: Attempting to fetch products', [
                'url' => self::API_BASE_URL . '/list_product',
                'api_key_length' => strlen($apiKey),
                'api_key_prefix' => substr($apiKey, 0, 8) . '...',
            ]);

            // Configure HTTP client with better error handling
            // For local development, disable SSL verification to avoid certificate issues
            $verifySSL = config('app.env') === 'production' && config('app.debug') === false;
            
            // Try with retry mechanism - Increased for large responses
            $maxRetries = 5;
            $baseRetryDelay = 2000; // 2 seconds base delay
            $response = null;
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    // Exponential backoff with jitter
                    if ($attempt > 1) {
                        $delayMs = $baseRetryDelay * pow(2, $attempt - 2) + rand(500, 1500);
                        Log::info("QuotaService: Retrying attempt {$attempt}/{$maxRetries} after {$delayMs}ms delay", [
                            'attempt' => $attempt,
                            'delay_ms' => $delayMs,
                        ]);
                        usleep($delayMs * 1000); // Convert to microseconds
                    }
                    
                    // Increased timeout for large response (61 products)
                    $response = Http::timeout(60) // Increased from 30 to 60
                        ->retry(2, 3000) // Additional retry at HTTP client level
                        ->withOptions([
                            'verify' => $verifySSL,
                            'http_errors' => false,
                            'allow_redirects' => true,
                            'connect_timeout' => 30, // Increased from 20 to 30
                            'timeout' => 60, // Increased from 30 to 60
                            'curl' => [
                                CURLOPT_SSL_VERIFYPEER => $verifySSL,
                                CURLOPT_SSL_VERIFYHOST => $verifySSL ? 2 : 0,
                                CURLOPT_TCP_KEEPALIVE => 1,
                                CURLOPT_TCP_KEEPIDLE => 10,
                                CURLOPT_TCP_KEEPINTVL => 5,
                                CURLOPT_TCP_NODELAY => 1, // Disable Nagle's algorithm
                                CURLOPT_BUFFERSIZE => 16384, // 16KB buffer
                                CURLOPT_ENCODING => '', // Enable gzip compression if available
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            ],
                        ])
                        ->get(self::API_BASE_URL . '/list_product', [
                            'api_key' => $apiKey,
                        ]);
                    
                    // If successful, break retry loop
                    if ($response->successful()) {
                        Log::info('QuotaService: Products fetched successfully', [
                            'attempt' => $attempt,
                            'status' => $response->status(),
                        ]);
                        break;
                    }
                    
                    // If last attempt, throw error
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: API request failed after all retries', [
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'http_status' => $response->status(),
                            'response_body' => substr($response->body(), 0, 500),
                        ]);
                        throw new \Exception("API request failed after {$maxRetries} attempts. HTTP Status: " . $response->status());
                    }
                    
                    // Log failed attempt
                    Log::warning("QuotaService: API request failed, will retry", [
                        'attempt' => $attempt,
                        'next_attempt' => $attempt + 1,
                        'http_status' => $response->status(),
                    ]);
                    
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // Connection error - retry with exponential backoff
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: Connection failed after retries', [
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'error' => $e->getMessage(),
                            'error_code' => $e->getCode(),
                            'url' => self::API_BASE_URL . '/list_product',
                        ]);
                        throw new \Exception('Koneksi ke server API terputus setelah ' . $maxRetries . ' percobaan. Pastikan koneksi internet stabil atau server API sedang online. Silakan coba lagi nanti.');
                    }
                    
                    $delayMs = $baseRetryDelay * pow(2, $attempt - 1) + rand(500, 1500);
                    Log::warning("QuotaService: Connection error, retrying...", [
                        'attempt' => $attempt,
                        'next_attempt' => $attempt + 1,
                        'delay_ms' => $delayMs,
                        'error' => $e->getMessage(),
                        'error_code' => $e->getCode(),
                    ]);
                    // Delay will be handled at the start of next loop iteration
                    
                } catch (\Exception $e) {
                    // Other errors - retry if not last attempt
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: Request failed after retries', [
                            'attempt' => $attempt,
                            'error' => $e->getMessage(),
                            'error_code' => $e->getCode(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        throw $e;
                    }
                    
                    $delayMs = $baseRetryDelay * pow(2, $attempt - 1) + rand(500, 1500);
                    Log::warning("QuotaService: Request error, retrying...", [
                        'attempt' => $attempt,
                        'next_attempt' => $attempt + 1,
                        'delay_ms' => $delayMs,
                        'error' => $e->getMessage(),
                    ]);
                    // Delay will be handled at the start of next loop iteration
                }
            }
            
            // Check if we have a response
            if (!$response) {
                throw new \Exception('Tidak ada response dari API setelah ' . $maxRetries . ' percobaan.');
            }

            // Check for connection errors
            if ($response->failed()) {
                $status = $response->status();
                $body = $response->body();
                $error = $response->toException();
                
                Log::error('QuotaService: API request failed', [
                    'status' => $status,
                    'body' => substr($body, 0, 500), // Limit log size
                    'url' => self::API_BASE_URL . '/list_product',
                    'error_message' => $error ? $error->getMessage() : 'Unknown error',
                    'error_code' => $error ? $error->getCode() : null,
                ]);
                
                // Provide more specific error messages
                if ($status >= 400 && $status < 500) {
                    $errorMessage = "API mengembalikan error (Status: {$status}). Periksa API key atau hubungi admin.";
                } elseif ($status >= 500) {
                    $errorMessage = "Server API sedang mengalami masalah (Status: {$status}). Silakan coba lagi nanti.";
                } else {
                    $errorMessage = 'Gagal terhubung ke server API. Pastikan koneksi internet Anda stabil atau coba lagi nanti.';
                }
                
                throw new \Exception($errorMessage);
            }

            $data = $response->json();
            
            // Handle different API response formats
            $products = [];
            if (is_array($data)) {
                // If data has 'data' key (format: {"ok": true, "data": [...]})
                if (isset($data['data']) && is_array($data['data'])) {
                    $products = $data['data'];
                }
                // If data is already an array of products
                elseif (isset($data[0]) && is_array($data[0])) {
                    $products = $data;
                }
                // If data has 'products' key
                elseif (isset($data['products']) && is_array($data['products'])) {
                    $products = $data['products'];
                }
                // Otherwise use data as is
                else {
                    $products = $data;
                }
            }
            
            /**
             * Get provider display name mapping
             * This is a public static method so it can be used elsewhere (e.g., in controller)
             */
            $providerDisplayNames = self::getProviderDisplayNames();
            $providerDisplayToCode = array_flip($providerDisplayNames);
            
            // Normalize product data and group by provider display name
            $grouped = [];
            foreach ($products as $product) {
                // Normalize field names (handle both formats: kode_produk/nama_produk/kode_provider/harga_final and kode/nama/provider/harga)
                $normalized = [
                    'kode' => $product['kode_produk'] ?? $product['kode'] ?? $product['code'] ?? $product['product_code'] ?? $product['sku'] ?? '',
                    'nama' => $product['nama_produk'] ?? $product['nama'] ?? $product['name'] ?? $product['product_name'] ?? $product['title'] ?? 'Produk',
                    'harga' => $product['harga_final'] ?? $product['harga'] ?? $product['price'] ?? 0,
                    'provider_code' => strtoupper($product['kode_provider'] ?? $product['provider'] ?? $product['operator'] ?? 'Unknown'),
                    'deskripsi' => $product['deskripsi'] ?? $product['description'] ?? $product['desk'] ?? '',
                    'gangguan' => $product['gangguan'] ?? 0,
                    'kosong' => $product['kosong'] ?? 0,
                    // Keep original data for reference
                    'original' => $product,
                ];
                
                $providerCode = $normalized['provider_code'];
                
                // Get display name for provider
                $providerDisplayName = $providerDisplayNames[$providerCode] ?? $providerCode;
                
                // Use display name as key for grouping
                if (!isset($grouped[$providerDisplayName])) {
                    $grouped[$providerDisplayName] = [];
                }
                
                // Keep provider_code in each product for API calls
                $normalized['provider'] = $providerCode;
                
                $grouped[$providerDisplayName][] = $normalized;
            }

            return $grouped;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('QuotaService: Connection error fetching products', [
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'url' => self::API_BASE_URL . '/list_product',
                'trace' => $e->getTraceAsString(),
            ]);
            
            // More specific error messages based on error type
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'SSL') || str_contains($errorMessage, 'certificate')) {
                throw new \Exception('Masalah SSL certificate dengan server API. Hubungi admin untuk mengatasi masalah ini.');
            } elseif (str_contains($errorMessage, 'timeout') || str_contains($errorMessage, 'timed out')) {
                throw new \Exception('Koneksi ke server API timeout. Server mungkin sedang sibuk. Silakan coba lagi nanti.');
            } elseif (str_contains($errorMessage, 'Connection was reset') || str_contains($errorMessage, 'reset')) {
                throw new \Exception('Koneksi ke server API terputus. Pastikan koneksi internet stabil atau server API sedang online. Silakan coba lagi nanti.');
            } else {
                throw new \Exception('Gagal terhubung ke server API. Pastikan koneksi internet Anda stabil atau coba lagi nanti. Jika masalah berlanjut, hubungi admin.');
            }
        } catch (\Exception $e) {
            Log::error('QuotaService: Error fetching products', [
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            // If error message already contains user-friendly message, use it
            if (str_contains($e->getMessage(), 'Gagal') || str_contains($e->getMessage(), 'Masalah')) {
                throw $e;
            }
            
            // Don't expose internal error details to user
            $userMessage = 'Gagal mengambil daftar produk. ';
            if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'cURL')) {
                $userMessage .= 'Masalah koneksi ke server API. Silakan coba lagi nanti atau hubungi admin.';
            } else {
                $userMessage .= 'Silakan coba lagi atau hubungi admin jika masalah berlanjut.';
            }
            
            throw new \Exception($userMessage);
        }
    }

    /**
     * Get product price by product code from cached products
     */
    private function getProductPrice(string $productCode): ?float
    {
        try {
            $products = $this->getProducts();
            
            // Search through all providers
            foreach ($products as $providerProducts) {
                if (is_array($providerProducts)) {
                    foreach ($providerProducts as $product) {
                        if (isset($product['kode']) && $product['kode'] === $productCode) {
                            return (float) ($product['harga'] ?? 0);
                        }
                    }
                }
            }
            
            Log::warning('QuotaService: Product not found in cache', [
                'product_code' => $productCode,
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('QuotaService: Error getting product price', [
                'product_code' => $productCode,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create new quota transaction
     */
    public function purchaseQuota(User $user, array $data): QuotaTransaction
    {
        $apiKey = $this->getApiKey();
        
        if (!$apiKey) {
            throw new \Exception('API key belum dikonfigurasi. Silakan hubungi admin.');
        }

        // Validate required fields
        if (empty($data['produk']) || empty($data['tujuan'])) {
            throw new \Exception('Produk dan nomor tujuan harus diisi.');
        }

        // Validate phone number format
        if (!preg_match('/^08\d{9,12}$/', $data['tujuan'])) {
            throw new \Exception('Format nomor tujuan tidak valid. Gunakan format 08xxxxxxxxxx');
        }

        // Get product price from cached products BEFORE creating transaction
        $productPrice = $this->getProductPrice($data['produk']);
        
        if ($productPrice === null) {
            throw new \Exception('Produk tidak ditemukan. Silakan refresh halaman dan coba lagi.');
        }
        
        if ($productPrice <= 0) {
            throw new \Exception('Harga produk tidak valid. Silakan hubungi admin.');
        }

        // Validate wallet balance BEFORE creating transaction
        if (!$this->walletService->hasSufficientBalance($user, $productPrice)) {
            throw new \Exception('Saldo wallet tidak mencukupi. Saldo Anda: Rp ' . number_format($user->wallet_balance, 0, ',', '.') . ', dibutuhkan: Rp ' . number_format($productPrice, 0, ',', '.'));
        }

        // Generate unique ref_id (UUID)
        $refId = $data['ref_id'] ?? (string) Str::uuid();

        return DB::transaction(function () use ($user, $data, $apiKey, $refId, $productPrice) {
            // Get current balance before deduction
            $oldBalance = $user->wallet_balance;
            
            // Create transaction record with correct price
            // Note: saldo_akhir initially same as saldo_awal (no deduction yet)
            $transaction = QuotaTransaction::create([
                'user_id' => $user->id,
                'ref_id' => $refId,
                'produk' => $data['produk'],
                'tujuan' => $data['tujuan'],
                'harga' => $productPrice,
                'status' => 'pending',
                'status_code' => null,
                'status_text' => 'Menunggu',
                'keterangan' => 'Transaksi sedang diproses',
                'saldo_awal' => $oldBalance,
                'saldo_akhir' => $oldBalance, // Initially same, will be updated after deduction
            ]);

            try {
                // Call API to create transaction
                $verifySSL = config('app.env') === 'production' && config('app.debug') === false;
                
                $response = Http::timeout(40) // Increased for reliability
                    ->retry(3, 2000) // More retries with longer delay
                    ->withOptions([
                        'verify' => $verifySSL,
                        'http_errors' => false,
                        'allow_redirects' => true,
                        'connect_timeout' => 25, // Increased
                        'timeout' => 40, // Increased
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => $verifySSL,
                            CURLOPT_SSL_VERIFYHOST => $verifySSL ? 2 : 0,
                        ],
                    ])
                    ->get(self::API_BASE_URL . '/trx', [
                        'produk' => $data['produk'],
                        'tujuan' => $data['tujuan'],
                        'reff_id' => $refId,
                        'api_key' => $apiKey,
                    ]);

                // Parse response - handle both JSON and plain text
                $responseData = null;
                try {
                    $responseData = $response->json();
                } catch (\Exception $e) {
                    // If not JSON, treat as plain text error
                    $responseBody = $response->body();
                    Log::warning('QuotaService: API returned non-JSON response', [
                        'body' => substr($responseBody, 0, 500),
                        'status' => $response->status(),
                    ]);
                    $responseData = ['message' => $responseBody ?: 'Gagal membuat transaksi'];
                }
                
                // Check for error messages in response (even if HTTP status is 200)
                $errorMessage = null;
                $hasError = false;
                
                // Check response body for error indicators
                if (isset($responseData['ok']) && $responseData['ok'] === false) {
                    $hasError = true;
                    $errorMessage = $responseData['msg'] ?? $responseData['message'] ?? $responseData['error'] ?? 'Gagal membuat transaksi';
                } elseif (isset($responseData['status']) && in_array(strtolower($responseData['status']), ['failed', 'error', 'gagal'])) {
                    $hasError = true;
                    $errorMessage = $responseData['message'] ?? $responseData['keterangan'] ?? 'Transaksi gagal';
                } elseif (isset($responseData['message']) || isset($responseData['msg'])) {
                    // Check if message contains error keywords (insufficient balance, etc)
                    $message = strtolower($responseData['msg'] ?? $responseData['message'] ?? '');
                    $errorKeywords = [
                        'saldo tidak cukup', 'saldo kurang', 'insufficient', 'balance', 
                        'saldo habis', 'saldo kosong', 'out of stock', 'stok habis',
                        'gagal', 'error', 'failed', 'tidak bisa', 'tidak dapat',
                        'isi saldo', 'saldo tidak mencukupi'
                    ];
                    
                    foreach ($errorKeywords as $keyword) {
                        if (str_contains($message, $keyword)) {
                            $hasError = true;
                            $errorMessage = $responseData['msg'] ?? $responseData['message'];
                            break;
                        }
                    }
                }
                
                // Also check if HTTP status indicates failure
                if ($response->failed() || $response->serverError() || $response->clientError()) {
                    $hasError = true;
                    if (!$errorMessage) {
                        $errorMessage = $responseData['message'] ?? $responseData['error'] ?? $response->body() ?? 'Gagal membuat transaksi (HTTP ' . $response->status() . ')';
                    }
                }
                
                // Log API response for debugging
                Log::info('QuotaService: API transaction response', [
                    'ref_id' => $refId,
                    'http_status' => $response->status(),
                    'has_error' => $hasError,
                    'error_message' => $errorMessage,
                    'response_data' => $responseData,
                ]);
                
                // Update transaction with API response
                // Only proceed if: HTTP success, no error detected, and response data exists
                // If API returns error (including provider balance insufficient), transaction should FAIL immediately
                if ($response->successful() && !$hasError && $responseData !== null && !empty($responseData)) {
                    // API response format may vary, handle different structures
                    $status = 'pending';
                    $statusText = 'Processing';
                    
                    // Use price from product data (already set), but allow API to override if provided
                    $finalPrice = $productPrice;
                    if (isset($responseData['harga']) && $responseData['harga'] > 0) {
                        $finalPrice = (float) $responseData['harga'];
                    } elseif (isset($responseData['price']) && $responseData['price'] > 0) {
                        $finalPrice = (float) $responseData['price'];
                    }
                    
                    if (isset($responseData['status'])) {
                        $status = $responseData['status'] === 'success' ? 'processing' : 'pending';
                        $statusText = $responseData['status'] ?? 'Processing';
                    }
                    
                    // Deduct from wallet ONLY AFTER confirmed successful API call
                    // Balance already validated before transaction creation
                    $this->walletService->deductBalance(
                        $user,
                        $finalPrice,
                        "Pembelian kuota: {$data['produk']} - {$data['tujuan']}"
                    );
                    
                    // Refresh user to get updated balance after deduction
                    $user->refresh();
                    
                    $transaction->update([
                        'trx_id' => $responseData['trx_id'] ?? $responseData['trxid'] ?? null,
                        'status' => $status,
                        'status_text' => $statusText,
                        'keterangan' => $responseData['message'] ?? $responseData['keterangan'] ?? 'Transaksi sedang diproses',
                        'harga' => $finalPrice,
                        'saldo_akhir' => $user->wallet_balance,
                    ]);
                    
                    Log::info('QuotaService: Balance deducted after successful API call', [
                        'transaction_id' => $transaction->id,
                        'ref_id' => $refId,
                        'amount_deducted' => $finalPrice,
                        'new_balance' => $user->wallet_balance,
                    ]);
                } else {
                    // API call failed or returned error - refund if balance was already deducted
                    if (!$errorMessage) {
                        $errorMessage = $responseData['message'] ?? $responseData['error'] ?? $response->body() ?? 'Gagal membuat transaksi';
                    }
                    
                    // Check if balance was already deducted (if harga > 0 and saldo_akhir < saldo_awal)
                    $wasDeducted = ($transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal);
                    
                    if ($wasDeducted) {
                        // Refund balance that was already deducted
                        $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
                        $this->walletService->addBalance(
                            $user,
                            $transaction->harga,
                            "Refund: Pembelian kuota gagal - {$data['produk']}",
                            'refund'
                        );
                        $user->refresh();
                    }
                    
                    $transaction->update([
                        'status' => 'failed',
                        'status_text' => 'Gagal',
                        'keterangan' => $errorMessage,
                        'saldo_akhir' => $user->wallet_balance,
                    ]);
                    
                    throw new \Exception($errorMessage);
                }

                Log::info('QuotaService: Transaction created', [
                    'transaction_id' => $transaction->id,
                    'ref_id' => $refId,
                    'user_id' => $user->id,
                ]);

                return $transaction->fresh();
            } catch (\Exception $e) {
                // Refresh user to get current balance (in case it changed)
                $user->refresh();
                
                // Check if balance was deducted before error occurred
                // If saldo_akhir < saldo_awal, it means balance was deducted
                $wasDeducted = ($transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal);
                
                // If balance was deducted but transaction failed, refund it
                if ($wasDeducted) {
                    Log::warning('QuotaService: Transaction failed after balance deduction, refunding', [
                        'transaction_id' => $transaction->id,
                        'ref_id' => $refId,
                        'amount_to_refund' => $transaction->harga,
                    ]);
                    
                    // Lock user row to prevent race condition
                    $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
                    
                    // Refund balance
                    $this->walletService->addBalance(
                        $user,
                        $transaction->harga,
                        "Refund: Pembelian kuota gagal - {$data['produk']}",
                        'refund'
                    );
                    
                    $user->refresh();
                }
                
                $transaction->update([
                    'status' => 'failed',
                    'status_text' => 'Gagal',
                    'keterangan' => $e->getMessage(),
                    'saldo_akhir' => $user->wallet_balance, // Updated after refund if needed
                ]);
                
                Log::error('QuotaService: Transaction failed', [
                    'transaction_id' => $transaction->id,
                    'ref_id' => $refId,
                    'error' => $e->getMessage(),
                    'was_deducted' => $wasDeducted,
                    'refunded' => $wasDeducted,
                    'final_balance' => $user->wallet_balance,
                    'trace' => $e->getTraceAsString(),
                ]);
                
                // Provide user-friendly error message
                if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'cURL')) {
                    throw new \Exception('Gagal terhubung ke server API. Pastikan koneksi internet Anda stabil atau coba lagi nanti.');
                }
                
                throw $e;
            }
        });
    }

    /**
     * Check transaction status by ref_id
     */
    public function checkStatus(string $refId): ?array
    {
        $apiKey = $this->getApiKey();
        
        if (!$apiKey) {
            return null;
        }

        try {
            $verifySSL = config('app.env') === 'production' && config('app.debug') === false;
            
            $response = Http::timeout(15)
                ->retry(1, 500)
                ->withOptions([
                    'verify' => $verifySSL,
                    'http_errors' => false,
                    'connect_timeout' => 10,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => $verifySSL,
                        CURLOPT_SSL_VERIFYHOST => $verifySSL ? 2 : 0,
                    ],
                ])
                ->get(self::API_BASE_URL . '/history', [
                    'api_key' => $apiKey,
                    'refid' => $refId,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('QuotaService: Error checking status', [
                'ref_id' => $refId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get provider display name mapping
     * Maps API provider codes to user-friendly display names
     */
    public static function getProviderDisplayNames(): array
    {
        return [
            'KUBER' => 'Akrab fresh (XLA)',
            'KUBERV2' => 'Akrab Fresh (XDA)',
            'FMX' => 'FlexMax (FMX)',
            'FLEXMAX' => 'FlexMax (FMX)',
            'XLA' => 'Akrab fresh (XLA)',
            'XDA' => 'Akrab Fresh (XDA)',
        ];
    }

    /**
     * Convert provider display name back to provider code
     * Used when processing form submission
     */
    public static function getProviderCodeFromDisplayName(string $displayName): string
    {
        $displayNames = self::getProviderDisplayNames();
        $codeMap = array_flip($displayNames);
        
        // Check if it's already a code
        if (isset($displayNames[strtoupper($displayName)])) {
            return strtoupper($displayName);
        }
        
        // Check if it's a display name
        if (isset($codeMap[$displayName])) {
            return $codeMap[$displayName];
        }
        
        // Default: return as is (uppercase)
        return strtoupper($displayName);
    }

    /**
     * Check stock for XLA or XDA
     */
    public function checkStock(string $type = 'XLA'): array
    {
        $apiKey = $this->getApiKey();
        
        if (!$apiKey) {
            throw new \Exception('API key belum dikonfigurasi.');
        }

        try {
            // Note: Endpoint might need different parameters, adjust based on actual API
            $verifySSL = config('app.env') === 'production' && config('app.debug') === false;
            
            // Try with retry mechanism - Similar to getProducts but optimized for smaller responses
            $maxRetries = 4;
            $baseRetryDelay = 2000;
            $response = null;
            
            // Map type to provider parameter and endpoint
            $providerMap = [
                'XLA' => 'KUBER',
                'XDA' => 'KUBERV2',
            ];
            $endpointMap = [
                'XLA' => '/cek_stock_akrab',
                'XDA' => '/cek_stock_akrab_v2',
            ];
            $provider = $providerMap[$type] ?? 'KUBER';
            $endpoint = $endpointMap[$type] ?? '/cek_stock_akrab';
            
            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                try {
                    // Exponential backoff with jitter
                    if ($attempt > 1) {
                        $delayMs = $baseRetryDelay * pow(2, $attempt - 2) + rand(500, 1500);
                        Log::info("QuotaService: Retrying stock check attempt {$attempt}/{$maxRetries} after {$delayMs}ms delay", [
                            'type' => $type,
                            'provider' => $provider,
                            'endpoint' => $endpoint,
                            'attempt' => $attempt,
                            'delay_ms' => $delayMs,
                        ]);
                        usleep($delayMs * 1000);
                    }
                    
                    $response = Http::timeout(40) // Increased for reliability
                        ->retry(2, 2000)
                        ->withOptions([
                            'verify' => $verifySSL,
                            'http_errors' => false,
                            'allow_redirects' => true,
                            'connect_timeout' => 25,
                            'timeout' => 40,
                            'curl' => [
                                CURLOPT_SSL_VERIFYPEER => $verifySSL,
                                CURLOPT_SSL_VERIFYHOST => $verifySSL ? 2 : 0,
                                CURLOPT_TCP_KEEPALIVE => 1,
                                CURLOPT_TCP_KEEPIDLE => 10,
                                CURLOPT_TCP_KEEPINTVL => 5,
                                CURLOPT_TCP_NODELAY => 1,
                                CURLOPT_BUFFERSIZE => 16384,
                                CURLOPT_ENCODING => '',
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            ],
                        ])
                        ->get(self::API_V3_BASE_URL . $endpoint, [
                            'api_key' => $apiKey,
                            'provider' => $provider, // Use provider parameter instead of type
                        ]);
                    
                    // If successful, break retry loop
                    if ($response->successful()) {
                        Log::info('QuotaService: Stock check successful', [
                            'type' => $type,
                            'provider' => $provider,
                            'endpoint' => $endpoint,
                            'attempt' => $attempt,
                            'status' => $response->status(),
                        ]);
                        break;
                    }
                    
                    // If last attempt, throw error
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: Stock check failed after all retries', [
                            'attempt' => $attempt,
                            'type' => $type,
                            'provider' => $provider,
                            'endpoint' => $endpoint,
                            'http_status' => $response->status(),
                        ]);
                        throw new \Exception("API request failed after {$maxRetries} attempts. HTTP Status: " . $response->status());
                    }
                    
                    Log::warning("QuotaService: Stock check failed, will retry", [
                        'attempt' => $attempt,
                        'type' => $type,
                        'provider' => $provider,
                        'endpoint' => $endpoint,
                        'http_status' => $response->status(),
                    ]);
                    
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // Connection error - retry with exponential backoff
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: Stock check connection failed after retries', [
                            'attempt' => $attempt,
                            'max_retries' => $maxRetries,
                            'type' => $type,
                            'provider' => $provider,
                            'endpoint' => $endpoint,
                            'error' => $e->getMessage(),
                            'error_code' => $e->getCode(),
                        ]);
                        throw new \Exception('Koneksi ke server API terputus setelah ' . $maxRetries . ' percobaan. Pastikan koneksi internet stabil atau server API sedang online. Silakan coba lagi nanti.');
                    }
                    
                    $delayMs = $baseRetryDelay * pow(2, $attempt - 1) + rand(500, 1500);
                    Log::warning("QuotaService: Stock check connection error, retrying...", [
                        'attempt' => $attempt,
                        'next_attempt' => $attempt + 1,
                        'type' => $type,
                        'delay_ms' => $delayMs,
                        'error' => $e->getMessage(),
                    ]);
                    
                } catch (\Exception $e) {
                    // Other errors - retry if not last attempt
                    if ($attempt === $maxRetries) {
                        Log::error('QuotaService: Stock check failed after retries', [
                            'attempt' => $attempt,
                            'type' => $type,
                            'provider' => $provider,
                            'endpoint' => $endpoint,
                            'error' => $e->getMessage(),
                            'error_code' => $e->getCode(),
                        ]);
                        throw $e;
                    }
                    
                    $delayMs = $baseRetryDelay * pow(2, $attempt - 1) + rand(500, 1500);
                    Log::warning("QuotaService: Stock check error, retrying...", [
                        'attempt' => $attempt,
                        'next_attempt' => $attempt + 1,
                        'type' => $type,
                        'delay_ms' => $delayMs,
                        'error' => $e->getMessage(),
                    ]);
                    // Delay will be handled at the start of next loop iteration
                }
            }
            
            // Check if we have a response
            if (!$response) {
                throw new \Exception('Tidak ada response dari API setelah ' . $maxRetries . ' percobaan untuk stock check.');
            }

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle response format: {"ok":true,"message":"success","data":[...]}
                $stockData = [];
                if (isset($data['data']) && is_array($data['data'])) {
                    $stockData = $data['data'];
                } elseif (isset($data['stock']) && is_array($data['stock'])) {
                    $stockData = $data['stock'];
                } elseif (is_array($data) && isset($data[0])) {
                    $stockData = $data;
                }
                
                // Return in consistent format
                return [
                    'type' => $type,
                    'stock' => $stockData,
                    'message' => $data['message'] ?? ($data['ok'] ? 'success' : 'Stock berhasil dicek'),
                    'ok' => $data['ok'] ?? true,
                ];
            }

            throw new \Exception('Gagal mengecek stock. Status: ' . $response->status());
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('QuotaService: Connection error checking stock', [
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Gagal terhubung ke server API untuk mengecek stock. Silakan coba lagi nanti.');
        } catch (\Exception $e) {
            Log::error('QuotaService: Error checking stock', [
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            $userMessage = 'Gagal mengecek stock. ';
            if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'cURL')) {
                $userMessage .= 'Masalah koneksi ke server API. Silakan coba lagi nanti.';
            } else {
                $userMessage .= $e->getMessage();
            }
            
            throw new \Exception($userMessage);
        }
    }

    /**
     * Process webhook callback
     */
    public function processWebhook(string $message): array
    {
        // Parse webhook message using regex (flexible pattern)
        $pattern = '~RC=(?P<reffid>[a-f0-9-]+)\s+TrxID=(?P<trxid>\d+)\s+(?P<produk>[A-Z0-9]+)\.(?P<tujuan>\d+)\s+(?P<status_text>[A-Za-z]+)\s*(?P<keterangan>.+?)(?:\s+Saldo[\s\S]*?)?(?:\bresult=(?P<status_code>\d+))?\s*>?$~is';

        if (!preg_match($pattern, $message, $matches)) {
            Log::warning('QuotaService: Webhook format tidak dikenali', [
                'message' => $message,
            ]);
            return ['ok' => false, 'error' => 'format tidak dikenali'];
        }

        $refId = $matches['reffid'] ?? '';
        $trxId = $matches['trxid'] ?? '';
        $produk = $matches['produk'] ?? '';
        $tujuan = $matches['tujuan'] ?? '';
        $statusText = $matches['status_text'] ?? '';
        $keterangan = trim($matches['keterangan'] ?? '');
        
        // Normalize status_code
        $statusCode = null;
        if (isset($matches['status_code']) && $matches['status_code'] !== '') {
            $statusCode = (int) $matches['status_code'];
        } else {
            if (preg_match('~sukses~i', $statusText)) {
                $statusCode = 0;
            } elseif (preg_match('~gagal|batal~i', $statusText)) {
                $statusCode = 1;
            }
        }

        // Process webhook in DB transaction to ensure atomicity
        return DB::transaction(function () use ($refId, $trxId, $produk, $tujuan, $statusText, $keterangan, $statusCode, $message) {
            // Lock transaction row to prevent race condition
            $transaction = QuotaTransaction::where('ref_id', $refId)
                ->lockForUpdate()
                ->first();
            
            if (!$transaction) {
                Log::warning('QuotaService: Transaction not found for webhook', [
                    'ref_id' => $refId,
                    'message' => $message,
                ]);
                return [
                    'ok' => false,
                    'error' => 'Transaction not found',
                ];
            }

            // Idempotency check: If transaction already has same status, skip update
            $newStatus = match($statusCode) {
                0 => 'success',
                1 => 'failed',
                default => 'processing',
            };

            // Check if status already updated (prevent duplicate processing)
            if ($transaction->status === $newStatus && $transaction->trx_id === $trxId) {
                Log::info('QuotaService: Webhook already processed (idempotency)', [
                    'ref_id' => $refId,
                    'trx_id' => $trxId,
                    'status' => $newStatus,
                ]);
                
                return [
                    'ok' => true,
                    'parsed' => [
                        'trxid' => $trxId,
                        'reffid' => $refId,
                        'produk' => $produk,
                        'tujuan' => $tujuan,
                        'status_text' => $statusText,
                        'status_code' => $statusCode,
                        'keterangan' => $keterangan,
                    ],
                    'message' => 'Already processed',
                ];
            }

            // Store old status for refund logic
            $oldStatus = $transaction->status;
            $oldTrxId = $transaction->trx_id;

            // Update transaction with webhook data
            $transaction->update([
                'trx_id' => $trxId,
                'status' => $newStatus,
                'status_code' => $statusCode,
                'status_text' => $statusText,
                'keterangan' => $keterangan,
            ]);

            // Handle wallet refund if transaction failed
            if ($newStatus === 'failed' && $oldStatus !== 'failed') {
                // Transaction failed - refund the deducted balance
                $user = $transaction->user;
                
                // Only refund if balance was actually deducted (check harga > 0 and saldo_akhir < saldo_awal)
                if ($transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal) {
                    $refundAmount = $transaction->harga;
                    
                    // Lock user row to prevent race condition
                    $user = User::where('id', $user->id)
                        ->lockForUpdate()
                        ->firstOrFail();
                    
                    // Refund balance
                    $user->increment('wallet_balance', $refundAmount);
                    
                    // Update transaction saldo_akhir after refund
                    $user->refresh();
                    $transaction->update([
                        'saldo_akhir' => $user->wallet_balance,
                    ]);
                    
                    Log::info('QuotaService: Wallet refunded due to failed transaction', [
                        'ref_id' => $refId,
                        'user_id' => $user->id,
                        'refund_amount' => $refundAmount,
                        'new_balance' => $user->wallet_balance,
                    ]);
                }
            }

            // If transaction is successful and price was not set, try to get from API
            if ($newStatus === 'success' && $transaction->harga == 0) {
                // Optionally fetch price from API history endpoint
                $historyData = $this->checkStatus($refId);
                if ($historyData && isset($historyData['harga'])) {
                    $transaction->update(['harga' => (float) $historyData['harga']]);
                }
            }

            Log::info('QuotaService: Webhook processed', [
                'ref_id' => $refId,
                'trx_id' => $trxId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'status_code' => $statusCode,
                'refunded' => ($newStatus === 'failed' && $oldStatus !== 'failed'),
            ]);

            return [
                'ok' => true,
                'parsed' => [
                    'trxid' => $trxId,
                    'reffid' => $refId,
                    'produk' => $produk,
                    'tujuan' => $tujuan,
                    'status_text' => $statusText,
                    'status_code' => $statusCode,
                    'keterangan' => $keterangan,
                ],
            ];
        });
    }

    /**
     * Get user's transaction history
     */
    public function getHistory(User $user, int $limit = 50): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return QuotaTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Cancel pending transaction and refund if balance was deducted
     */
    public function cancelTransaction(User $user, string $refId): QuotaTransaction
    {
        return DB::transaction(function () use ($user, $refId) {
            $transaction = QuotaTransaction::where('ref_id', $refId)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Only allow canceling pending transactions
            if (!in_array($transaction->status, ['pending', 'processing'])) {
                throw new \Exception('Hanya transaksi pending atau processing yang dapat dibatalkan.');
            }

            // Check if balance was deducted
            $wasDeducted = ($transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal);

            // Refund if balance was deducted
            if ($wasDeducted) {
                $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
                $this->walletService->addBalance(
                    $user,
                    $transaction->harga,
                    "Refund: Pembatalan transaksi kuota - {$transaction->produk}",
                    'refund'
                );
                $user->refresh();
            }

            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'status_text' => 'Dibatalkan',
                'keterangan' => 'Transaksi dibatalkan oleh user',
                'saldo_akhir' => $user->wallet_balance,
            ]);

            Log::info('QuotaService: Transaction cancelled', [
                'transaction_id' => $transaction->id,
                'ref_id' => $refId,
                'was_deducted' => $wasDeducted,
                'refunded' => $wasDeducted,
                'final_balance' => $user->wallet_balance,
            ]);

            return $transaction->fresh();
        });
    }

    /**
     * Refund transaction (admin or user can request)
     */
    public function refundTransaction(User $user, string $refId): QuotaTransaction
    {
        return DB::transaction(function () use ($user, $refId) {
            $transaction = QuotaTransaction::where('ref_id', $refId)
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Check if already refunded
            if ($transaction->status === 'failed' && $transaction->saldo_akhir >= $transaction->saldo_awal) {
                throw new \Exception('Transaksi ini sudah di-refund sebelumnya.');
            }

            // Check if balance was deducted
            $wasDeducted = ($transaction->harga > 0 && $transaction->saldo_akhir < $transaction->saldo_awal);

            if (!$wasDeducted) {
                throw new \Exception('Tidak ada saldo yang perlu di-refund. Saldo tidak pernah dipotong.');
            }

            // Refund balance
            $user = User::where('id', $user->id)->lockForUpdate()->firstOrFail();
            $this->walletService->addBalance(
                $user,
                $transaction->harga,
                "Refund: Pengembalian saldo transaksi kuota - {$transaction->produk}",
                'refund'
            );
            $user->refresh();

            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'status_text' => 'Di-refund',
                'keterangan' => 'Saldo telah dikembalikan ke wallet',
                'saldo_akhir' => $user->wallet_balance,
            ]);

            Log::info('QuotaService: Transaction refunded', [
                'transaction_id' => $transaction->id,
                'ref_id' => $refId,
                'refund_amount' => $transaction->harga,
                'final_balance' => $user->wallet_balance,
            ]);

            return $transaction->fresh();
        });
    }
}

