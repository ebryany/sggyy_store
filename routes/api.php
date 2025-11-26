<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\FeaturedController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\Seller\SellerDashboardController;
use App\Http\Controllers\Api\Seller\SellerProductController;
use App\Http\Controllers\Api\Seller\SellerServiceController;
use App\Http\Controllers\Api\Seller\SellerOrderController;
use App\Http\Controllers\Api\Seller\SellerEarningController;
use App\Http\Controllers\Api\Seller\SellerVerificationController;
use App\Http\Controllers\Api\Admin\AdminDashboardController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminPaymentController;
use App\Http\Controllers\Api\Admin\AdminBannerController;
use App\Http\Controllers\Api\Admin\AdminSettingController;
use App\Http\Controllers\Api\Webhook\XenditWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes - Ebrystoree v1
|--------------------------------------------------------------------------
|
| Following 5 principles:
| 1. UUID untuk order & payment di URL
| 2. Slug untuk catalog public
| 3. Query filter seragam di semua list
| 4. Response konsisten (data/meta/errors)
| 5. Webhook namespace terpisah tanpa Sanctum
|
*/

// ========================================
// 1) Auth
// ========================================
Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

// ========================================
// 2) Public Catalog (slug-based)
// ========================================
Route::prefix('v1')->group(function () {
    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{product:slug}', [ProductController::class, 'show']);
    Route::get('products/{product:slug}/reviews', [ProductController::class, 'reviews']);
    
    // Services
    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{service:slug}', [ServiceController::class, 'show']);
    Route::get('services/{service:slug}/reviews', [ServiceController::class, 'reviews']);
});

// ========================================
// 3) Featured & Banner
// ========================================
Route::prefix('v1')->group(function () {
    Route::get('featured/products', [FeaturedController::class, 'products']);
    Route::get('featured/services', [FeaturedController::class, 'services']);
    Route::get('banners', [BannerController::class, 'index']);
});

// ========================================
// 4) Cart & Checkout (Authenticated)
// ========================================
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/items', [CartController::class, 'addItem']);
    Route::patch('cart/items/{item_uuid}', [CartController::class, 'updateItem']);
    Route::delete('cart/items/{item_uuid}', [CartController::class, 'removeItem']);
    
    Route::post('checkout', [CheckoutController::class, 'store']);
});

// ========================================
// 5) Orders (UUID-based, Authenticated)
// ========================================
Route::prefix('v1/orders')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::post('/', [OrderController::class, 'store']);
    Route::get('{order:uuid}', [OrderController::class, 'show']);
    Route::post('{order:uuid}/cancel', [OrderController::class, 'cancel']);
    
    // Service-specific actions
    Route::patch('{order:uuid}/progress', [OrderController::class, 'updateProgress']);
    Route::post('{order:uuid}/deliverables', [OrderController::class, 'uploadDeliverable']);
    Route::get('{order:uuid}/deliverables', [OrderController::class, 'getDeliverables']);
    Route::post('{order:uuid}/confirm', [OrderController::class, 'confirmCompletion']);
    Route::post('{order:uuid}/revision', [OrderController::class, 'requestRevision']);
    Route::post('{order:uuid}/dispute', [OrderController::class, 'dispute']);
});

// ========================================
// 6) Payments (UUID-based, Authenticated)
// ========================================
Route::prefix('v1/payments')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [PaymentController::class, 'create']);
    Route::get('{payment:uuid}', [PaymentController::class, 'show']);
    Route::post('{payment:uuid}/proof', [PaymentController::class, 'uploadProof']);
    Route::post('{payment:uuid}/cancel', [PaymentController::class, 'cancel']);
});

// ========================================
// 7) Download Produk Digital (Authenticated)
// ========================================
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('orders/{order:uuid}/downloads', [DownloadController::class, 'getDownloadLinks']);
    Route::get('downloads/{download_token}', [DownloadController::class, 'download'])
        ->name('api.downloads.download')
        ->middleware('signed');
});

// ========================================
// 8) Wallet & Rekber (Authenticated)
// ========================================
Route::prefix('v1/wallet')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [WalletController::class, 'index']);
    Route::get('transactions', [WalletController::class, 'transactions']);
    Route::post('topups', [WalletController::class, 'topup']);
    Route::post('withdrawals', [WalletController::class, 'withdrawal']);
    Route::get('withdrawals/{withdrawal:uuid}', [WalletController::class, 'getWithdrawal']);
});

