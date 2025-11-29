@extends('layouts.user')

@section('title', 'Dashboard - Ebrystoree')

@section('content')
<div class="space-y-3 sm:space-y-6">
    <!-- Welcome Banner - Optimized for Mobile -->
    <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 mb-4 sm:mb-6">
        <!-- Mobile: Compact Vertical Layout -->
        <div class="sm:hidden space-y-4">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-14 h-14 rounded-xl border-2 border-primary/30 flex-shrink-0 object-cover">
                    @if(auth()->user()->email_verified_at)
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-dark flex items-center justify-center">
                        <x-icon name="check" class="w-2.5 h-2.5 text-white" />
                    </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg font-bold mb-0.5">
                        Halo, <span class="text-primary">{{ auth()->user()->name }}!</span>
                    </h1>
                    <p class="text-white/60 text-xs mb-1.5 truncate">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->email_verified_at)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-500/20 text-green-400 rounded-full text-[10px] font-medium">
                        <x-icon name="check" class="w-3 h-3" />
                        Email Terverifikasi
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-yellow-500/20 text-yellow-400 rounded-full text-[10px] font-medium">
                        <x-icon name="alert" class="w-3 h-3" />
                        Email belum terverifikasi
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="pt-3 border-t border-white/10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-white/60 text-xs">Profile Completion</p>
                    <span class="text-base font-bold text-white">{{ $profileCompletion['percentage'] }}%</span>
                </div>
                <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-primary transition-all duration-500 rounded-full" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                </div>
                @if($profileCompletion['percentage'] < 100)
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg font-medium transition-all text-xs border border-primary/30">
                    <x-icon name="paint" class="w-3.5 h-3.5" />
                    Lengkapi Profile
                </a>
                @endif
            </div>
        </div>
        
        <!-- Desktop: Original Layout -->
        <div class="hidden sm:flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-16 h-16 rounded-xl border-2 border-primary/30 flex-shrink-0 object-cover">
                    @if(auth()->user()->email_verified_at)
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-dark flex items-center justify-center">
                        <x-icon name="check" class="w-3 h-3 text-white" />
                    </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-1">
                        Halo, <span class="text-primary">{{ auth()->user()->name }}!</span>
                    </h1>
                    <p class="text-white/60 text-sm mb-2 truncate">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->email_verified_at)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">
                        <x-icon name="check" class="w-3.5 h-3.5" />
                        Email Terverifikasi
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">
                        <x-icon name="alert" class="w-3.5 h-3.5" />
                        Email belum terverifikasi
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="w-full sm:w-auto">
                <div class="flex items-center justify-between sm:flex-col sm:items-end gap-3 mb-3">
                    <p class="text-white/60 text-sm">Profile Completion</p>
                    <span class="text-xl font-bold text-white">{{ $profileCompletion['percentage'] }}%</span>
                </div>
                <div class="w-full sm:w-48 h-2 bg-white/10 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-primary transition-all duration-500 rounded-full" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                </div>
                @if($profileCompletion['percentage'] < 100)
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 hover:bg-primary/20 text-primary rounded-lg font-medium transition-all text-sm border border-primary/30">
                    <x-icon name="paint" class="w-4 h-4" />
                    Lengkapi Profile
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions - Clean Cards -->
    <div class="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('wallet.topUp') }}" 
           class="glass p-5 rounded-xl border border-white/5 hover:border-primary/30 transition-all text-center group">
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                <x-icon name="currency" class="w-6 h-6 text-primary" />
            </div>
            <p class="text-sm font-medium text-white/90">Top Up Wallet</p>
        </a>
        
        <a href="{{ route('profile.edit') }}" 
           class="glass p-5 rounded-xl border border-white/5 hover:border-primary/30 transition-all text-center group">
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                <x-icon name="paint" class="w-6 h-6 text-primary" />
            </div>
            <p class="text-sm font-medium text-white/90">Edit Profile</p>
        </a>
        
        <a href="{{ route('products.index') }}" 
           class="glass p-5 rounded-xl border border-white/5 hover:border-primary/30 transition-all text-center group">
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                <x-icon name="shopping-bag" class="w-6 h-6 text-primary" />
            </div>
            <p class="text-sm font-medium text-white/90">Explore</p>
        </a>
        
        <a href="{{ route('notifications.index') }}" 
           class="glass p-5 rounded-xl border border-white/5 hover:border-primary/30 transition-all text-center group relative">
            @if($unreadCount > 0)
            <span class="absolute top-2 right-2 bg-primary text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center font-bold">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                <x-icon name="bell" class="w-6 h-6 text-primary" />
            </div>
            <p class="text-sm font-medium text-white/90">Notifikasi</p>
        </a>
    </div>
    
    <!-- Stats Grid - KPI Cards (1 Column Mobile, 4 Columns Desktop) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <!-- Total Pesanan -->
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="document" class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                </div>
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1.5 sm:mb-2">Total Pesanan</p>
            <p class="text-2xl sm:text-3xl font-bold text-white mb-0.5 sm:mb-1">
                {{ number_format($totalOrders, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-xs text-white/50">Semua pesanan Anda</p>
        </div>
        
        <!-- Saldo Wallet -->
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <x-icon name="currency" class="w-5 h-5 sm:w-6 sm:h-6 text-green-400" />
                </div>
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1.5 sm:mb-2">Saldo Wallet</p>
            <p class="text-xl sm:text-2xl font-bold text-green-400 mb-0.5 sm:mb-1 break-words">
                Rp {{ number_format($walletBalance / 1000, 0) }}k
            </p>
            @if($lastTopUp)
            <p class="text-[10px] sm:text-xs text-white/50">Top-up: {{ $lastTopUp->created_at->diffForHumans() }}</p>
            @else
            <p class="text-[10px] sm:text-xs text-white/50">Belum pernah top-up</p>
            @endif
        </div>
        
        <!-- Produk Aktif -->
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="package" class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                </div>
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1.5 sm:mb-2">Produk Aktif</p>
            <p class="text-2xl sm:text-3xl font-bold text-white mb-0.5 sm:mb-1">
                {{ number_format($activeProducts, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-xs text-white/50">Produk yang aktif</p>
        </div>
        
        <!-- Jasa Aktif -->
        <div class="glass p-4 sm:p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="target" class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                </div>
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1.5 sm:mb-2">Jasa Aktif</p>
            <p class="text-2xl sm:text-3xl font-bold text-white mb-0.5 sm:mb-1">
                {{ number_format($activeServices, 0, ',', '.') }}
            </p>
            <p class="text-[10px] sm:text-xs text-white/50">Jasa yang aktif</p>
        </div>
    </div>
    
    <!-- Order Status Quick View -->
    <div x-data="{ activeTab: 'pending' }" class="mb-6 sm:mb-8">
        <!-- Mobile: Tab Interface -->
        <div class="sm:hidden">
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2 scrollbar-hide">
                <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'bg-yellow-500/20 border-yellow-500/40 text-yellow-400' : 'glass border-white/10 text-white/60'"
                        class="flex-shrink-0 px-4 py-3 rounded-xl border transition-all font-semibold text-sm flex items-center gap-2">
                    <x-icon name="clock" class="w-4 h-4" />
                    <span>Pending</span>
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold" :class="activeTab === 'pending' ? 'bg-yellow-500/30 text-yellow-300' : 'bg-white/10 text-white/60'">{{ $orderStatusCounts['pending'] }}</span>
                </button>
                <button @click="activeTab = 'processing'" 
                        :class="activeTab === 'processing' ? 'bg-blue-500/20 border-blue-500/40 text-blue-400' : 'glass border-white/10 text-white/60'"
                        class="flex-shrink-0 px-4 py-3 rounded-xl border transition-all font-semibold text-sm flex items-center gap-2">
                    <x-icon name="refresh" class="w-4 h-4" />
                    <span>Processing</span>
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold" :class="activeTab === 'processing' ? 'bg-blue-500/30 text-blue-300' : 'bg-white/10 text-white/60'">{{ $orderStatusCounts['processing'] }}</span>
                </button>
                <button @click="activeTab = 'completed'" 
                        :class="activeTab === 'completed' ? 'bg-green-500/20 border-green-500/40 text-green-400' : 'glass border-white/10 text-white/60'"
                        class="flex-shrink-0 px-4 py-3 rounded-xl border transition-all font-semibold text-sm flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Completed</span>
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold" :class="activeTab === 'completed' ? 'bg-green-500/30 text-green-300' : 'bg-white/10 text-white/60'">{{ $orderStatusCounts['completed'] }}</span>
                </button>
                <button @click="activeTab = 'cancelled'" 
                        :class="activeTab === 'cancelled' ? 'bg-red-500/20 border-red-500/40 text-red-400' : 'glass border-white/10 text-white/60'"
                        class="flex-shrink-0 px-4 py-3 rounded-xl border transition-all font-semibold text-sm flex items-center gap-2">
                    <x-icon name="x" class="w-4 h-4" />
                    <span>Cancelled</span>
                    <span class="px-2 py-0.5 rounded-lg text-xs font-bold" :class="activeTab === 'cancelled' ? 'bg-red-500/30 text-red-300' : 'bg-white/10 text-white/60'">{{ $orderStatusCounts['cancelled'] }}</span>
                </button>
            </div>
            
            <!-- Tab Content -->
            <div class="glass p-6 rounded-xl border border-white/5">
                <div x-show="activeTab === 'pending'" x-cloak>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                            <x-icon name="clock" class="w-6 h-6 text-yellow-400" />
                        </div>
                        <div>
                            <p class="text-white/60 text-sm mb-1">Pending</p>
                            <p class="text-3xl font-bold text-white">{{ $orderStatusCounts['pending'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=pending" class="block w-full px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition-colors text-center font-medium text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'processing'" x-cloak>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <x-icon name="refresh" class="w-6 h-6 text-blue-400" />
                        </div>
                        <div>
                            <p class="text-white/60 text-sm mb-1">Processing</p>
                            <p class="text-3xl font-bold text-white">{{ $orderStatusCounts['processing'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=processing" class="block w-full px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition-colors text-center font-medium text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'completed'" x-cloak>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                            <x-icon name="check" class="w-6 h-6 text-green-400" />
                        </div>
                        <div>
                            <p class="text-white/60 text-sm mb-1">Completed</p>
                            <p class="text-3xl font-bold text-white">{{ $orderStatusCounts['completed'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=completed" class="block w-full px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded-lg transition-colors text-center font-medium text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'cancelled'" x-cloak>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-red-500/10 flex items-center justify-center">
                            <x-icon name="x" class="w-6 h-6 text-red-400" />
                        </div>
                        <div>
                            <p class="text-white/60 text-sm mb-1">Cancelled</p>
                            <p class="text-3xl font-bold text-white">{{ $orderStatusCounts['cancelled'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=cancelled" class="block w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-colors text-center font-medium text-sm">
                        Lihat Semua →
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Desktop: Grid View -->
        <div class="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass p-4 rounded-xl border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                        <x-icon name="clock" class="w-5 h-5 text-yellow-400" />
                    </div>
                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs font-medium">{{ $orderStatusCounts['pending'] }}</span>
                </div>
                <p class="text-white/60 text-xs mb-1">Pending</p>
                <p class="text-xl font-bold text-white">{{ $orderStatusCounts['pending'] }}</p>
            </div>
            
            <div class="glass p-4 rounded-xl border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <x-icon name="refresh" class="w-5 h-5 text-blue-400" />
                    </div>
                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-medium">{{ $orderStatusCounts['processing'] }}</span>
                </div>
                <p class="text-white/60 text-xs mb-1">Processing</p>
                <p class="text-xl font-bold text-white">{{ $orderStatusCounts['processing'] }}</p>
            </div>
            
            <div class="glass p-4 rounded-xl border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                        <x-icon name="check" class="w-5 h-5 text-green-400" />
                    </div>
                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs font-medium">{{ $orderStatusCounts['completed'] }}</span>
                </div>
                <p class="text-white/60 text-xs mb-1">Completed</p>
                <p class="text-xl font-bold text-white">{{ $orderStatusCounts['completed'] }}</p>
            </div>
            
            <div class="glass p-4 rounded-xl border border-white/5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center">
                        <x-icon name="x" class="w-5 h-5 text-red-400" />
                    </div>
                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded text-xs font-medium">{{ $orderStatusCounts['cancelled'] }}</span>
                </div>
                <p class="text-white/60 text-xs mb-1">Cancelled</p>
                <p class="text-xl font-bold text-white">{{ $orderStatusCounts['cancelled'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Tugas Dalam Progress Widget -->
    @if($inProgressOrders->count() > 0)
    <div class="glass p-6 rounded-xl border border-white/5 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <x-icon name="file-text" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Tugas Dalam Progress</h2>
                        <p class="text-xs text-white/50">{{ $inProgressOrders->count() }} tugas aktif</p>
                    </div>
                </div>
            <a href="{{ route('orders.index') }}?status=processing" 
               class="px-4 py-2 glass hover:bg-white/10 rounded-lg font-medium transition-all text-sm border border-white/10">
                Lihat Semua →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($inProgressOrders as $order)
            <a href="{{ route('orders.show', $order) }}" 
               class="block glass glass-hover p-5 rounded-xl transition-all border border-white/5 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-base truncate text-white group-hover:text-primary transition-colors flex items-center gap-2">
                            @if($order->type === 'product')
                                <x-icon name="package" class="w-4 h-4 text-primary" />
                                {{ $order->product->title ?? 'N/A' }}
                            @else
                                <x-icon name="target" class="w-4 h-4 text-primary" />
                                {{ $order->service->title ?? 'N/A' }}
                            @endif
                        </h3>
                        <p class="text-xs text-white/50 mt-1">{{ $order->order_number }}</p>
                    </div>
                    <span class="px-2.5 py-1 bg-primary/20 text-primary rounded-lg text-xs font-medium flex-shrink-0 ml-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-white/60 mb-2">
                        <span>Progress</span>
                        <span class="font-semibold text-white">{{ $order->progress ?? 0 }}%</span>
                    </div>
                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-primary transition-all duration-500 rounded-full" style="width: {{ $order->progress ?? 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Deadline & Price -->
                <div class="flex justify-between items-center pt-4 border-t border-white/10">
                    @if($order->deadline_at)
                    <div class="flex items-center gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-white/50" />
                        <span class="text-xs font-medium {{ $order->deadline_at->isPast() ? 'text-red-400' : ($order->deadline_at->diffInHours() < 24 ? 'text-yellow-400' : 'text-white/60') }}">
                            {{ $order->deadline_at->diffForHumans() }}
                        </span>
                    </div>
                    @else
                    <span class="text-xs text-white/40">No deadline</span>
                    @endif
                    <span class="text-base font-bold text-white">Rp {{ number_format($order->total / 1000, 0) }}k</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- File Download Center -->
    @if($completedOrders->count() > 0)
    <div class="glass p-6 rounded-xl border border-white/5 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <x-icon name="download" class="w-5 h-5 text-green-400" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">File Download Center</h2>
                    <p class="text-xs text-white/50">{{ $completedOrders->count() }} file tersedia</p>
                </div>
            </div>
            <a href="{{ route('orders.index') }}?status=completed" 
               class="px-4 py-2 glass hover:bg-white/10 rounded-lg font-medium transition-all text-sm border border-white/10">
                Lihat Semua →
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($completedOrders->take(6) as $order)
            <div class="glass glass-hover p-5 rounded-xl transition-all border border-white/5">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if($order->type === 'product')
                            <x-icon name="package" class="w-6 h-6 text-green-400" />
                        @else
                            <x-icon name="file-text" class="w-6 h-6 text-green-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate text-white mb-1">
                            @if($order->type === 'product')
                                {{ $order->product->title ?? 'N/A' }}
                            @else
                                {{ $order->service->title ?? 'N/A' }}
                            @endif
                        </p>
                        <p class="text-xs text-white/50">{{ $order->completed_at?->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($order->type === 'product' && $order->product && $order->product->file_path)
                    <a href="{{ route('products.download', $order->product) }}" 
                       class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm font-medium text-center flex items-center justify-center gap-2">
                        <x-icon name="download" class="w-4 h-4" />
                        Download
                    </a>
                    @elseif($order->type === 'service' && $order->deliverable_path)
                    <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                       class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm font-medium text-center flex items-center justify-center gap-2">
                        <x-icon name="download" class="w-4 h-4" />
                        Download
                    </a>
                    @endif
                    <a href="{{ route('orders.show', $order) }}" 
                       class="flex-1 px-4 py-2.5 glass glass-hover rounded-lg transition-all text-sm font-medium text-center border border-white/10 flex items-center justify-center gap-2">
                        <x-icon name="eye" class="w-4 h-4" />
                        Detail
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Orders -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <x-icon name="list" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Pesanan Terbaru</h2>
                        <p class="text-xs text-white/50">5 pesanan terakhir</p>
                    </div>
                </div>
                <a href="{{ route('orders.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-lg font-medium transition-all text-sm border border-white/10">
                    Semua →
                </a>
            </div>
            
            @if($recentOrders->count() > 0)
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" 
                   class="block glass glass-hover p-4 rounded-lg transition-all border border-white/5 group">
                    <div class="flex items-start sm:items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-semibold text-sm truncate text-white group-hover:text-primary transition-colors">{{ $order->order_number }}</p>
                                @include('components.order-status-badge', ['status' => $order->status])
                            </div>
                            <p class="text-xs text-white/50 truncate flex items-center gap-1.5">
                                @if($order->type === 'product')
                                    <x-icon name="package" class="w-3.5 h-3.5 text-white/50" />
                                    {{ $order->product->title ?? 'N/A' }}
                                @else
                                    <x-icon name="target" class="w-3.5 h-3.5 text-white/50" />
                                    {{ $order->service->title ?? 'N/A' }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-white text-sm">Rp {{ number_format($order->total / 1000, 0) }}k</p>
                            <p class="text-xs text-white/50">{{ $order->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 px-4">
                <div class="flex justify-center mb-3">
                    <x-icon name="shopping-bag" class="w-16 h-16 text-white/30" />
                </div>
                <p class="text-white/60 mb-4 text-sm sm:text-base">Belum ada pesanan</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-xl transition-colors font-semibold">
                    Mulai Belanja
                </a>
            </div>
            @endif
        </div>
        
        <!-- Recent Notifications -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center relative">
                        <x-icon name="bell" class="w-5 h-5 text-primary" />
                        @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-bold">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Notifikasi Terbaru</h2>
                        <p class="text-xs text-white/50">{{ $unreadCount > 0 ? $unreadCount . ' belum dibaca' : 'Tidak ada notifikasi baru' }}</p>
                    </div>
                </div>
                <a href="{{ route('notifications.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-lg font-medium transition-all text-sm border border-white/10">
                    Semua →
                </a>
            </div>
            
            @if($recentNotifications->count() > 0)
            <div class="space-y-3">
                @foreach($recentNotifications as $notification)
                <a href="{{ route('notifications.index') }}" 
                   class="block glass glass-hover p-4 rounded-lg transition-all border {{ !$notification->is_read ? 'border-primary/30 bg-primary/5' : 'border-white/5' }} group">
                    <p class="font-medium text-sm {{ !$notification->is_read ? 'text-primary' : 'text-white/90' }} mb-1">
                        {{ $notification->message }}
                    </p>
                    <p class="text-xs text-white/50">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 px-4">
                <div class="flex justify-center mb-3">
                    <x-icon name="bell" class="w-16 h-16 text-white/30" />
                </div>
                <p class="text-white/60 text-sm sm:text-base">Tidak ada notifikasi</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Admin Quick Link -->
    @if(auth()->user()->isAdmin())
    <div class="glass p-6 rounded-xl border border-primary/30 bg-primary/5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="dashboard" class="w-6 h-6 text-primary" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-primary mb-1">Admin Dashboard</h2>
                    <p class="text-white/60 text-sm">Akses dashboard admin untuk melihat statistik lengkap platform</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all font-medium text-center">
                Buka Admin Dashboard →
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
