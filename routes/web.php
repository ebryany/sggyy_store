<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\SellerVerificationController;
use App\Http\Controllers\Admin\AdminSellerVerificationController;
use App\Http\Controllers\Seller\SellerWithdrawalController;
use App\Http\Controllers\Admin\AdminWithdrawalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ChatController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');

// Store Profile Routes (Public)
Route::get('/store/{slug}', [StoreController::class, 'show'])->name('store.show');
Route::post('/store/{slug}/follow', [StoreController::class, 'toggleFollow'])
    ->name('store.toggleFollow')
    ->middleware('auth');

// Chat Routes (Authenticated) - Username-based URLs
// Note: Route pattern uses literal @ in URL, but controller handles username with or without @
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/@{username}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/@{username}/start', [ChatController::class, 'startChat'])->name('chat.start');
    Route::post('/chat/@{username}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/@{username}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = \Illuminate\Support\Facades\Auth::user();
            
            // 🔒 SECURITY: Clear failed login attempts on successful login
            \App\Http\Middleware\ThrottleFailedLogins::clearFailedAttempts($credentials['email']);
            
            // Redirect based on user role
            // Clear any intended URL to prevent redirecting to wrong dashboard
            $request->session()->forget('url.intended');
            
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            } elseif ($user->isSeller()) {
                // Refresh user to get latest verification status (important for cloud)
                $user->refresh();
                // Check if seller is verified
                if ($user->isVerifiedSeller()) {
                    return redirect('/seller/dashboard');
                } else {
                    // Seller but not verified yet, redirect to verification page
                    return redirect('/seller/verification');
                }
            } else {
                return redirect('/dashboard');
            }
        }
        
        // 🔒 SECURITY: Record failed login attempt
        \App\Http\Middleware\ThrottleFailedLogins::recordFailedAttempt(
            $credentials['email'], 
            $request->ip()
        );
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    })->middleware(['throttle:5,1', \App\Http\Middleware\ThrottleFailedLogins::class]); // 5 attempts per minute + lockout mechanism
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::post('/register', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 🔒 SECURITY: Enhanced password policy with complexity requirements
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain at least one special character
            ],
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*#?&).',
        ]);
        
        $user = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);
        
        \Illuminate\Support\Facades\Auth::login($user);
        
        // Redirect based on user role (new users are usually regular users)
        // Clear any intended URL to prevent redirecting to wrong dashboard
        $request->session()->forget('url.intended');
        
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isSeller()) {
            // Refresh user to get latest verification status (important for cloud)
            $user->refresh();
            // Check if seller is verified
            if ($user->isVerifiedSeller()) {
                return redirect('/seller/dashboard');
            } else {
                // Seller but not verified yet, redirect to verification page
                return redirect('/seller/verification');
            }
        } else {
            return redirect('/dashboard');
        }
    });
});

// 🔒 SECURITY: Public Product Routes (Viewing Only)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// 🔒 SECURITY: Public Service Routes (Viewing Only)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service}', [ServiceController::class, 'show'])->name('services.show');

// Cart Routes (can be accessed by guests)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
// Xendit Webhook (public, no auth required - uses signature verification)
Route::post('/webhooks/xendit', [\App\Http\Controllers\XenditWebhookController::class, 'handle'])->name('webhooks.xendit');
Route::get('/webhooks/xendit/health', [\App\Http\Controllers\XenditWebhookController::class, 'health'])->name('webhooks.xendit.health');

// Veripay Webhook (public, no auth required - uses signature verification)
Route::post('/webhooks/veripay', [\App\Http\Controllers\VeripayWebhookController::class, 'handle'])->name('webhooks.veripay');
Route::get('/webhooks/veripay/health', [\App\Http\Controllers\VeripayWebhookController::class, 'health'])->name('webhooks.veripay.health');

