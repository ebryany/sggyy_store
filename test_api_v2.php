<?php
/**
 * Test API Khfy Store v2
 * Berdasarkan dokumentasi resmi: https://panel.khfy-store.com
 * 
 * Endpoints:
 * - GET /list_product - Lihat semua produk
 * - GET /trx - Buat transaksi baru
 * - GET /history - Cek status transaksi
 * - GET /cek_stock_akrab - Cek stock akrab XL/Axis
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ============================================
// CONFIGURATION
// ============================================
$API_KEY = '0F8317F7-59D9-482D-8C3B-276FF527E356';
$API_BASE_URL = 'https://panel.khfy-store.com/api_v2';
$API_V3_BASE_URL = 'https://panel.khfy-store.com/api_v3';

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Make HTTP request dengan cURL
 */
function makeRequest(string $url, array $options = []): array
{
    $ch = curl_init();
    
    // Extract non-curl options first
    $disableSsl = $options['disable_ssl'] ?? false;
    unset($options['disable_ssl']);
    
    // Default options (only valid cURL options)
    $defaultOptions = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => !$disableSsl,
        CURLOPT_SSL_VERIFYHOST => $disableSsl ? 0 : 2,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_USERAGENT => 'KhfyStore-API-Test/1.0',
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Content-Type: application/json',
        ],
        CURLOPT_TCP_KEEPALIVE => 1,
        CURLOPT_TCP_KEEPIDLE => 10,
        CURLOPT_TCP_KEEPINTVL => 5,
    ];
    
    // Merge dengan custom options (filter hanya valid cURL options)
    $curlOptions = $defaultOptions;
    foreach ($options as $key => $value) {
        if (is_int($key) && defined('CURLOPT_' . $key)) {
            $curlOptions[$key] = $value;
        }
    }
    
    curl_setopt_array($ch, $curlOptions);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    $errno = curl_errno($ch);
    
    curl_close($ch);
    
    if ($response === false) {
        return [
            'success' => false,
            'error' => "cURL Error #{$errno}: {$error}",
            'http_code' => $httpCode,
        ];
    }
    
    $jsonData = json_decode($response, true);
    
    return [
        'success' => json_last_error() === JSON_ERROR_NONE,
        'http_code' => $httpCode,
        'data' => $jsonData,
        'raw' => $response,
    ];
}

/**
 * Format output untuk display
 */
function formatOutput(string $title, array $result): void
{
    echo "\n" . str_repeat('=', 80) . "\n";
    echo "TEST: {$title}\n";
    echo str_repeat('=', 80) . "\n";
    
    if (!$result['success']) {
        echo "❌ ERROR: {$result['error']}\n";
        echo "HTTP Code: {$result['http_code']}\n";
        return;
    }
    
    echo "✅ HTTP Code: {$result['http_code']}\n\n";
    
    if (isset($result['data'])) {
        echo "Response Data:\n";
        echo str_repeat('-', 80) . "\n";
        echo json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo "\n" . str_repeat('-', 80) . "\n";
    }
    
    // Analisis struktur data
    if (isset($result['data']) && is_array($result['data'])) {
        echo "\n📊 Analisis Struktur:\n";
        
        if (isset($result['data']['ok'])) {
            echo "  - Status: " . ($result['data']['ok'] ? 'OK' : 'ERROR') . "\n";
        }
        
        if (isset($result['data']['count'])) {
            echo "  - Count: {$result['data']['count']}\n";
        }
        
        if (isset($result['data']['provider'])) {
            echo "  - Provider: " . ($result['data']['provider'] ?? 'null') . "\n";
        }
        
        if (isset($result['data']['data']) && is_array($result['data']['data'])) {
            $products = $result['data']['data'];
            $count = count($products);
            echo "  - Jumlah Produk: {$count}\n";
            
            if ($count > 0) {
                $firstProduct = $products[0];
                echo "  - Sample Product Fields:\n";
                foreach (array_keys($firstProduct) as $key) {
                    $value = $firstProduct[$key];
                    $type = gettype($value);
                    $preview = is_string($value) && strlen($value) > 50 
                        ? substr($value, 0, 50) . '...' 
                        : $value;
                    echo "    • {$key}: ({$type}) " . json_encode($preview, JSON_UNESCAPED_UNICODE) . "\n";
                }
                
                // Analisis provider
                $providers = [];
                foreach ($products as $product) {
                    $provider = $product['kode_provider'] ?? $product['provider'] ?? 'Unknown';
                    if (!isset($providers[$provider])) {
                        $providers[$provider] = 0;
                    }
                    $providers[$provider]++;
                }
                
                echo "\n  - Provider Distribution:\n";
                foreach ($providers as $provider => $count) {
                    echo "    • {$provider}: {$count} produk\n";
                }
            }
        }
    }
    
    echo "\n";
}

