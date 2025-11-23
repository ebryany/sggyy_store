
<?php if(auth()->guard()->check()): ?>
<?php
    $user = auth()->user();
    try {
        $currentRoute = request()->route() ? request()->route()->getName() : 'home';
    } catch (\Exception $e) {
        $currentRoute = 'home';
    }
    
    // Determine active route based on current location
    $isDashboard = in_array($currentRoute, ['dashboard', 'orders.index', 'wallet.index', 'notifications.index']);
    $isHome = in_array($currentRoute, ['home']);
    $isCart = $currentRoute === 'cart.index';
    $isExplore = in_array($currentRoute, ['products.index', 'services.index', 'products.show', 'services.show']);
    
    // Determine active tab
    $activeTab = 'home';
    if ($isDashboard) $activeTab = 'dashboard';
    elseif ($isHome) $activeTab = 'home';
    elseif ($isCart) $activeTab = 'cart';
    elseif ($isExplore) $activeTab = 'explore';
    
    // Get cart count safely
    try {
        $cartCount = method_exists(\App\Http\Controllers\CartController::class, 'getCartCount') 
            ? \App\Http\Controllers\CartController::getCartCount() 
            : 0;
    } catch (\Exception $e) {
        $cartCount = 0;
    }
    
?>
<nav class="mobile-bottom-nav fixed bottom-0 left-0 right-0 z-[9999] pb-safe pt-3"
     style="background: transparent;"
     x-data="{ activeTab: '<?php echo e($activeTab); ?>' }"
     id="mobile-bottom-nav">
    
    <div class="absolute inset-x-0 bottom-0 h-full bg-gradient-to-t from-dark/95 via-dark/80 to-transparent pointer-events-none"></div>
    
    <div class="container mx-auto px-3 relative z-10">
        <div class="rounded-2xl border border-white/10 backdrop-blur-[30px] shadow-2xl"
             style="background: rgba(255, 255, 255, 0.05);">
            <div class="flex items-center justify-between px-3 py-3">
            
            <a href="<?php echo e(route('home')); ?>" 
               class="flex flex-col items-center justify-center flex-1 transition-all duration-300 touch-target"
               :class="activeTab === 'home' ? 'text-primary' : 'text-white/60 hover:text-white'"
               @click="activeTab = 'home'">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-semibold">Home</span>
            </a>
            
            
            <a href="<?php echo e(route('products.index')); ?>" 
               class="flex flex-col items-center justify-center flex-1 transition-all duration-300 touch-target"
               :class="activeTab === 'explore' ? 'text-primary' : 'text-white/60 hover:text-white'"
               @click="activeTab = 'explore'">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-[10px] font-semibold">Explore</span>
            </a>
            
            
            <a href="<?php echo e(route('cart.index')); ?>" 
               class="flex flex-col items-center justify-center flex-1 transition-all duration-300 touch-target relative"
               :class="activeTab === 'cart' ? 'text-primary' : 'text-white/60 hover:text-white'"
               @click="activeTab = 'cart'">
                <?php if($cartCount > 0): ?>
                <span class="absolute top-0 right-[20%] bg-primary text-white text-[9px] rounded-full min-w-[16px] h-[16px] flex items-center justify-center font-bold border-2 border-dark px-1">
                    <?php echo e($cartCount > 99 ? '99+' : $cartCount); ?>

                </span>
                <?php endif; ?>
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="text-[10px] font-semibold">Cart</span>
            </a>
            
            
            <a href="<?php echo e(route('dashboard')); ?>" 
               class="flex flex-col items-center justify-center flex-1 transition-all duration-300 touch-target"
               :class="activeTab === 'dashboard' ? 'text-primary' : 'text-white/60 hover:text-white'"
               @click="activeTab = 'dashboard'">
                <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="text-[10px] font-semibold">Dashboard</span>
            </a>
            
        </div>
    </div>
</nav>
<?php endif; ?>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/components/bottom-nav.blade.php ENDPATH**/ ?>