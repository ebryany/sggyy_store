@extends('layouts.app')

@section('title', 'Dashboard - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Welcome Banner - Modern & Attractive -->
    <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 lg:p-10 bg-gradient-to-br from-primary/20 via-primary/10 to-blue-500/10 border border-primary/30 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-6">
            <div class="flex items-center space-x-4 sm:space-x-5">
                <div class="relative">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-xl border-2 border-primary/50 flex-shrink-0 shadow-xl object-cover">
                    @if(auth()->user()->email_verified_at)
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-dark flex items-center justify-center">
                        <x-icon name="check" class="w-3 h-3 text-white" />
                    </span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">
                        Halo, <span class="text-primary">{{ auth()->user()->name }}!</span>
                    </h1>
                    <p class="text-white/70 text-sm sm:text-base mb-2 truncate">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->email_verified_at)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-semibold border border-green-500/30">
                        <x-icon name="check" class="w-3.5 h-3.5" />
                        Email Terverifikasi
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold border border-yellow-500/30">
                        <x-icon name="alert" class="w-3.5 h-3.5" />
                        Email belum terverifikasi
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="w-full sm:w-auto">
                <div class="flex items-center justify-between sm:flex-col sm:items-end gap-3 mb-3">
                    <p class="text-white/70 text-sm font-medium">Profile Completion</p>
                    <span class="text-lg sm:text-xl font-bold text-primary">{{ $profileCompletion['percentage'] }}%</span>
                </div>
                <div class="w-full sm:w-48 h-3 bg-white/10 rounded-full overflow-hidden mb-2">
                    <div class="h-full bg-gradient-to-r from-primary to-blue-500 transition-all duration-500 rounded-full" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                </div>
                @if($profileCompletion['percentage'] < 100)
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 hover:bg-primary/30 text-primary rounded-xl font-semibold transition-all hover:scale-105 text-sm border border-primary/30">
                    <x-icon name="paint" class="w-4 h-4" />
                    Lengkapi Profile
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions - Modern Cards (Hidden on mobile, available in profile dropdown) -->
    <div class="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <a href="{{ route('wallet.topUp') }}" 
           class="group relative overflow-hidden rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-yellow-500/10 via-yellow-500/5 to-transparent border border-yellow-500/20 hover:border-yellow-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-yellow-500/20 text-center touch-target">
            <div class="relative z-10">
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 rounded-2xl bg-yellow-500/20 backdrop-blur-lg flex items-center justify-center border border-yellow-500/30 group-hover:scale-110 transition-transform">
                    <x-icon name="currency" class="w-8 h-8 sm:w-10 sm:h-10 text-yellow-400" />
                </div>
                <p class="text-sm sm:text-base font-bold text-white/90 group-hover:text-yellow-400 transition-colors">Top Up Wallet</p>
            </div>
        </a>
        
        <a href="{{ route('profile.edit') }}" 
           class="group relative overflow-hidden rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-orange-500/10 via-orange-500/5 to-transparent border border-orange-500/20 hover:border-orange-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-orange-500/20 text-center touch-target">
            <div class="relative z-10">
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 rounded-2xl bg-orange-500/20 backdrop-blur-lg flex items-center justify-center border border-orange-500/30 group-hover:scale-110 transition-transform">
                    <x-icon name="paint" class="w-8 h-8 sm:w-10 sm:h-10 text-orange-400" />
                </div>
                <p class="text-sm sm:text-base font-bold text-white/90 group-hover:text-orange-400 transition-colors">Edit Profile</p>
            </div>
        </a>
        
        <a href="{{ route('products.index') }}" 
           class="group relative overflow-hidden rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20 text-center touch-target">
            <div class="relative z-10">
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 rounded-2xl bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30 group-hover:scale-110 transition-transform">
                    <x-icon name="shopping-bag" class="w-8 h-8 sm:w-10 sm:h-10 text-blue-400" />
                </div>
                <p class="text-sm sm:text-base font-bold text-white/90 group-hover:text-blue-400 transition-colors">Explore</p>
            </div>
        </a>
        
        <a href="{{ route('notifications.index') }}" 
           class="group relative overflow-hidden rounded-2xl p-5 sm:p-6 bg-gradient-to-br from-purple-500/10 via-purple-500/5 to-transparent border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-purple-500/20 text-center touch-target">
            <div class="relative z-10">
                @if($unreadCount > 0)
                <span class="absolute top-2 right-2 bg-primary text-white text-xs rounded-full min-w-[20px] h-5 flex items-center justify-center font-bold border-2 border-dark z-20">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
                <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto mb-3 rounded-2xl bg-purple-500/20 backdrop-blur-lg flex items-center justify-center border border-purple-500/30 group-hover:scale-110 transition-transform">
                    <x-icon name="bell" class="w-8 h-8 sm:w-10 sm:h-10 text-purple-400" />
                </div>
                <p class="text-sm sm:text-base font-bold text-white/90 group-hover:text-purple-400 transition-colors">Notifikasi</p>
            </div>
        </a>
    </div>
    
    <!-- Stats Grid - Modern Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Pesanan -->
        <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 bg-gradient-to-br from-blue-500/10 via-blue-500/5 to-transparent border border-blue-500/20 hover:border-blue-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-blue-500/20">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30">
                        <x-icon name="document" class="w-6 h-6 text-blue-400" />
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-blue-400 bg-blue-500/20 px-2 py-1 rounded-lg">Pesanan</span>
                </div>
                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-400 mb-1">
                    {{ number_format($totalOrders, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-white/60">Total pesanan Anda</p>
            </div>
        </div>
        
        <!-- Saldo Wallet -->
        <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 bg-gradient-to-br from-green-500/10 via-green-500/5 to-transparent border border-green-500/20 hover:border-green-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-green-500/20">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-green-500/20 backdrop-blur-lg flex items-center justify-center border border-green-500/30">
                        <x-icon name="currency" class="w-6 h-6 text-green-400" />
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-green-400 bg-green-500/20 px-2 py-1 rounded-lg">Wallet</span>
                </div>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-400 mb-1 break-words">
                    Rp {{ number_format($walletBalance, 0, ',', '.') }}
                </p>
                @if($lastTopUp)
                <p class="text-xs text-white/60">Top-up: {{ $lastTopUp->created_at->diffForHumans() }}</p>
                @else
                <p class="text-xs text-white/60">Belum pernah top-up</p>
                @endif
            </div>
        </div>
        
        <!-- Produk Aktif -->
        <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 bg-gradient-to-br from-purple-500/10 via-purple-500/5 to-transparent border border-purple-500/20 hover:border-purple-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-purple-500/20">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 backdrop-blur-lg flex items-center justify-center border border-purple-500/30">
                        <x-icon name="package" class="w-6 h-6 text-purple-400" />
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-purple-400 bg-purple-500/20 px-2 py-1 rounded-lg">Produk</span>
                </div>
                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-purple-400 mb-1">
                    {{ number_format($activeProducts, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-white/60">Produk aktif</p>
            </div>
        </div>
        
        <!-- Jasa Aktif -->
        <div class="group relative overflow-hidden rounded-2xl p-6 sm:p-8 bg-gradient-to-br from-orange-500/10 via-orange-500/5 to-transparent border border-orange-500/20 hover:border-orange-500/40 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-orange-500/20">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/20 backdrop-blur-lg flex items-center justify-center border border-orange-500/30">
                        <x-icon name="target" class="w-6 h-6 text-orange-400" />
                    </div>
                    <span class="text-xs sm:text-sm font-semibold text-orange-400 bg-orange-500/20 px-2 py-1 rounded-lg">Jasa</span>
                </div>
                <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-orange-400 mb-1">
                    {{ number_format($activeServices, 0, ',', '.') }}
                </p>
                <p class="text-xs sm:text-sm text-white/60">Jasa aktif</p>
            </div>
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
            <div class="glass p-6 rounded-xl border border-white/10">
                <div x-show="activeTab === 'pending'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center border border-yellow-500/30">
                                <x-icon name="clock" class="w-6 h-6 text-yellow-400" />
                            </div>
                            <div>
                                <p class="text-white/60 text-sm mb-1">Pending</p>
                                <p class="text-3xl font-bold text-yellow-400">{{ $orderStatusCounts['pending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=pending" class="block w-full px-4 py-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition-colors text-center font-semibold text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'processing'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                                <x-icon name="refresh" class="w-6 h-6 text-blue-400" />
                            </div>
                            <div>
                                <p class="text-white/60 text-sm mb-1">Processing</p>
                                <p class="text-3xl font-bold text-blue-400">{{ $orderStatusCounts['processing'] }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=processing" class="block w-full px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition-colors text-center font-semibold text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'completed'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center border border-green-500/30">
                                <x-icon name="check" class="w-6 h-6 text-green-400" />
                            </div>
                            <div>
                                <p class="text-white/60 text-sm mb-1">Completed</p>
                                <p class="text-3xl font-bold text-green-400">{{ $orderStatusCounts['completed'] }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=completed" class="block w-full px-4 py-2 bg-green-500/20 hover:bg-green-500/30 text-green-400 rounded-lg transition-colors text-center font-semibold text-sm">
                        Lihat Semua →
                    </a>
                </div>
                
                <div x-show="activeTab === 'cancelled'" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center border border-red-500/30">
                                <x-icon name="x" class="w-6 h-6 text-red-400" />
                            </div>
                            <div>
                                <p class="text-white/60 text-sm mb-1">Cancelled</p>
                                <p class="text-3xl font-bold text-red-400">{{ $orderStatusCounts['cancelled'] }}</p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('orders.index') }}?status=cancelled" class="block w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-colors text-center font-semibold text-sm">
                        Lihat Semua →
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Desktop: Grid View -->
        <div class="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="glass p-4 sm:p-6 rounded-xl border border-yellow-500/20 hover:border-yellow-500/40 transition-all hover:scale-105">
                <div class="flex items-center justify-between mb-2">
                    <x-icon name="clock" class="w-6 h-6 text-yellow-400" />
                    <span class="text-xs sm:text-sm font-semibold text-yellow-400 bg-yellow-500/20 px-2 py-1 rounded-lg">{{ $orderStatusCounts['pending'] }}</span>
                </div>
                <p class="text-white/60 text-xs sm:text-sm mb-1">Pending</p>
                <p class="text-xl sm:text-2xl font-bold text-yellow-400">{{ $orderStatusCounts['pending'] }}</p>
            </div>
            
            <div class="glass p-4 sm:p-6 rounded-xl border border-blue-500/20 hover:border-blue-500/40 transition-all hover:scale-105">
                <div class="flex items-center justify-between mb-2">
                    <x-icon name="refresh" class="w-6 h-6 text-blue-400" />
                    <span class="text-xs sm:text-sm font-semibold text-blue-400 bg-blue-500/20 px-2 py-1 rounded-lg">{{ $orderStatusCounts['processing'] }}</span>
                </div>
                <p class="text-white/60 text-xs sm:text-sm mb-1">Processing</p>
                <p class="text-xl sm:text-2xl font-bold text-blue-400">{{ $orderStatusCounts['processing'] }}</p>
            </div>
            
            <div class="glass p-4 sm:p-6 rounded-xl border border-green-500/20 hover:border-green-500/40 transition-all hover:scale-105">
                <div class="flex items-center justify-between mb-2">
                    <x-icon name="check" class="w-6 h-6 text-green-400" />
                    <span class="text-xs sm:text-sm font-semibold text-green-400 bg-green-500/20 px-2 py-1 rounded-lg">{{ $orderStatusCounts['completed'] }}</span>
                </div>
                <p class="text-white/60 text-xs sm:text-sm mb-1">Completed</p>
                <p class="text-xl sm:text-2xl font-bold text-green-400">{{ $orderStatusCounts['completed'] }}</p>
            </div>
            
            <div class="glass p-4 sm:p-6 rounded-xl border border-red-500/20 hover:border-red-500/40 transition-all hover:scale-105">
                <div class="flex items-center justify-between mb-2">
                    <x-icon name="x" class="w-6 h-6 text-red-400" />
                    <span class="text-xs sm:text-sm font-semibold text-red-400 bg-red-500/20 px-2 py-1 rounded-lg">{{ $orderStatusCounts['cancelled'] }}</span>
                </div>
                <p class="text-white/60 text-xs sm:text-sm mb-1">Cancelled</p>
                <p class="text-xl sm:text-2xl font-bold text-red-400">{{ $orderStatusCounts['cancelled'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Tugas Dalam Progress Widget -->
    @if($inProgressOrders->count() > 0)
    <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-blue-500/20 mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30">
                        <x-icon name="file-text" class="w-5 h-5 text-blue-400" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Tugas Dalam Progress</h2>
                        <p class="text-sm text-white/60">{{ $inProgressOrders->count() }} tugas aktif</p>
                    </div>
                </div>
            <a href="{{ route('orders.index') }}?status=processing" 
               class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                Lihat Semua →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach($inProgressOrders as $order)
            <a href="{{ route('orders.show', $order) }}" 
               class="block glass glass-hover p-5 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-base sm:text-lg truncate group-hover:text-primary transition-colors flex items-center gap-2">
                            @if($order->type === 'product')
                                <x-icon name="package" class="w-4 h-4 text-primary" />
                                {{ $order->product->title ?? 'N/A' }}
                            @else
                                <x-icon name="target" class="w-4 h-4 text-primary" />
                                {{ $order->service->title ?? 'N/A' }}
                            @endif
                        </h3>
                        <p class="text-xs sm:text-sm text-white/60 mt-1">{{ $order->order_number }}</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-semibold border border-blue-500/30 flex-shrink-0 ml-2">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mb-4">
                    <div class="flex justify-between text-xs sm:text-sm text-white/70 mb-2">
                        <span>Progress</span>
                        <span class="font-bold text-blue-400">{{ $order->progress ?? 0 }}%</span>
                    </div>
                    <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary to-blue-500 transition-all duration-500 rounded-full" style="width: {{ $order->progress ?? 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Deadline & Price -->
                <div class="flex justify-between items-center pt-4 border-t border-white/10">
                    @if($order->deadline_at)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs sm:text-sm font-semibold {{ $order->deadline_at->isPast() ? 'text-red-400' : ($order->deadline_at->diffInHours() < 24 ? 'text-yellow-400' : 'text-white/60') }}">
                            {{ $order->deadline_at->diffForHumans() }}
                        </span>
                    </div>
                    @else
                    <span class="text-xs text-white/40">No deadline</span>
                    @endif
                    <span class="text-base sm:text-lg font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- File Download Center -->
    @if($completedOrders->count() > 0)
    <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10 mb-6 sm:mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 backdrop-blur-lg flex items-center justify-center border border-green-500/30">
                    <x-icon name="download" class="w-5 h-5 text-green-400" />
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold">File Download Center</h2>
                    <p class="text-sm text-white/60">{{ $completedOrders->count() }} file tersedia</p>
                </div>
            </div>
            <a href="{{ route('orders.index') }}?status=completed" 
               class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                Lihat Semua →
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach($completedOrders->take(6) as $order)
            <div class="glass glass-hover p-4 sm:p-5 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-green-500/30">
                        @if($order->type === 'product')
                            <x-icon name="package" class="w-6 h-6 text-green-400" />
                        @else
                            <x-icon name="file-text" class="w-6 h-6 text-green-400" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-sm sm:text-base truncate mb-1">
                            @if($order->type === 'product')
                                {{ $order->product->title ?? 'N/A' }}
                            @else
                                {{ $order->service->title ?? 'N/A' }}
                            @endif
                        </p>
                        <p class="text-xs text-white/60">{{ $order->completed_at?->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    @if($order->type === 'product' && $order->product && $order->product->file_path)
                    <a href="{{ route('products.download', $order->product) }}" 
                       class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary-dark rounded-xl transition-colors text-sm font-semibold text-center flex items-center justify-center gap-2">
                        <x-icon name="download" class="w-4 h-4" />
                        Download
                    </a>
                    @elseif($order->type === 'service' && $order->deliverable_path)
                    <a href="{{ route('orders.downloadDeliverable', $order) }}" 
                       class="flex-1 px-4 py-2.5 bg-primary hover:bg-primary-dark rounded-xl transition-colors text-sm font-semibold text-center flex items-center justify-center gap-2">
                        <x-icon name="download" class="w-4 h-4" />
                        Download
                    </a>
                    @endif
                    <a href="{{ route('orders.show', $order) }}" 
                       class="flex-1 px-4 py-2.5 glass glass-hover rounded-xl transition-all text-sm font-semibold text-center border border-white/10 flex items-center justify-center gap-2">
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Recent Orders -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <x-icon name="list" class="w-5 h-5 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Pesanan Terbaru</h2>
                        <p class="text-sm text-white/60">5 pesanan terakhir</p>
                    </div>
                </div>
                <a href="{{ route('orders.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                    Semua →
                </a>
            </div>
            
            @if($recentOrders->count() > 0)
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" 
                   class="block glass glass-hover p-4 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 group">
                    <div class="flex items-start sm:items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-bold text-sm sm:text-base truncate group-hover:text-primary transition-colors">{{ $order->order_number }}</p>
                                @include('components.order-status-badge', ['status' => $order->status])
                            </div>
                            <p class="text-xs sm:text-sm text-white/60 truncate flex items-center gap-1.5">
                                @if($order->type === 'product')
                                    <x-icon name="package" class="w-3.5 h-3.5 text-white/60" />
                                    {{ $order->product->title ?? 'N/A' }}
                                @else
                                    <x-icon name="target" class="w-3.5 h-3.5 text-white/60" />
                                    {{ $order->service->title ?? 'N/A' }}
                                @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-primary text-sm sm:text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <p class="text-xs text-white/60">{{ $order->created_at->diffForHumans() }}</p>
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
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 backdrop-blur-lg flex items-center justify-center border border-purple-500/30 relative">
                        <x-icon name="bell" class="w-5 h-5 text-purple-400" />
                        @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-bold border-2 border-dark">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Notifikasi Terbaru</h2>
                        <p class="text-sm text-white/60">{{ $unreadCount > 0 ? $unreadCount . ' belum dibaca' : 'Tidak ada notifikasi baru' }}</p>
                    </div>
                </div>
                <a href="{{ route('notifications.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                    Semua →
                </a>
            </div>
            
            @if($recentNotifications->count() > 0)
            <div class="space-y-3">
                @foreach($recentNotifications as $notification)
                <a href="{{ route('notifications.index') }}" 
                   class="block glass glass-hover p-4 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 {{ !$notification->is_read ? 'border-l-4 border-primary bg-primary/5' : '' }} group">
                    <p class="font-semibold text-sm sm:text-base {{ !$notification->is_read ? 'text-primary' : 'text-white/90' }} mb-1">
                        {{ $notification->message }}
                    </p>
                    <p class="text-xs text-white/60">{{ $notification->created_at->diffForHumans() }}</p>
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
    <div class="glass p-6 sm:p-8 rounded-2xl border-2 border-primary/30 bg-gradient-to-br from-primary/10 to-transparent">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                    <x-icon name="dashboard" class="w-7 h-7 text-primary" />
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-primary mb-1">Admin Dashboard</h2>
                    <p class="text-white/60 text-sm sm:text-base">Akses dashboard admin untuk melihat statistik lengkap platform</p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" 
               class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-xl transition-all hover:scale-105 font-semibold text-center touch-target">
                Buka Admin Dashboard →
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