// Protected Routes (Buyer Actions)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // 🔒 SECURITY: Product Download (Buyer Only - setelah purchase)
    Route::get('/products/{product}/download', [ProductController::class, 'download'])
        ->name('products.download')
        ->middleware(['throttle:5,1']); // 🔒 SECURITY: Reduced from 10 to 5 downloads per minute per user
    
    // 🔒 CRITICAL SECURITY: Signed download route for private file access
    Route::get('/products/{product}/download-signed/{order}', [ProductController::class, 'downloadSigned'])
        ->name('products.download.signed')
        ->middleware(['signed', 'throttle:5,1']);
    
    // Checkout
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('throttle:10,1'); // 10 per minute
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/update-progress', [OrderController::class, 'updateProgress'])->name('orders.updateProgress');
    Route::post('/orders/{order}/set-deadline', [OrderController::class, 'setDeadline'])->name('orders.setDeadline');
    // ✅ PHASE 2: Enhanced rate limiting for file uploads (5 per hour)
    Route::post('/orders/{order}/upload-deliverable', [OrderController::class, 'uploadDeliverable'])->name('orders.uploadDeliverable')->middleware('throttle:5,60');
    Route::get('/orders/{order}/download-deliverable', [OrderController::class, 'downloadDeliverable'])->name('orders.downloadDeliverable');
    Route::delete('/orders/{order}/delete-deliverable', [OrderController::class, 'deleteDeliverable'])->name('orders.deleteDeliverable');
    Route::get('/orders/{order}/download-task', [OrderController::class, 'downloadTask'])->name('orders.downloadTask');
    Route::post('/orders/{order}/request-revision', [OrderController::class, 'requestRevision'])->name('orders.requestRevision');
    Route::post('/orders/{order}/send-message', [OrderController::class, 'sendMessage'])->name('orders.sendMessage');
    Route::post('/orders/{order}/update-priority', [OrderController::class, 'updatePriority'])->name('orders.updatePriority');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    
    // New service order flow routes
    Route::post('/orders/{order}/accept', [OrderController::class, 'acceptOrder'])->name('orders.accept'); // Seller accepts order
    Route::post('/orders/{order}/reject', [OrderController::class, 'rejectOrder'])->name('orders.reject'); // Seller rejects order
    Route::post('/orders/{order}/confirm', [OrderController::class, 'confirmCompletion'])->name('orders.confirm'); // Buyer confirms completion
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel'); // Cancel order with refund rules
    
    // Dispute Routes
    Route::get('/orders/{order}/dispute', [\App\Http\Controllers\DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/orders/{order}/dispute', [\App\Http\Controllers\DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/disputes', [\App\Http\Controllers\DisputeController::class, 'index'])->name('disputes.index');
    
    // 🔒 SECURITY: Payment Proof Upload (Buyer Only)
    // Rate limiting: 10 uploads per 10 minutes (more reasonable for retry scenarios)
    Route::post('/payments/{payment}/upload', [PaymentController::class, 'uploadProof'])->name('payments.upload')->middleware('throttle:10,10');
    
    // Ratings
    Route::get('/orders/{order}/rating/create', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
    Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/top-up', [WalletController::class, 'topUpForm'])->name('wallet.topUp');
    // ✅ PHASE 2: Enhanced rate limiting for wallet top-up (3 per hour)
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('wallet.topUp.store')->middleware('throttle:3,60');
    
    // Quota Purchase (Kuota XL)
    Route::get('/quota', [\App\Http\Controllers\QuotaController::class, 'index'])->name('quota.index');
    Route::post('/quota/purchase', [\App\Http\Controllers\QuotaController::class, 'purchase'])->name('quota.purchase')->middleware('throttle:10,1');
    Route::post('/quota/check-stock', [\App\Http\Controllers\QuotaController::class, 'checkStock'])->name('quota.checkStock');
    Route::get('/quota/history', [\App\Http\Controllers\QuotaController::class, 'history'])->name('quota.history');
    Route::post('/quota/{refId}/cancel', [\App\Http\Controllers\QuotaController::class, 'cancel'])->name('quota.cancel');
    Route::post('/quota/{refId}/refund', [\App\Http\Controllers\QuotaController::class, 'refund'])->name('quota.refund');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    
    // Store Settings (for sellers)
    Route::post('/profile/store/banner', [ProfileController::class, 'updateStoreBanner'])->name('profile.store.banner');
    Route::delete('/profile/store/banner', [ProfileController::class, 'removeStoreBanner'])->name('profile.store.banner.remove');
    Route::post('/profile/store/logo', [ProfileController::class, 'updateStoreLogo'])->name('profile.store.logo');
    Route::delete('/profile/store/logo', [ProfileController::class, 'removeStoreLogo'])->name('profile.store.logo.remove');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // API endpoint for real-time notifications
    Route::get('/api/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('api.notifications.unread');
    
    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Webhook for quota transactions (public endpoint, but should be secured in production)
Route::post('/webhook/quota', [\App\Http\Controllers\QuotaController::class, 'webhook'])->name('quota.webhook');
Route::get('/webhook/quota', [\App\Http\Controllers\QuotaController::class, 'webhook'])->name('quota.webhook.get');

// Test API endpoint (only for local/dev environment)
if (config('app.env') !== 'production') {
    Route::get('/test-api', function () {
        // Read API key from settings
        $settingsService = app(\App\Services\SettingsService::class);
        $apiKey = $settingsService->get('khfy_api_key');
        
        if (!$apiKey) {
            return response()->json([
                'error' => 'API key belum dikonfigurasi. Silakan set di Admin Settings → API Settings.',
            ], 400);
        }
        
        // Include test file
        $_GET['api_key'] = $apiKey;
        $_GET['auto'] = true;
        
        ob_start();
        include base_path('test_api.php');
        $output = ob_get_clean();
        
        return response($output)->header('Content-Type', 'text/html; charset=utf-8');
    })->name('test.api');
}

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Financial Report
    Route::get('/financial-report', [\App\Http\Controllers\Admin\FinancialReportController::class, 'index'])->name('financial-report.index');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'show'])->name('users.show');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/business-hours', [SettingController::class, 'updateBusinessHours'])->name('settings.businessHours');
    Route::post('/settings/contact', [SettingController::class, 'updateContactInfo'])->name('settings.contact');
    Route::post('/settings/bank-account', [SettingController::class, 'updateBankAccountInfo'])->name('settings.bankAccount');
    Route::post('/settings/platform', [SettingController::class, 'updatePlatformSettings'])->name('settings.platform');
    Route::post('/settings/commission', [SettingController::class, 'updateCommissionSettings'])->name('settings.commission');
    Route::post('/settings/limits', [SettingController::class, 'updateLimits'])->name('settings.limits');
    Route::post('/settings/email', [SettingController::class, 'updateEmailSettings'])->name('settings.email');
    Route::post('/settings/seo', [SettingController::class, 'updateSeoSettings'])->name('settings.seo');
            Route::post('/settings/features', [SettingController::class, 'updateFeatureFlags'])->name('settings.features');
            Route::post('/settings/home', [SettingController::class, 'updateHomeSettings'])->name('settings.home');
            Route::post('/settings/banner', [SettingController::class, 'updateBannerSettings'])->name('settings.banner');
    Route::post('/settings/xendit', [SettingController::class, 'updateXenditSettings'])->name('settings.xendit');
    Route::post('/settings/veripay', [SettingController::class, 'updateVeripaySettings'])->name('settings.veripay');
            Route::post('/settings/owner', [SettingController::class, 'updateOwnerSettings'])->name('settings.owner');
            Route::post('/settings/featured', [SettingController::class, 'storeFeaturedItem'])->name('settings.featured');
            Route::delete('/settings/featured/{featuredItem}', [SettingController::class, 'deleteFeaturedItem'])->name('settings.featured.delete');
            Route::post('/settings/api', [SettingController::class, 'updateApiSettings'])->name('settings.api'); // Full route: admin.settings.api
            Route::post('/settings/sync-products', [SettingController::class, 'syncProducts'])->name('settings.syncProducts'); // Full route: admin.settings.syncProducts
    
    // 🔒 CRITICAL SECURITY: Wallet Top-up Management (Admin Money Actions)
    Route::get('/wallet/requests', [WalletController::class, 'adminIndex'])->name('wallet.index');
    Route::post('/wallet/{transaction}/approve', [WalletController::class, 'approve'])->name('wallet.approve');
    Route::post('/wallet/{transaction}/reject', [WalletController::class, 'reject'])->name('wallet.reject');
    
    // 🔒 CRITICAL SECURITY: Payment Management (Admin Money Actions)
    // Semua admin payment actions dipindahkan ke namespace /admin/payments/* untuk konsistensi
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('/payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    
    // Seller Withdrawal Management (Admin)
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/complete', [AdminWithdrawalController::class, 'complete'])->name('withdrawals.complete');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
    
    // Seller Verification Management (Admin)
    Route::get('/verifications', [AdminSellerVerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifications/{verification}', [AdminSellerVerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifications/{verification}/approve', [AdminSellerVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifications/{verification}/reject', [AdminSellerVerificationController::class, 'reject'])->name('verifications.reject');
});

// 🔒 SECURITY: Seller Verification (only for non-verified users/buyers)
// Verified sellers will be redirected to seller dashboard
// This route is NOT in seller dashboard sidebar for verified sellers
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {
    // Verification (buyers only - untuk menjadi seller)
    Route::get('/verification', [SellerVerificationController::class, 'index'])->name('verification.index');
    Route::post('/verification', [SellerVerificationController::class, 'store'])->name('verification.store');
});

// 🔒 SECURITY: Seller Routes (only for verified sellers)
// Semua seller CRUD operations dipindahkan ke namespace /seller/* untuk keamanan
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    
    // Analytics
    Route::get('/analytics', [SellerDashboardController::class, 'analytics'])->name('analytics');
    
    // 🔒 SECURITY: Product CRUD (Seller Only - moved from /products/*)
    Route::get('/products', [SellerDashboardController::class, 'products'])->name('products.index'); // Seller's own products list
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    
    // 🔒 SECURITY: Service CRUD (Seller Only - moved from /services/*)
    Route::get('/services', [SellerDashboardController::class, 'services'])->name('services.index'); // Seller's own services list
    Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name('services.edit');
    Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');
    
    // 🔒 SECURITY: Orders (Seller Only - with seller layout & sidebar)
    Route::get('/orders', [SellerDashboardController::class, 'orders'])->name('orders.index');
    Route::get('/orders/{order}', [SellerDashboardController::class, 'orderShow'])->name('orders.show');
    Route::post('/orders/{order}/send-product', [SellerDashboardController::class, 'sendProduct'])->name('orders.sendProduct');
    
    // 🔒 SECURITY: Wallet (Seller Only - with seller layout & sidebar)
    Route::get('/wallet', [SellerDashboardController::class, 'wallet'])->name('wallet.index');
    Route::get('/wallet/top-up', [WalletController::class, 'topUpForm'])->name('wallet.topUp');
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('wallet.topUp.store')->middleware('throttle:3,60');
    
    // Withdrawal
    Route::get('/withdrawal', [SellerWithdrawalController::class, 'index'])->name('withdrawal.index');
    Route::post('/withdrawal', [SellerWithdrawalController::class, 'store'])->name('withdrawal.store');
});
    Route::get('/wallet/top-up', [WalletController::class, 'topUpForm'])->name('wallet.topUp');
    Route::post('/wallet/top-up', [WalletController::class, 'topUp'])->name('wallet.topUp.store')->middleware('throttle:3,60');
    
    // Withdrawal
    Route::get('/withdrawal', [SellerWithdrawalController::class, 'index'])->name('withdrawal.index');
    Route::post('/withdrawal', [SellerWithdrawalController::class, 'store'])->name('withdrawal.store');
});