// ========================================
// 9) Seller Area (UUID internal, Authenticated)
// ========================================
Route::prefix('v1/seller')->middleware(['auth:sanctum', 'seller'])->group(function () {
    // Dashboard
    Route::get('dashboard', [SellerDashboardController::class, 'index']);
    
    // Orders
    Route::get('orders', [SellerOrderController::class, 'index']);
    Route::get('orders/{order:uuid}', [SellerOrderController::class, 'show']);
    
    // Products
    Route::get('products', [SellerProductController::class, 'index']);
    Route::post('products', [SellerProductController::class, 'store']);
    Route::patch('products/{product:uuid}', [SellerProductController::class, 'update']);
    Route::delete('products/{product:uuid}', [SellerProductController::class, 'destroy']);
    
    // Services
    Route::get('services', [SellerServiceController::class, 'index']);
    Route::post('services', [SellerServiceController::class, 'store']);
    Route::patch('services/{service:uuid}', [SellerServiceController::class, 'update']);
    Route::delete('services/{service:uuid}', [SellerServiceController::class, 'destroy']);
    
    // Earnings
    Route::get('earnings', [SellerEarningController::class, 'index']);
    Route::post('earnings/{earning:uuid}/withdraw', [SellerEarningController::class, 'withdraw']);
    
    // Verification
    Route::post('verification', [SellerVerificationController::class, 'store']);
    Route::get('verification/status', [SellerVerificationController::class, 'status']);
});

// ========================================
// 10) Admin Area (Authenticated)
// ========================================
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index']);
    
    // Orders
    Route::get('orders', [AdminOrderController::class, 'index']);
    Route::patch('orders/{order:uuid}/status', [AdminOrderController::class, 'updateStatus']);
    
    // Payments
    Route::get('payments', [AdminPaymentController::class, 'index']);
    Route::patch('payments/{payment:uuid}/verify', [AdminPaymentController::class, 'verify']);
    Route::patch('payments/{payment:uuid}/reject', [AdminPaymentController::class, 'reject']);
    
    // Banners
    Route::get('banners', [AdminBannerController::class, 'index']);
    Route::get('banners/statistics', [AdminBannerController::class, 'statistics']);
    Route::get('banners/positions', [AdminBannerController::class, 'positions']);
    Route::post('banners', [AdminBannerController::class, 'store']);
    Route::get('banners/{banner:uuid}', [AdminBannerController::class, 'show']);
    Route::patch('banners/{banner:uuid}', [AdminBannerController::class, 'update']);
    Route::patch('banners/{banner:uuid}/toggle', [AdminBannerController::class, 'toggle']);
    Route::delete('banners/{banner:uuid}', [AdminBannerController::class, 'destroy']);
    Route::post('banners/{banner:uuid}/track-view', [AdminBannerController::class, 'trackView']);
    Route::post('banners/{banner:uuid}/track-click', [AdminBannerController::class, 'trackClick']);
    
    // Settings
    Route::get('settings', [AdminSettingController::class, 'index']);
    Route::patch('settings', [AdminSettingController::class, 'update']);
});

// ========================================
// 11) Notifications (Authenticated)
// ========================================
Route::prefix('v1/notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('unread', [NotificationController::class, 'unread']);
    Route::patch('{notification:uuid}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('read-all', [NotificationController::class, 'markAllAsRead']);
});

// ========================================
// 12) Chat (Authenticated)
// ========================================
Route::prefix('v1/chats')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [ChatController::class, 'index']);
    Route::post('/', [ChatController::class, 'create']);
    Route::get('{chat:uuid}/messages', [ChatController::class, 'messages']);
    Route::post('{chat:uuid}/messages', [ChatController::class, 'sendMessage']);
});

// ========================================
// 13) Webhooks (NO Sanctum, signature verification)
// ========================================
Route::prefix('v1/webhooks/xendit')
    ->middleware(['xendit.signature', 'webhook.throttle', 'xendit.ip'])
    ->group(function () {
        Route::post('payment', [XenditWebhookController::class, 'handlePayment']);
        Route::post('invoice', [XenditWebhookController::class, 'handleInvoice']);
        Route::post('disbursement', [XenditWebhookController::class, 'handleDisbursement']);
    });

// Health check endpoint (no signature/IP check needed)
Route::get('v1/webhooks/xendit/health', [XenditWebhookController::class, 'health']);

