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
            <!-- Top Bar - Header (Desktop & Mobile) -->
            <header class="relative glass border-b border-white/10 pt-safe">
                <div class="relative z-10 px-4 sm:px-6 lg:px-8 py-4 sm:py-5">
                    <div class="flex items-center justify-between gap-4">
                        <!-- Left Side - Menu Button (Mobile) & Logo (Desktop) -->
                        <div class="flex items-center gap-4">
                            <button @click="sidebarOpen = !sidebarOpen" 
                                    class="lg:hidden touch-target p-2.5 glass-hover rounded-xl transition-all hover:scale-110 border border-white/10"
                                    aria-label="Menu">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            
                            <!-- Desktop Logo -->
                            <div class="hidden lg:flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                                    <x-icon name="dashboard" class="w-5 h-5 text-primary" />
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white/90">User Dashboard</p>
                                    <p class="text-xs text-white/60">{{ now()->format('d M Y') }}</p>
                                </div>
                            </div>
                            
                            <!-- Mobile Logo -->
                            <a href="{{ route('dashboard') }}" class="lg:hidden text-lg font-bold text-primary">
                                Ebrystoree
                            </a>
                        </div>
                        
                        <!-- Right Side - Actions & User Menu -->
                        <div class="flex items-center gap-3 sm:gap-4">
                            <!-- Notifications -->
                            @php
                                $notificationService = app(\App\Services\NotificationService::class);
                                $unreadCount = $notificationService->getUnreadCount(auth()->user());
                            @endphp
                            <a href="{{ route('notifications.index') }}" 
                               class="relative touch-target p-2.5 glass-hover rounded-xl transition-all hover:scale-110 border border-white/10">
                                <x-icon name="bell" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                                @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-primary text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5 border-2 border-dark">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                                @endif
                            </a>
                            
                            <!-- Home Link (Desktop) -->
                            <a href="{{ route('home') }}" 
                               class="hidden sm:flex items-center gap-2 px-4 py-2.5 glass-hover rounded-xl transition-all hover:scale-105 border border-white/10 text-sm sm:text-base font-medium">
                                <x-icon name="home" class="w-4 h-4 sm:w-5 sm:h-5" />
                                <span>Home</span>
                            </a>
                            
                            <!-- User Profile & Dropdown -->
                            <div class="relative" x-data="{ userMenuOpen: false }">
                                <button @click="userMenuOpen = !userMenuOpen"
                                        @click.away="userMenuOpen = false"
                                        class="flex items-center gap-2 sm:gap-3 px-3 py-2 sm:px-4 sm:py-2.5 glass-hover rounded-xl transition-all hover:scale-105 border border-white/10 touch-target">
                                    @php
                                        $user = auth()->user();
                                        $avatarUrl = null;
                                        if ($user->avatar) {
                                            if (str_starts_with($user->avatar, 'http://') || str_starts_with($user->avatar, 'https://')) {
                                                $avatarUrl = $user->avatar;
                                            } else {
                                                $avatarUrl = asset('storage/' . ltrim($user->avatar, '/'));
                                            }
                                        }
                                        if (!$avatarUrl) {
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff&size=128';
                                        }
                                    @endphp
                                    <img src="{{ $avatarUrl }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border-2 border-primary/50 flex-shrink-0 object-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff&size=128'">
                                    <div class="hidden sm:block text-left">
                                        <p class="font-semibold text-sm text-white/90 truncate max-w-[120px]">{{ $user->name }}</p>
                                        <p class="text-xs text-white/60">User</p>
                                    </div>
                                    <svg class="w-4 h-4 text-white/60 transition-transform" 
                                         :class="{'rotate-180': userMenuOpen}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div x-show="userMenuOpen"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95 translate-y-[-10px]"
                                     x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 transform scale-95 translate-y-[-10px]"
                                     class="absolute right-0 mt-2 w-56 bg-[#1A1A1C] rounded-xl shadow-xl py-2 z-50 border border-white/10 overflow-hidden"
                                     style="background-color: rgba(26, 26, 28, 0.98);">
                                    <a href="{{ route('profile.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="user" class="w-5 h-5" />
                                        <span>Profile</span>
                                    </a>
                                    <a href="{{ route('dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="dashboard" class="w-5 h-5" />
                                        <span>Dashboard</span>
                                    </a>
                                    <a href="{{ route('wallet.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="currency" class="w-5 h-5" />
                                        <span>Wallet</span>
                                    </a>
                                    @if(auth()->user()->isSeller())
                                    <a href="{{ route('seller.dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="store" class="w-5 h-5" />
                                        <span>Seller Dashboard</span>
                                    </a>
                                    @endif
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="shield" class="w-5 h-5" />
                                        <span>Admin Dashboard</span>
                                    </a>
                                    @endif
                                    <div class="border-t border-white/10 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-500/20 text-red-400 transition-colors text-sm font-semibold">
                                            <x-icon name="log-out" class="w-5 h-5" />
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto pt-safe pb-safe pb-20 lg:pb-safe">
                @include('components.alert', ['type' => 'success'])
                @include('components.alert', ['type' => 'error'])
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
    // Flash messages are now only shown via alert components, not notification toast
    // This prevents duplicate notifications (alert box + toast notification)
    </script>
    
    @include('components.toast')
    
    {{-- Stack for page-specific scripts --}}
    @stack('scripts')
    
    @include('components.footer')
</body>
</html>

