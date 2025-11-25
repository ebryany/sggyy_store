<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $settingsService = app(\App\Services\SettingsService::class);
        $platformSettings = $settingsService->getPlatformSettings();
        $seoSettings = $settingsService->getSeoSettings();
        $siteName = $platformSettings['site_name'] ?? 'Ebrystoree';
        $faviconUrl = $platformSettings['favicon_url'] ?? ($platformSettings['favicon'] ?? '');
        $metaTitle = $seoSettings['meta_title'] ?? ($siteName . ' - Marketplace Digital Products & Jasa Joki');
        $metaDescription = $seoSettings['meta_description'] ?? '';
        $metaKeywords = $seoSettings['meta_keywords'] ?? '';
    @endphp
    <title>@yield('title', $metaTitle)</title>
    @if($metaDescription)
    <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if($metaKeywords)
    <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    @if($faviconUrl)
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-white min-h-screen">
    @include('components.navbar')
    @include('components.notification-toast')
    @include('components.system-announcement')
    @include('components.maintenance-banner')
    
    <main class="pt-safe pb-safe">
        @include('components.alert', ['type' => 'success'])
        @include('components.alert', ['type' => 'error'])
        @yield('content')
    </main>
    
    <script>
    // Integrate session flash messages with notification toast
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            window.dispatchEvent(new CustomEvent('notification-received', {
                detail: {
                    id: 'flash-' + Date.now(),
                    type: 'success',
                    message: '{{ session('success') }}',
                    is_read: false,
                    created_at: new Date().toISOString(),
                    action_url: null,
                    action_text: null
                }
            }));
        @endif
        
        @if(session('error'))
            window.dispatchEvent(new CustomEvent('notification-received', {
                detail: {
                    id: 'flash-' + Date.now(),
                    type: 'error',
                    message: '{{ session('error') }}',
                    is_read: false,
                    created_at: new Date().toISOString(),
                    action_url: null,
                    action_text: null
                }
            }));
        @endif
    });
    </script>
    
    @include('components.toast')
    
    {{-- Mobile Bottom Navigation (only show on mobile devices, only when authenticated) --}}
    @auth
    <div class="lg:hidden block">
        @include('components.bottom-nav')
    </div>
    @endauth
    
    {{-- Add padding-bottom for mobile to prevent content from being hidden behind bottom nav --}}
    @auth
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
    @endauth
    
    @include('components.footer')
</body>
</html>

