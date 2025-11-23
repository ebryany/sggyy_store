<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Seller Dashboard - Ebrystoree')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-white">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <aside class="hidden lg:flex lg:flex-shrink-0">
            <div class="w-64 glass border-r border-white/10 flex flex-col">
                <!-- Logo -->
                <div class="p-4 sm:p-6 border-b border-white/10">
                    <a href="{{ route('seller.dashboard') }}" class="text-xl sm:text-2xl font-bold text-primary">
                        Ebrystoree
                    </a>
                    <p class="text-xs sm:text-sm text-white/60 mt-1">Seller Dashboard</p>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 p-3 sm:p-4 space-y-1 sm:space-y-2 overflow-y-auto">
                    <a href="{{ route('seller.dashboard') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.dashboard') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="dashboard" class="w-5 h-5" />
                        <span>Dashboard</span>
                    </a>
                    {{-- Verifikasi hanya untuk seller yang belum verified --}}
                    @if(!auth()->user()->isVerifiedSeller())
                    <a href="{{ route('seller.verification.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.verification.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="shield" class="w-5 h-5" />
                        <span>Verifikasi</span>
                    </a>
                    @endif
                    <a href="{{ route('seller.products.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.products.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="package" class="w-5 h-5" />
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('seller.services.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.services.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="game" class="w-5 h-5" />
                        <span>Jasa</span>
                    </a>
                    <a href="{{ route('seller.orders.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.orders.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="list" class="w-5 h-5" />
                        <span>Pesanan</span>
                    </a>
                    <a href="{{ route('seller.analytics') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.analytics') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="chart" class="w-5 h-5" />
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('seller.wallet.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.wallet.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="dollar" class="w-5 h-5" />
                        <span>Wallet</span>
                    </a>
                    <a href="{{ route('seller.withdrawal.index') }}" 
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2 sm:py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.withdrawal.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="withdraw" class="w-5 h-5" />
                        <span>Tarik Saldo</span>
                    </a>
                </nav>
                
                <!-- User Info -->
                <div class="p-3 sm:p-4 border-t border-white/10">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-xs sm:text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/60">Seller</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       class="block text-center px-3 sm:px-4 py-2 glass glass-hover rounded-lg text-xs sm:text-sm touch-target">
                        User Dashboard
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Mobile Sidebar -->
        <div x-show="sidebarOpen" 
             x-cloak
             @click.away="sidebarOpen = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="lg:hidden fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="sidebarOpen = false"></div>
            <div class="fixed left-0 top-0 bottom-0 w-64 sm:w-72 glass border-r border-white/10 overflow-y-auto pt-safe pb-safe">
                <!-- Mobile sidebar content -->
                <div class="p-4 sm:p-6 border-b border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('seller.dashboard') }}" class="text-xl sm:text-2xl font-bold text-primary">
                            Ebrystoree
                        </a>
                        <button @click="sidebarOpen = false" class="touch-target p-2 glass-hover rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs sm:text-sm text-white/60">Seller Dashboard</p>
                </div>
                <nav class="p-3 sm:p-4 space-y-1 sm:space-y-2">
                    <a href="{{ route('seller.dashboard') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.dashboard') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="dashboard" class="w-5 h-5" />
                        <span>Dashboard</span>
                    </a>
                    {{-- Verifikasi hanya untuk seller yang belum verified --}}
                    @if(!auth()->user()->isVerifiedSeller())
                    <a href="{{ route('seller.verification.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.verification.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="shield" class="w-5 h-5" />
                        <span>Verifikasi</span>
                    </a>
                    @endif
                    <a href="{{ route('seller.products.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.products.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="package" class="w-5 h-5" />
                        <span>Produk</span>
                    </a>
                    <a href="{{ route('seller.services.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.services.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="game" class="w-5 h-5" />
                        <span>Jasa</span>
                    </a>
                    <a href="{{ route('seller.orders.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.orders.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="list" class="w-5 h-5" />
                        <span>Pesanan</span>
                    </a>
                    <a href="{{ route('seller.analytics') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.analytics') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="chart" class="w-5 h-5" />
                        <span>Analytics</span>
                    </a>
                    <a href="{{ route('seller.wallet.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.wallet.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="dollar" class="w-5 h-5" />
                        <span>Wallet</span>
                    </a>
                    <a href="{{ route('seller.withdrawal.index') }}" 
                       @click="sidebarOpen = false"
                       class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-3 glass-hover rounded-lg touch-target text-sm sm:text-base {{ request()->routeIs('seller.withdrawal.*') ? 'bg-primary/20 text-primary' : '' }}">
                        <x-icon name="withdraw" class="w-5 h-5" />
                        <span>Tarik Saldo</span>
                    </a>
                </nav>
                <div class="p-3 sm:p-4 border-t border-white/10">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-xs sm:text-sm truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-white/60">Seller</p>
                        </div>
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       @click="sidebarOpen = false"
                       class="block text-center px-3 sm:px-4 py-2 glass glass-hover rounded-lg text-xs sm:text-sm touch-target">
                        User Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar - Modern Header -->
            <header class="relative glass border-b border-white/10 pt-safe">
                <!-- Animated Background Gradient -->
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-[100px]"></div>
                </div>
                
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
                                <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                                    <x-icon name="dashboard" class="w-5 h-5 text-primary" />
                                </div>
                                <div>
                                    <p class="font-bold text-sm text-white/90">Seller Dashboard</p>
                                    <p class="text-xs text-white/60">{{ now()->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Actions & User Menu -->
                        <div class="flex items-center gap-3 sm:gap-4">
                            <!-- Notifications -->
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                            @endphp
                            <a href="{{ route('notifications.index') }}" 
                               class="relative touch-target p-2.5 glass-hover rounded-xl transition-all hover:scale-110 border border-white/10">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5 border-2 border-dark">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                                @endif
                            </a>
                            
                            <!-- Home Link -->
                            <a href="{{ route('home') }}" 
                               class="hidden sm:flex items-center gap-2 px-4 py-2.5 glass-hover rounded-xl transition-all hover:scale-105 border border-white/10 text-sm sm:text-base font-medium">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span>Home</span>
                            </a>
                            
                            <!-- User Profile & Dropdown -->
                            <div class="relative" x-data="{ userMenuOpen: false }">
                                <button @click="userMenuOpen = !userMenuOpen"
                                        @click.away="userMenuOpen = false"
                                        class="flex items-center gap-2 sm:gap-3 px-3 py-2 sm:px-4 sm:py-2.5 glass-hover rounded-xl transition-all hover:scale-105 border border-white/10 touch-target">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border-2 border-primary/50 flex-shrink-0">
                                    <div class="hidden sm:block text-left">
                                        <p class="font-semibold text-sm text-white/90 truncate max-w-[120px]">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-white/60">Seller</p>
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
                                     class="absolute right-0 mt-2 w-56 glass rounded-xl shadow-xl py-2 z-50 border border-white/10 overflow-hidden">
                                    <a href="{{ route('profile.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="user" class="w-5 h-5" />
                                        <span>Profile</span>
                                    </a>
                                    <a href="{{ route('dashboard') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="dashboard" class="w-5 h-5" />
                                        <span>User Dashboard</span>
                                    </a>
                                    <a href="{{ route('seller.wallet.index') }}" 
                                       class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 transition-colors text-sm">
                                        <x-icon name="dollar" class="w-5 h-5" />
                                        <span>Wallet</span>
                                    </a>
                                    <div class="border-t border-white/10 my-2"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-500/20 text-red-400 transition-colors text-sm font-semibold">
                                            <x-icon name="lock" class="w-5 h-5" />
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-3 sm:p-4 lg:p-6 pb-safe">
                @include('components.alert')
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

