@php
    $user = auth()->user();
    $notificationService = app(\App\Services\NotificationService::class);
    $unreadCount = $notificationService->getUnreadCount($user);
@endphp

<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="w-64 bg-[#1A1A1C] border-r border-white/10 flex flex-col">
        <!-- Logo & User Info -->
        <div class="p-4 sm:p-6 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="text-xl sm:text-2xl font-bold text-primary block mb-3">
                Ebrystoree
            </a>
            <div class="flex items-center space-x-3">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                     alt="{{ $user->name }}" 
                     class="w-10 h-10 rounded-full flex-shrink-0 border-2 border-primary/30">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-white/60 truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 p-3 sm:p-4 space-y-1 sm:space-y-2 overflow-y-auto">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="dashboard" class="w-5 h-5 flex-shrink-0" />
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('profile.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('profile.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="user" class="w-5 h-5 flex-shrink-0" />
                <span>Akun Saya</span>
            </a>
            
            <a href="{{ route('orders.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('orders.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="list" class="w-5 h-5 flex-shrink-0" />
                <span>Pesanan Saya</span>
            </a>
            
            <a href="{{ route('notifications.index') }}" 
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm relative {{ request()->routeIs('notifications.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="bell" class="w-5 h-5 flex-shrink-0" />
                <span>Notifikasi</span>
                @if($unreadCount > 0)
                <span class="ml-auto bg-primary text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center font-bold px-1.5">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('wallet.topUp') }}" 
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('wallet.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="currency" class="w-5 h-5 flex-shrink-0" />
                <span>Wallet</span>
            </a>
            
            @if($user->isSeller())
            <div class="pt-2 mt-2 border-t border-white/10">
                <a href="{{ route('seller.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                    <x-icon name="store" class="w-5 h-5 flex-shrink-0" />
                    <span>Seller Dashboard</span>
                </a>
            </div>
            @endif
            
            @if($user->isAdmin())
            <div class="pt-2 mt-2 border-t border-white/10">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                    <x-icon name="shield" class="w-5 h-5 flex-shrink-0" />
                    <span>Admin Dashboard</span>
                </a>
            </div>
            @endif
        </nav>
        
        <!-- Settings & Logout -->
        <div class="p-3 sm:p-4 border-t border-white/10 space-y-2">
            <a href="{{ route('profile.edit') }}" 
               class="flex items-center space-x-3 px-4 py-2 glass glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                <x-icon name="settings" class="w-5 h-5 flex-shrink-0" />
                <span>Pengaturan</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center space-x-3 px-4 py-2 glass glass-hover rounded-lg touch-target text-sm text-red-400 hover:text-red-300 transition-colors">
                    <x-icon name="log-out" class="w-5 h-5 flex-shrink-0" />
                    <span>Log Out</span>
                </button>
            </form>
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
    <div class="fixed left-0 top-0 bottom-0 w-64 sm:w-72 bg-[#1A1A1C] border-r border-white/10 overflow-y-auto pt-safe pb-safe shadow-2xl">
        <!-- Mobile sidebar header -->
        <div class="p-4 sm:p-6 border-b border-white/10">
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('dashboard') }}" class="text-xl sm:text-2xl font-bold text-primary">
                    Ebrystoree
                </a>
                <button @click="sidebarOpen = false" class="touch-target p-2 glass-hover rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="flex items-center space-x-3">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                     alt="{{ $user->name }}" 
                     class="w-10 h-10 rounded-full flex-shrink-0 border-2 border-primary/30">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $user->name }}</p>
                    <p class="text-xs text-white/60 truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        
        <!-- Mobile navigation -->
        <nav class="p-3 sm:p-4 space-y-1 sm:space-y-2">
            <a href="{{ route('dashboard') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('dashboard') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="dashboard" class="w-5 h-5 flex-shrink-0" />
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('profile.index') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('profile.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="user" class="w-5 h-5 flex-shrink-0" />
                <span>Akun Saya</span>
            </a>
            
            <a href="{{ route('orders.index') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm relative {{ request()->routeIs('orders.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="list" class="w-5 h-5 flex-shrink-0" />
                <span>Pesanan Saya</span>
            </a>
            
            <a href="{{ route('notifications.index') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm relative {{ request()->routeIs('notifications.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="bell" class="w-5 h-5 flex-shrink-0" />
                <span>Notifikasi</span>
                @if($unreadCount > 0)
                <span class="ml-auto bg-primary text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center font-bold px-1.5">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('wallet.topUp') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm {{ request()->routeIs('wallet.*') ? 'bg-primary/20 text-primary border-l-4 border-primary' : 'text-white/70 hover:text-white' }}">
                <x-icon name="currency" class="w-5 h-5 flex-shrink-0" />
                <span>Wallet</span>
            </a>
            
            @if($user->isSeller())
            <div class="pt-2 mt-2 border-t border-white/10">
                <a href="{{ route('seller.dashboard') }}" 
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                    <x-icon name="store" class="w-5 h-5 flex-shrink-0" />
                    <span>Seller Dashboard</span>
                </a>
            </div>
            @endif
            
            @if($user->isAdmin())
            <div class="pt-2 mt-2 border-t border-white/10">
                <a href="{{ route('admin.dashboard') }}" 
                   @click="sidebarOpen = false"
                   class="flex items-center space-x-3 px-4 py-3 glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                    <x-icon name="shield" class="w-5 h-5 flex-shrink-0" />
                    <span>Admin Dashboard</span>
                </a>
            </div>
            @endif
        </nav>
        
        <!-- Mobile settings & logout -->
        <div class="p-3 sm:p-4 border-t border-white/10 space-y-2">
            <a href="{{ route('profile.edit') }}" 
               @click="sidebarOpen = false"
               class="flex items-center space-x-3 px-4 py-2 glass glass-hover rounded-lg touch-target text-sm text-white/70 hover:text-white">
                <x-icon name="settings" class="w-5 h-5 flex-shrink-0" />
                <span>Pengaturan</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        @click="sidebarOpen = false"
                        class="w-full flex items-center space-x-3 px-4 py-2 glass glass-hover rounded-lg touch-target text-sm text-red-400 hover:text-red-300 transition-colors">
                    <x-icon name="log-out" class="w-5 h-5 flex-shrink-0" />
                    <span>Log Out</span>
                </button>
            </form>
        </div>
    </div>
</div>

