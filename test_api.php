<?php
/**
 * Test API Khfy Store
 * 
 * File ini digunakan untuk testing koneksi dan semua endpoint API Khfy Store
 * 
 * Cara penggunaan:
 * 1. Ganti API_KEY_ANDA dengan API key yang sebenarnya
 * 2. Jalankan: php test_api.php
 * 3. Atau akses via browser: http://localhost:8000/test_api.php
 */

declare(strict_types=1);

// ============================================
// KONFIGURASI
// ============================================
$API_KEY = '0F8317F7-59D9-482D-8C3B-276FF527E356'; // GANTI DENGAN API KEY ANDA
$API_BASE_URL = 'https://panel.khfy-store.com/api_v2';
$API_V3_BASE_URL = 'https://panel.khfy-store.com/api_v3';

// Opsi untuk disable SSL verification (untuk testing local)
$DISABLE_SSL_VERIFY = true; // Set false untuk production

// ============================================
// FUNGSI HELPER
// ============================================

/**
 * Make HTTP GET request
 */
function makeRequest(string $url, array $options = []): array
{
    $ch = curl_init();
    
    // Extract disable_ssl from options (not a cURL option)
    $disableSSL = $options['disable_ssl'] ?? false;
    
    // Set cURL options directly (don't merge with non-cURL options)
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !$disableSSL);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $disableSSL ? 0 : 2);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'User-Agent: KhfyStore-API-Test/1.0',
    ]);
    
    // Apply any additional valid cURL options from $options
    foreach ($options as $key => $value) {
        if (is_int($key) && $key > 0) {
            // It's a CURLOPT constant
            curl_setopt($ch, $key, $value);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $errno = curl_errno($ch);
    
    curl_close($ch);
    
    return [
        'success' => $response !== false && $httpCode >= 200 && $httpCode < 300,
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
        'errno' => $errno,
        'data' => $response ? json_decode($response, true) : null,
    ];
}

/**
 * Print test result
 */
function printResult(string $title, array $result, bool $showRaw = false): void
{
    echo "\n" . str_repeat("=", 80) . "\n";
    echo "TEST: {$title}\n";
    echo str_repeat("=", 80) . "\n";
    
    if ($result['success']) {
        echo "✅ STATUS: SUCCESS (HTTP {$result['http_code']})\n\n";
        
        if ($result['data']) {
            echo "📦 RESPONSE DATA:\n";
            echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        } else {
            echo "📦 RESPONSE (Raw):\n";
            echo substr($result['response'], 0, 500) . "\n";
        }
    } else {
        echo "❌ STATUS: FAILED\n";
        echo "HTTP Code: {$result['http_code']}\n";
        
        if ($result['error']) {
            echo "cURL Error: {$result['error']} (Code: {$result['errno']})\n";
        }
        
        if ($result['response']) {
            echo "\n📦 RESPONSE:\n";
            echo substr($result['response'], 0, 500) . "\n";
        }
    }
    
    if ($showRaw && $result['response']) {
        echo "\n📄 RAW RESPONSE:\n";
        echo substr($result['response'], 0, 1000) . "\n";
    }
    
    echo "\n";
}

// ============================================
// TEST 1: GET /list_product
// ============================================
function testListProduct(string $apiKey, string $baseUrl, bool $disableSSL): array
{
    $url = "{$baseUrl}/list_product?api_key=" . urlencode($apiKey);
    
    $result = makeRequest($url, [
        'disable_ssl' => $disableSSL,
    ]);
    
    return $result;
}

// ============================================
// TEST 2: GET /trx (Create Transaction)
// ============================================
function testCreateTransaction(string $apiKey, string $baseUrl, bool $disableSSL): array
{
    // Test dengan produk dummy (ganti dengan produk yang valid)
    $produk = 'BPAL19'; // Ganti dengan kode produk yang valid
    $tujuan = '087812345678'; // Ganti dengan nomor tujuan yang valid
    $refId = 'test-' . uniqid();
    
    $url = "{$baseUrl}/trx?" . http_build_query([
        'produk' => $produk,
        'tujuan' => $tujuan,
        'reff_id' => $refId,
        'api_key' => $apiKey,
    ]);
    
    $result = makeRequest($url, [
        'disable_ssl' => $disableSSL,
    ]);
    
    return $result;
}

// ============================================
// TEST 3: GET /history (Check Transaction Status)
// ============================================
function testCheckHistory(string $apiKey, string $baseUrl, bool $disableSSL, string $refId = null): array
{
    // Jika tidak ada refId, gunakan dummy
    if (!$refId) {
        $refId = '123e4567-e89b-12d3-a456-426614174000';
    }
    
    $url = "{$baseUrl}/history?" . http_build_query([
        'api_key' => $apiKey,
        'refid' => $refId,
    ]);
    
    $result = makeRequest($url, [
        'disable_ssl' => $disableSSL,
    ]);
    
    return $result;
}

// ============================================
// TEST 4: GET /cek_stock_akrab (Check Stock)
// ============================================
function testCheckStock(string $apiKey, string $baseUrl, bool $disableSSL, string $type = 'XLA'): array
{
    $url = "{$baseUrl}/cek_stock_akrab?" . http_build_query([
        'api_key' => $apiKey,
        'type' => $type,
    ]);
    
    $result = makeRequest($url, [
        'disable_ssl' => $disableSSL,
    ]);
    
    return $result;
}

// ============================================
// MAIN EXECUTION
// ============================================

// Check if running from CLI or browser
$isCLI = php_sapi_name() === 'cli';

if (!$isCLI) {
    header('Content-Type: text/html; charset=utf-8');
    echo "<!DOCTYPE html>
<html>
<head>
    <title>Test API Khfy Store</title>
    <style>
        body { font-family: monospace; background: #1a1a1a; color: #fff; padding: 20px; }
        pre { background: #2a2a2a; padding: 15px; border-radius: 5px; overflow-x: auto; }
        .success { color: #4ade80; }
        .error { color: #f87171; }
        .info { color: #60a5fa; }
        h1 { color: #fbbf24; }
        hr { border-color: #444; }
    </style>
</head>
<body>
    <h1>🧪 Test API Khfy Store</h1>
    <pre>";
}

echo "╔══════════════════════════════════════════════════════════════════════════════╗\n";
echo "║                    TEST API KHFY STORE                                       ║\n";
echo "╚══════════════════════════════════════════════════════════════════════════════╝\n\n";

echo "📋 KONFIGURASI:\n";
echo "   API Base URL: {$API_BASE_URL}\n";
echo "   API V3 Base URL: {$API_V3_BASE_URL}\n";
echo "   API Key: " . substr($API_KEY, 0, 8) . "..." . (strlen($API_KEY) > 8 ? " (Length: " . strlen($API_KEY) . ")" : "") . "\n";
echo "   SSL Verification: " . ($DISABLE_SSL_VERIFY ? "DISABLED (Testing Mode)" : "ENABLED (Production Mode)") . "\n";
echo "\n";

// Check if API key is set
if ($API_KEY === 'API_KEY_ANDA' || empty($API_KEY)) {
    echo "⚠️  PERINGATAN: API Key belum diatur!\n";
    echo "   Silakan edit file test_api.php dan ganti 'API_KEY_ANDA' dengan API key Anda.\n\n";
    
    if (!$isCLI) {
        echo "</pre></body></html>";
    }
    exit(1);
}

// Run tests
echo "🚀 Memulai testing...\n\n";

// Test 1: List Products
$result1 = testListProduct($API_KEY, $API_BASE_URL, $DISABLE_SSL_VERIFY);
printResult("1. GET /list_product - Lihat Daftar Produk", $result1, true);

// Test 2: Check Stock XLA
$result2 = testCheckStock($API_KEY, $API_V3_BASE_URL, $DISABLE_SSL_VERIFY, 'XLA');
printResult("2. GET /cek_stock_akrab (XLA) - Cek Stock XLA", $result2, true);

// Test 3: Check Stock XDA
$result3 = testCheckStock($API_KEY, $API_V3_BASE_URL, $DISABLE_SSL_VERIFY, 'XDA');
printResult("3. GET /cek_stock_akrab (XDA) - Cek Stock XDA", $result3, true);

// Test 4: Check History (dummy refId)
$result4 = testCheckHistory($API_KEY, $API_BASE_URL, $DISABLE_SSL_VERIFY);
printResult("4. GET /history - Cek Status Transaksi (Dummy RefId)", $result4, false);

// Test 5: Create Transaction (commented out by default - uncomment to test)
// WARNING: This will create a real transaction!
/*
echo "\n" . str_repeat("=", 80) . "\n";
echo "⚠️  PERINGATAN: Test Create Transaction akan membuat transaksi REAL!\n";
echo "   Uncomment bagian ini di test_api.php jika ingin test.\n";
echo str_repeat("=", 80) . "\n\n";
// $result5 = testCreateTransaction($API_KEY, $API_BASE_URL, $DISABLE_SSL_VERIFY);
// printResult("5. GET /trx - Buat Transaksi Baru", $result5, true);
*/

// Summary
echo "\n" . str_repeat("=", 80) . "\n";
echo "📊 RINGKASAN HASIL TEST:\n";
echo str_repeat("=", 80) . "\n";
echo "1. List Products: " . ($result1['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";
echo "2. Check Stock XLA: " . ($result2['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";
echo "3. Check Stock XDA: " . ($result3['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";
echo "4. Check History: " . ($result4['success'] ? "✅ SUCCESS" : "❌ FAILED") . "\n";

$successCount = 0;
if ($result1['success']) $successCount++;
if ($result2['success']) $successCount++;
if ($result3['success']) $successCount++;
if ($result4['success']) $successCount++;

echo "\nTotal: {$successCount}/4 test berhasil\n";

if ($successCount === 4) {
    echo "\n🎉 Semua test berhasil! API dapat diakses dengan baik.\n";
} else {
    echo "\n⚠️  Beberapa test gagal. Periksa error di atas untuk detail.\n";
    echo "\n💡 TIPS:\n";
    echo "   - Pastikan API key sudah benar\n";
    echo "   - Pastikan koneksi internet stabil\n";
    echo "   - Coba set \$DISABLE_SSL_VERIFY = true jika ada masalah SSL\n";
    echo "   - Periksa apakah server API sedang online\n";
}

echo "\n";

if (!$isCLI) {
    echo "</pre></body></html>";
}