// ============================================
// TEST 1: LIST PRODUCT
// ============================================
echo "\n🚀 MULAI TEST API KHFY STORE v2\n";
echo str_repeat('=', 80) . "\n";
echo "API Key: " . substr($API_KEY, 0, 8) . "...\n";
echo "Base URL: {$API_BASE_URL}\n";

$url = "{$API_BASE_URL}/list_product?api_key=" . urlencode($API_KEY);
$result = makeRequest($url);
formatOutput('GET /list_product - Daftar Semua Produk', $result);

// Simpan data produk untuk test selanjutnya
$products = [];
if ($result['success'] && isset($result['data']['data']) && is_array($result['data']['data'])) {
    $products = $result['data']['data'];
    echo "✅ Berhasil mengambil " . count($products) . " produk\n";
} else {
    echo "❌ Gagal mengambil produk atau format tidak valid\n";
    exit(1);
}

// ============================================
// TEST 2: CEK STOCK AKRAB (XLA & XDA)
// ============================================
if (!empty($products)) {
    // Cari produk XLA dan XDA
    $xlaProduct = null;
    $xdaProduct = null;
    
    foreach ($products as $product) {
        $kode = $product['kode_produk'] ?? '';
        $provider = $product['kode_provider'] ?? '';
        
        if (strpos($kode, 'XLA') !== false && $provider === 'KUBER' && !$xlaProduct) {
            $xlaProduct = $product;
        }
        if (strpos($kode, 'XDA') !== false && $provider === 'KUBERV2' && !$xdaProduct) {
            $xdaProduct = $product;
        }
        
        if ($xlaProduct && $xdaProduct) break;
    }
    
    // Test XLA Stock
    if ($xlaProduct) {
        $url = "{$API_V3_BASE_URL}/cek_stock_akrab?api_key=" . urlencode($API_KEY) . "&provider=KUBER";
        $result = makeRequest($url);
        formatOutput('GET /cek_stock_akrab - Stock XLA (KUBER)', $result);
    } else {
        echo "\n⚠️  Produk XLA (KUBER) tidak ditemukan untuk test stock\n";
    }
    
    // Test XDA Stock
    if ($xdaProduct) {
        $url = "{$API_V3_BASE_URL}/cek_stock_akrab?api_key=" . urlencode($API_KEY) . "&provider=KUBERV2";
        $result = makeRequest($url);
        formatOutput('GET /cek_stock_akrab - Stock XDA (KUBERV2)', $result);
    } else {
        echo "\n⚠️  Produk XDA (KUBERV2) tidak ditemukan untuk test stock\n";
    }
}

// ============================================
// TEST 3: BUAT TRANSAKSI (DEMO - JANGAN DI-EXECUTE)
// ============================================
echo "\n" . str_repeat('=', 80) . "\n";
echo "TEST 3: BUAT TRANSAKSI (DEMO - TIDAK DI-EXECUTE)\n";
echo str_repeat('=', 80) . "\n";
echo "⚠️  Transaksi tidak di-execute untuk menghindari charge yang tidak perlu\n";
echo "Format URL:\n";
echo "  GET {$API_BASE_URL}/trx?produk=KODE_PRODUK&tujuan=NOMOR_TUJUAN&reff_id=UUID&api_key=API_KEY\n\n";

if (!empty($products)) {
    $sampleProduct = $products[0];
    $sampleKode = $sampleProduct['kode_produk'] ?? 'BPAL1';
    $sampleRefId = '123e4567-e89b-12d3-a456-426614174000';
    $sampleTujuan = '087812345678';
    
    $demoUrl = "{$API_BASE_URL}/trx?produk=" . urlencode($sampleKode) 
        . "&tujuan=" . urlencode($sampleTujuan)
        . "&reff_id=" . urlencode($sampleRefId)
        . "&api_key=" . urlencode($API_KEY);
    
    echo "Contoh URL (TIDAK DI-EXECUTE):\n";
    echo "  {$demoUrl}\n\n";
}

// ============================================
// TEST 4: CEK HISTORY (DEMO)
// ============================================
echo str_repeat('=', 80) . "\n";
echo "TEST 4: CEK HISTORY (DEMO)\n";
echo str_repeat('=', 80) . "\n";
echo "Format URL:\n";
echo "  GET {$API_BASE_URL}/history?api_key=API_KEY&refid=REF_ID\n\n";

$sampleRefId = '123e4567-e89b-12d3-a456-426614174000';
$demoUrl = "{$API_BASE_URL}/history?api_key=" . urlencode($API_KEY) 
    . "&refid=" . urlencode($sampleRefId);

echo "Contoh URL (TIDAK DI-EXECUTE):\n";
echo "  {$demoUrl}\n\n";

// ============================================
// TEST 5: ANALISIS STRUKTUR DATA UNTUK FRONTEND
// ============================================
echo str_repeat('=', 80) . "\n";
echo "TEST 5: ANALISIS STRUKTUR DATA UNTUK FRONTEND\n";
echo str_repeat('=', 80) . "\n";

