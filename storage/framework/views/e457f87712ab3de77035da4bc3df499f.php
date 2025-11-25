<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php
        $settingsService = app(\App\Services\SettingsService::class);
        $platformSettings = $settingsService->getPlatformSettings();
        $seoSettings = $settingsService->getSeoSettings();
        $siteName = $platformSettings['site_name'] ?? 'Ebrystoree';
        $faviconUrl = $platformSettings['favicon_url'] ?? ($platformSettings['favicon'] ?? '');
        $metaTitle = $seoSettings['meta_title'] ?? ($siteName . ' - Marketplace Digital Products & Jasa Joki');
        $metaDescription = $seoSettings['meta_description'] ?? '';
        $metaKeywords = $seoSettings['meta_keywords'] ?? '';
    ?>
    <title><?php echo $__env->yieldContent('title', $metaTitle); ?></title>
    <?php if($metaDescription): ?>
    <meta name="description" content="<?php echo e($metaDescription); ?>">
    <?php endif; ?>
    <?php if($metaKeywords): ?>
    <meta name="keywords" content="<?php echo e($metaKeywords); ?>">
    <?php endif; ?>
    <?php if($faviconUrl): ?>
    <link rel="icon" type="image/x-icon" href="<?php echo e($faviconUrl); ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-dark text-white min-h-screen">
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.notification-toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.system-announcement', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.maintenance-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <main class="pt-safe pb-safe">
        <?php echo $__env->make('components.alert', ['type' => 'success'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('components.alert', ['type' => 'error'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    
    <script>
    // Integrate session flash messages with notification toast
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(session('success')): ?>
            window.dispatchEvent(new CustomEvent('notification-received', {
                detail: {
                    id: 'flash-' + Date.now(),
                    type: 'success',
                    message: '<?php echo e(session('success')); ?>',
                    is_read: false,
                    created_at: new Date().toISOString(),
                    action_url: null,
                    action_text: null
                }
            }));
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            window.dispatchEvent(new CustomEvent('notification-received', {
                detail: {
                    id: 'flash-' + Date.now(),
                    type: 'error',
                    message: '<?php echo e(session('error')); ?>',
                    is_read: false,
                    created_at: new Date().toISOString(),
                    action_url: null,
                    action_text: null
                }
            }));
        <?php endif; ?>
    });
    </script>
    
    <?php echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    
    <?php if(auth()->guard()->check()): ?>
    <div class="lg:hidden block">
        <?php echo $__env->make('components.bottom-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <?php endif; ?>
    
    
    <?php if(auth()->guard()->check()): ?>
    <script>
        // Add class to body for mobile nav padding
        if (window.innerWidth <= 1023) {
            document.body.classList.add('mobile-nav-active');
        }
        
        // Update on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 1023) {
                document.body.classList.add('mobile-nav-active');
            } else {
                document.body.classList.remove('mobile-nav-active');
            }
        });
    </script>
    <?php endif; ?>
    
    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>

<?php /**PATH C:\Users\febry\Documents\my_jasa\resources\views/layouts/app.blade.php ENDPATH**/ ?>