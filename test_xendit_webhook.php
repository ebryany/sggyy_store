<?php
/**
 * Test Script untuk Xendit Webhook
 * 
 * Usage: php test_xendit_webhook.php
 * 
 * Script ini akan mengirim berbagai jenis webhook payload ke endpoint webhook
 * untuk testing dan debugging.
 */

$webhookUrl = 'http://43.106.24.25/webhooks/xendit';
$webhookToken = 'Hx8MLQqtemU04yQzqknKkVv5XMjgtEZvPdWtLovQZjN7Lvea'; // Ganti dengan webhook token dari settings

// Test Case 1: Invoice Payment (PAID)
$invoicePaidPayload = [
    'id' => 'inv_test_' . time(),
    'external_id' => 'EBR-TEST-' . time(),
    'status' => 'PAID',
    'amount' => 100000,
    'description' => 'Test Invoice Payment',
    'payment_method' => 'BANK_TRANSFER',
    'created' => date('c'),
    'updated' => date('c'),
    'paid_at' => date('c'),
    'customer' => [
        'given_names' => 'Test',
        'email' => 'test@example.com',
    ],
];

// Test Case 2: Invoice Payment (EXPIRED)
$invoiceExpiredPayload = [
    'id' => 'inv_expired_' . time(),
    'external_id' => 'EBR-EXPIRED-' . time(),
    'status' => 'EXPIRED',
    'amount' => 100000,
    'description' => 'Test Invoice Expired',
    'created' => date('c'),
    'updated' => date('c'),
];

// Test Case 3: E-Wallet Payment (SUCCEEDED) - dengan struktur data wrapper
$ewalletPayload = [
    'data' => [
        'id' => 'ewc_test_' . time(),
        'status' => 'SUCCEEDED',
        'amount' => 100000,
        'currency' => 'IDR',
        'created' => date('c'),
        'updated' => date('c'),
        'metadata' => [
            'external_id' => 'EBR-EWALLET-' . time(),
        ],
    ],
    'event' => 'ewallet.payment.succeeded',
];

// Test Case 4: Invoice Payment (root level, tanpa data wrapper)
$invoiceRootPayload = [
    'id' => 'inv_root_' . time(),
    'external_id' => 'EBR-ROOT-' . time(),
    'status' => 'PAID',
    'amount' => 100000,
    'payment_method' => 'BANK_TRANSFER',
];

function sendWebhook($url, $token, $payload) {
    $ch = curl_init($url);
    
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'X-Callback-Token: ' . $token,
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 30,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error,
    ];
}

echo "=== Xendit Webhook Test Script ===\n\n";

if ($webhookToken === 'YOUR_WEBHOOK_TOKEN_HERE') {
    echo "⚠️  ERROR: Silakan set webhook token di script ini!\n";
    echo "   Edit file test_xendit_webhook.php dan ganti YOUR_WEBHOOK_TOKEN_HERE\n\n";
    exit(1);
}

echo "Webhook URL: {$webhookUrl}\n";
echo "Webhook Token: " . substr($webhookToken, 0, 10) . "...\n\n";

// Test 1: Invoice Paid
echo "Test 1: Invoice Payment (PAID)\n";
echo "Payload: " . json_encode($invoicePaidPayload, JSON_PRETTY_PRINT) . "\n";
$result1 = sendWebhook($webhookUrl, $webhookToken, $invoicePaidPayload);
echo "HTTP Code: {$result1['http_code']}\n";
echo "Response: {$result1['response']}\n";
if ($result1['error']) {
    echo "Error: {$result1['error']}\n";
}
echo "\n---\n\n";

// Test 2: Invoice Expired
echo "Test 2: Invoice Payment (EXPIRED)\n";
echo "Payload: " . json_encode($invoiceExpiredPayload, JSON_PRETTY_PRINT) . "\n";
$result2 = sendWebhook($webhookUrl, $webhookToken, $invoiceExpiredPayload);
echo "HTTP Code: {$result2['http_code']}\n";
echo "Response: {$result2['response']}\n";
if ($result2['error']) {
    echo "Error: {$result2['error']}\n";
}
echo "\n---\n\n";

// Test 3: E-Wallet (dengan data wrapper)
echo "Test 3: E-Wallet Payment (dengan data wrapper)\n";
echo "Payload: " . json_encode($ewalletPayload, JSON_PRETTY_PRINT) . "\n";
$result3 = sendWebhook($webhookUrl, $webhookToken, $ewalletPayload);
echo "HTTP Code: {$result3['http_code']}\n";
echo "Response: {$result3['response']}\n";
if ($result3['error']) {
    echo "Error: {$result3['error']}\n";
}
echo "\n---\n\n";

// Test 4: Invoice Root Level
echo "Test 4: Invoice Payment (root level)\n";
echo "Payload: " . json_encode($invoiceRootPayload, JSON_PRETTY_PRINT) . "\n";
$result4 = sendWebhook($webhookUrl, $webhookToken, $invoiceRootPayload);
echo "HTTP Code: {$result4['http_code']}\n";
echo "Response: {$result4['response']}\n";
if ($result4['error']) {
    echo "Error: {$result4['error']}\n";
}
echo "\n=== Test Selesai ===\n";

