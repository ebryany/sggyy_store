<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
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
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('components.user-sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar (Mobile) -->
            <div class="lg:hidden glass border-b border-white/10 px-4 py-3 flex items-center justify-between">
                <button @click="sidebarOpen = !sidebarOpen" class="touch-target p-2 glass-hover rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-primary">
                    Ebrystoree
                </a>
                <div class="w-10"></div> <!-- Spacer for centering -->
            </div>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto pt-safe pb-safe pb-20 lg:pb-safe">
                @include('components.notification-toast')
                @include('components.system-announcement')
                @include('components.maintenance-banner')
                
                <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 lg:py-8">
                    @yield('content')
                </div>
            </main>
            
            <!-- Mobile Bottom Navigation -->
            @include('components.bottom-nav')
        </div>
    </div>
    
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
    
    {{-- Stack for page-specific scripts --}}
    @stack('scripts')
    
    @include('components.footer')
</body>
</html>