if (!empty($products)) {
    // Group by provider
    $grouped = [];
    foreach ($products as $product) {
        $provider = $product['kode_provider'] ?? $product['provider'] ?? 'Unknown';
        if (!isset($grouped[$provider])) {
            $grouped[$provider] = [];
        }
        
        // Normalize product data
        $normalized = [
            'kode' => $product['kode_produk'] ?? $product['kode'] ?? '',
            'nama' => $product['nama_produk'] ?? $product['nama'] ?? 'Produk',
            'harga' => $product['harga_final'] ?? $product['harga'] ?? 0,
            'provider' => $provider,
            'deskripsi' => $product['deskripsi'] ?? '',
            'gangguan' => $product['gangguan'] ?? 0,
            'kosong' => $product['kosong'] ?? 0,
        ];
        
        $grouped[$provider][] = $normalized;
    }
    
    echo "📦 Struktur Data yang Dikembalikan (Grouped by Provider):\n";
    echo json_encode($grouped, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo "\n\n";
    
    echo "📊 Summary:\n";
    echo "  - Total Provider: " . count($grouped) . "\n";
    foreach ($grouped as $provider => $providerProducts) {
        echo "  - {$provider}: " . count($providerProducts) . " produk\n";
    }
    echo "\n";
    
    // Sample untuk Alpine.js
    echo "💡 Sample Data untuk Alpine.js (products variable):\n";
    $sampleGrouped = array_slice($grouped, 0, 2, true); // Ambil 2 provider pertama
    foreach ($sampleGrouped as $provider => $providerProducts) {
        $sampleGrouped[$provider] = array_slice($providerProducts, 0, 2); // Ambil 2 produk pertama
    }
    echo "  products: " . json_encode($sampleGrouped, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n\n";
}

// ============================================
// TEST 6: WEBHOOK SIMULATION
// ============================================
echo str_repeat('=', 80) . "\n";
echo "TEST 6: WEBHOOK SIMULATION\n";
echo str_repeat('=', 80) . "\n";

$sampleWebhookMessages = [
    // Sukses
    'RC=123e4567-e89b-12d3-a456-426614174000 TrxID=12345 BPAL19.087812345678 SUKSES Transaksi berhasil diproses Saldo: 100000 result=0',
    // Gagal
    'RC=123e4567-e89b-12d3-a456-426614174001 TrxID=12346 BPAL19.087812345678 GAGAL Saldo tidak cukup Saldo: 5000 result=1',
    // Tanpa result=
    'RC=123e4567-e89b-12d3-a456-426614174002 TrxID=12347 XLA14.087812345678 SUKSES Transaksi berhasil diproses Saldo: 95000',
];

$pattern = '~RC=(?P<reffid>[a-f0-9-]+)\s+TrxID=(?P<trxid>\d+)\s+(?P<produk>[A-Z0-9]+)\.(?P<tujuan>\d+)\s+(?P<status_text>[A-Za-z]+)\s*(?P<keterangan>.+?)(?:\s+Saldo[\s\S]*?)?(?:\bresult=(?P<status_code>\d+))?\s*>?$~is';

echo "Testing Webhook Message Parsing:\n\n";

foreach ($sampleWebhookMessages as $index => $message) {
    echo "Message " . ($index + 1) . ":\n";
    echo "  Raw: {$message}\n";
    
    if (preg_match($pattern, $message, $m)) {
        $statusCode = null;
        if (isset($m['status_code']) && $m['status_code'] !== '') {
            $statusCode = (int)$m['status_code'];
        } else {
            if (preg_match('~sukses~i', $m['status_text'])) {
                $statusCode = 0;
            } elseif (preg_match('~gagal|batal~i', $m['status_text'])) {
                $statusCode = 1;
            }
        }
        
        echo "  ✅ Parsed:\n";
        echo "    - reffid: {$m['reffid']}\n";
        echo "    - trxid: {$m['trxid']}\n";
        echo "    - produk: {$m['produk']}\n";
        echo "    - tujuan: {$m['tujuan']}\n";
        echo "    - status_text: {$m['status_text']}\n";
        echo "    - status_code: " . ($statusCode !== null ? $statusCode : 'null') . "\n";
        echo "    - keterangan: " . trim($m['keterangan']) . "\n";
    } else {
        echo "  ❌ Failed to parse\n";
    }
    echo "\n";
}

// ============================================
// SUMMARY
// ============================================
echo str_repeat('=', 80) . "\n";
echo "✅ TEST SELESAI\n";
echo str_repeat('=', 80) . "\n";
echo "Catatan:\n";
echo "  - Pastikan API key valid dan aktif\n";
echo "  - Pastikan koneksi internet stabil\n";
echo "  - Untuk test transaksi, gunakan nomor tujuan yang valid\n";
echo "  - Webhook harus di-set di Profile → Pengaturan di panel Khfy Store\n";
echo "\n";

