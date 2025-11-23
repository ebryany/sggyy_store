<?php
/**
 * Test Webhook Quota Endpoint
 * 
 * Script untuk menguji webhook endpoint quota
 * Endpoint: https://39908a651f51.ngrok-free.app/webhook/quota
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ============================================
// CONFIGURATION
// ============================================
$WEBHOOK_URL = 'https://39908a651f51.ngrok-free.app/webhook/quota';
// Atau untuk local: 'http://localhost:8000/webhook/quota'

// ============================================
// HELPER FUNCTIONS
// ============================================

function formatOutput(string $title, array $result): void
{
    echo "\n" . str_repeat('-', 60) . "\n";
    echo "{$title}\n";
    echo str_repeat('-', 60) . "\n";
    
    if (isset($result['ok'])) {
        echo "Status: " . ($result['ok'] ? '✓ OK' : '✗ ERROR') . "\n";
    }
    
    if (isset($result['error'])) {
        echo "Error: {$result['error']}\n";
    }
    
    if (isset($result['parsed'])) {
        echo "\nParsed Data:\n";
        foreach ($result['parsed'] as $key => $value) {
            echo "  - {$key}: " . (is_array($value) ? json_encode($value) : $value) . "\n";
        }
    }
    
    if (isset($result['message'])) {
        echo "Message: {$result['message']}\n";
    }
    
    echo "\nFull Response:\n";
    echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
}

function sendWebhook(string $url, string $message, string $method = 'POST'): array
{
    $ch = curl_init();
    
    if ($method === 'GET') {
        // GET: message sebagai query parameter
        $urlWithParams = $url . '?message=' . urlencode($message);
        curl_setopt($ch, CURLOPT_URL, $urlWithParams);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    } else {
        // POST: message sebagai body atau form data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        
        // Coba sebagai form data dulu
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['message' => $message]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'WebhookTest/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    if ($error) {
        return [
            'ok' => false,
            'error' => 'cURL Error: ' . $error,
            'http_code' => $httpCode,
        ];
    }
    
    // Try to parse JSON response
    $json = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        return array_merge($json, ['http_code' => $httpCode]);
    }
    
    // If not JSON, return raw response
    return [
        'ok' => false,
        'error' => 'Response is not JSON',
        'http_code' => $httpCode,
        'raw_response' => substr($response, 0, 500), // First 500 chars
    ];
}

// ============================================
// SAMPLE WEBHOOK MESSAGES
// ============================================

$sampleWebhookMessages = [
    // Success message
    'success' => 'RC=123e4567-e89b-12d3-a456-426614174000 TrxID=12345 BPAL19.087812345678 SUKSES Transaksi berhasil diproses Saldo: 100000 result=0',
    
    // Failed message
    'failed' => 'RC=123e4567-e89b-12d3-a456-426614174001 TrxID=12346 BPAL19.087812345678 GAGAL Saldo tidak cukup Saldo: 5000 result=1',
    
    // Success without result code (should auto-detect from status_text)
    'success_auto' => 'RC=123e4567-e89b-12d3-a456-426614174002 TrxID=12347 XLA14.087812345678 SUKSES Transaksi berhasil diproses Saldo: 95000',
    
    // Failed without result code
    'failed_auto' => 'RC=123e4567-e89b-12d3-a456-426614174003 TrxID=12348 XDA39.087812345678 GAGAL Transaksi gagal diproses Saldo: 5000',
    
    // Processing (should not exist, but test anyway)
    'processing' => 'RC=123e4567-e89b-12d3-a456-426614174004 TrxID=12349 BPAL19.087812345678 PROCESSING Sedang diproses result=2',
];

// ============================================
// TEST WEBHOOK
// ============================================

echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST WEBHOOK QUOTA ENDPOINT\n";
echo str_repeat('=', 80) . "\n";
echo "URL: {$WEBHOOK_URL}\n";
echo "\n⚠️  Catatan:\n";
echo "  1. Pastikan ada transaksi di database dengan ref_id yang sesuai\n";
echo "  2. Atau update ref_id di pesan webhook dengan ref_id yang ada di database\n";
echo "  3. Webhook akan menolak jika ref_id tidak ditemukan\n\n";

// Test 1: Empty message
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 1: Empty Message (Should return error)\n";
echo str_repeat('=', 80) . "\n";
$result = sendWebhook($WEBHOOK_URL, '', 'POST');
formatOutput('POST Empty Message', $result);

// Test 2: Invalid format
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 2: Invalid Format (Should return format error)\n";
echo str_repeat('=', 80) . "\n";
$result = sendWebhook($WEBHOOK_URL, 'Invalid format message', 'POST');
formatOutput('POST Invalid Format', $result);

// Test 3: Success webhook (POST)
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 3: Success Webhook (POST)\n";
echo str_repeat('=', 80) . "\n";
echo "⚠️  Update ref_id dengan ref_id transaksi yang ada di database!\n";
$message = $sampleWebhookMessages['success'];
$result = sendWebhook($WEBHOOK_URL, $message, 'POST');
formatOutput('POST Success Message', $result);

// Test 4: Failed webhook (POST)
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 4: Failed Webhook (POST)\n";
echo str_repeat('=', 80) . "\n";
echo "⚠️  Update ref_id dengan ref_id transaksi yang ada di database!\n";
$message = $sampleWebhookMessages['failed'];
$result = sendWebhook($WEBHOOK_URL, $message, 'POST');
formatOutput('POST Failed Message', $result);

// Test 5: Success webhook (GET)
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 5: Success Webhook (GET)\n";
echo str_repeat('=', 80) . "\n";
echo "⚠️  Update ref_id dengan ref_id transaksi yang ada di database!\n";
$message = $sampleWebhookMessages['success_auto'];
$result = sendWebhook($WEBHOOK_URL, $message, 'GET');
formatOutput('GET Success Message', $result);

// Test 6: Test dengan ref_id dari database
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 6: Test dengan Ref ID dari Database\n";
echo str_repeat('=', 80) . "\n";
echo "Untuk test ini, silakan:\n";
echo "1. Buat transaksi quota di aplikasi\n";
echo "2. Copy ref_id dari transaksi tersebut\n";
echo "3. Update script ini dengan ref_id yang benar\n";
echo "4. Jalankan ulang script ini\n\n";

echo "Format pesan webhook:\n";
echo "RC=<REF_ID> TrxID=<TRX_ID> <PRODUK>.<TUJUAN> <STATUS_TEXT> <KETERANGAN> result=<STATUS_CODE>\n\n";

// Uncomment dan update ref_id di bawah untuk test dengan data real
/*
$realRefId = '123e4567-e89b-12d3-a456-426614174000'; // UPDATE INI dengan ref_id dari database
$realTrxId = '99999'; // UPDATE INI dengan trx_id yang diinginkan
$realMessage = "RC={$realRefId} TrxID={$realTrxId} BPAL19.087812345678 SUKSES Transaksi berhasil diproses result=0";
$result = sendWebhook($WEBHOOK_URL, $realMessage, 'POST');
formatOutput('POST Real Transaction', $result);
*/

echo "\n" . str_repeat('=', 80) . "\n";
echo "SELESAI\n";
echo str_repeat('=', 80) . "\n";
echo "\n📝 Catatan Penting:\n";
echo "  - Webhook endpoint harus public (tidak ada middleware auth)\n";
echo "  - Pastikan ref_id di webhook message sesuai dengan ref_id di database\n";
echo "  - Webhook akan menolak jika format tidak sesuai\n";
echo "  - Webhook akan menolak jika ref_id tidak ditemukan\n";
echo "  - Webhook akan auto-refund saldo jika transaksi gagal\n";
echo "\n";

