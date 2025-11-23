@extends('seller.layouts.dashboard')

@section('title', 'Seller Dashboard - Ebrystoree')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Welcome Banner - Modern & Attractive -->
    <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 lg:p-10 bg-gradient-to-br from-primary/20 via-primary/10 to-blue-500/10 border border-primary/30">
        <!-- Animated Background -->
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
        </div>
        
        <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-primary/30 backdrop-blur-lg flex items-center justify-center border border-primary/50">
                        <x-icon name="user" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-1">
                            Halo, <span class="text-primary">{{ auth()->user()->name }}!</span>
                        </h1>
                        <p class="text-white/70 text-sm sm:text-base">Kelola bisnis Anda dari dashboard ini</p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-2 sm:gap-3">
                <a href="{{ route('seller.products.create') }}" 
                   class="px-4 py-2 sm:px-6 sm:py-3 bg-primary hover:bg-primary-dark rounded-xl font-semibold transition-all hover:scale-105 flex items-center gap-2 text-sm sm:text-base">
                    <x-icon name="package" class="w-4 h-4" />
                    <span>Tambah Produk</span>
                </a>
                <a href="{{ route('seller.services.create') }}" 
                   class="px-4 py-2 sm:px-6 sm:py-3 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 flex items-center gap-2 text-sm sm:text-base border border-white/20">
                    <x-icon name="target" class="w-4 h-4" />
                    <span>Tambah Jasa</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid - Simplified & Lightweight -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4" x-data="{ showDetails: {} }">
        <!-- Total Pendapatan -->
        <div class="group glass rounded-xl p-4 sm:p-5 border border-green-500/20 hover:border-green-500/30 transition-colors"
             @mouseenter="showDetails.pendapatan = true"
             @mouseleave="showDetails.pendapatan = false">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-green-500/20 flex items-center justify-center border border-green-500/30">
                    <x-icon name="currency" class="w-5 h-5 text-green-400" />
                </div>
                <span class="text-xs font-semibold text-green-400 bg-green-500/20 px-2 py-0.5 rounded">Pendapatan</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-green-400 mb-0.5 break-words">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-white/50" x-show="showDetails.pendapatan" x-transition>
                Bulan ini: <span class="text-green-400 font-semibold">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</span>
            </p>
        </div>
        
        <!-- Total Pesanan -->
        <div class="group glass rounded-xl p-4 sm:p-5 border border-blue-500/20 hover:border-blue-500/30 transition-colors"
             @mouseenter="showDetails.pesanan = true"
             @mouseleave="showDetails.pesanan = false">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                    <x-icon name="document" class="w-5 h-5 text-blue-400" />
                </div>
                <span class="text-xs font-semibold text-blue-400 bg-blue-500/20 px-2 py-0.5 rounded">Pesanan</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-blue-400 mb-0.5">
                {{ number_format($stats['total_orders'], 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-2 text-xs text-white/50" x-show="showDetails.pesanan" x-transition>
                <span>{{ $stats['completed_orders'] }} <span class="text-blue-400 font-semibold">selesai</span></span>
                @if($stats['total_ratings'] > 0)
                <span class="text-yellow-400">•</span>
                <span class="text-yellow-400">{{ number_format($stats['average_rating'], 1) }} ⭐</span>
                @endif
            </div>
        </div>
        
        <!-- Produk & Jasa -->
        <div class="group glass rounded-xl p-4 sm:p-5 border border-purple-500/20 hover:border-purple-500/30 transition-colors"
             @mouseenter="showDetails.katalog = true"
             @mouseleave="showDetails.katalog = false">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-purple-500/20 flex items-center justify-center border border-purple-500/30">
                    <x-icon name="package" class="w-5 h-5 text-purple-400" />
                </div>
                <span class="text-xs font-semibold text-purple-400 bg-purple-500/20 px-2 py-0.5 rounded">Katalog</span>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-purple-400 mb-0.5">
                {{ $stats['total_products'] + $stats['total_services'] }}
            </p>
            <p class="text-xs text-white/50" x-show="showDetails.katalog" x-transition>
                {{ $stats['total_products'] }} produk, {{ $stats['total_services'] }} jasa
            </p>
        </div>
        
        <!-- Withdrawable Balance -->
        <div class="group glass rounded-xl p-4 sm:p-5 border border-primary/20 hover:border-primary/30 transition-colors">
            <div class="flex items-center justify-between mb-2">
                <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center border border-primary/30">
                    <x-icon name="withdraw" class="w-5 h-5 text-primary" />
                </div>
                <a href="{{ route('seller.withdrawal.index') }}" 
                   class="text-xs font-semibold text-primary bg-primary/20 px-2 py-0.5 rounded hover:bg-primary/30 transition-colors">
                    Tarik →
                </a>
            </div>
            <p class="text-xl sm:text-2xl font-bold text-primary mb-0.5 break-words">
                Rp {{ number_format($withdrawableBalance, 0, ',', '.') }}
            </p>
            <p class="text-xs text-white/50">Siap untuk ditarik</p>
        </div>
    </div>
    
    <!-- Order Status Quick View - Simplified -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3">
        <div class="glass p-3 sm:p-4 rounded-lg border border-yellow-500/20 hover:border-yellow-500/30 transition-colors">
            <div class="flex items-center gap-2 mb-1">
                <x-icon name="clock" class="w-4 h-4 text-yellow-400" />
                <span class="text-xs font-semibold text-yellow-400 bg-yellow-500/20 px-1.5 py-0.5 rounded">{{ $stats['pending_orders'] }}</span>
            </div>
            <p class="text-white/50 text-xs mb-0.5">Pending</p>
            <p class="text-lg sm:text-xl font-bold text-yellow-400">{{ $stats['pending_orders'] }}</p>
        </div>
        
        <div class="glass p-3 sm:p-4 rounded-lg border border-blue-500/20 hover:border-blue-500/30 transition-colors">
            <div class="flex items-center gap-2 mb-1">
                <x-icon name="lightning" class="w-4 h-4 text-blue-400" />
                <span class="text-xs font-semibold text-blue-400 bg-blue-500/20 px-1.5 py-0.5 rounded">{{ $stats['processing_orders'] }}</span>
            </div>
            <p class="text-white/50 text-xs mb-0.5">Processing</p>
            <p class="text-lg sm:text-xl font-bold text-blue-400">{{ $stats['processing_orders'] }}</p>
        </div>
        
        <div class="glass p-3 sm:p-4 rounded-lg border border-green-500/20 hover:border-green-500/30 transition-colors">
            <div class="flex items-center gap-2 mb-1">
                <x-icon name="check" class="w-4 h-4 text-green-400" />
                <span class="text-xs font-semibold text-green-400 bg-green-500/20 px-1.5 py-0.5 rounded">{{ $stats['completed_orders'] }}</span>
            </div>
            <p class="text-white/50 text-xs mb-0.5">Completed</p>
            <p class="text-lg sm:text-xl font-bold text-green-400">{{ $stats['completed_orders'] }}</p>
        </div>
        
        <div class="glass p-3 sm:p-4 rounded-lg border border-primary/20 hover:border-primary/30 transition-colors">
            <div class="flex items-center gap-2 mb-1">
                <x-icon name="withdraw" class="w-4 h-4 text-primary" />
                <span class="text-xs font-semibold text-primary bg-primary/20 px-1.5 py-0.5 rounded">Tarik</span>
            </div>
            <p class="text-white/50 text-xs mb-0.5">Withdrawable</p>
            <p class="text-base sm:text-lg font-bold text-primary break-words">Rp {{ number_format($withdrawableBalance, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Order Queue Kanban Board - Modern Design -->
    <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10" 
         x-data="{ 
             activeTab: 'pending', 
             completedCollapsed: true,
             isMobile: window.innerWidth < 768
         }"
         x-init="
             window.addEventListener('resize', () => {
                 isMobile = window.innerWidth < 768;
             });
         ">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                    <x-icon name="chart" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold">Order Queue</h2>
                    <p class="text-sm text-white/60">Kelola pesanan Anda dengan mudah</p>
                </div>
            </div>
            <a href="{{ route('orders.index') }}" 
               class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                Lihat Semua →
            </a>
        </div>
        
        <!-- Mobile Tab Navigation -->
        <div class="md:hidden mb-4 flex gap-2 border-b border-white/10">
            <button @click="activeTab = 'pending'" 
                    :class="activeTab === 'pending' ? 'border-b-2 border-yellow-400 text-yellow-400' : 'text-white/60'"
                    class="flex-1 py-2 px-4 text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <x-icon name="clock" class="w-4 h-4" />
                <span>Pending</span>
                <span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-bold border border-yellow-500/30">
                    {{ $pendingOrders->count() }}
                </span>
            </button>
            <button @click="activeTab = 'processing'" 
                    :class="activeTab === 'processing' ? 'border-b-2 border-blue-400 text-blue-400' : 'text-white/60'"
                    class="flex-1 py-2 px-4 text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <x-icon name="refresh" class="w-4 h-4" />
                <span>Processing</span>
                <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold border border-blue-500/30">
                    {{ $processingOrders->count() }}
                </span>
            </button>
            <button @click="activeTab = 'completed'" 
                    :class="activeTab === 'completed' ? 'border-b-2 border-green-400 text-green-400' : 'text-white/60'"
                    class="flex-1 py-2 px-4 text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                <x-icon name="check" class="w-4 h-4" />
                <span>Completed</span>
                <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded-full text-xs font-bold border border-green-500/30">
                    {{ $completedOrdersToday->count() }}
                </span>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <!-- Pending Column -->
            <div class="rounded-2xl p-4 sm:p-6 bg-gradient-to-b from-yellow-500/10 via-yellow-500/5 to-transparent border-2 border-yellow-500/30 hover:border-yellow-500/50 transition-all"
                 x-show="!isMobile || activeTab === 'pending'"
                 x-transition
                 class="hidden md:block">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-yellow-500/20 backdrop-blur-lg flex items-center justify-center border border-yellow-500/30">
                            <x-icon name="clock" class="w-5 h-5 text-yellow-400" />
                        </div>
                        <h3 class="font-bold text-yellow-400">Pending</h3>
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs sm:text-sm font-bold border border-yellow-500/30">
                        {{ $pendingOrders->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($pendingOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" 
                       class="block glass glass-hover p-3 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 hover:border-yellow-500/30 group">
                        <div class="flex items-start justify-between mb-1.5">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm truncate group-hover:text-yellow-400 transition-colors">{{ $order->order_number }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">
                                    @if($order->type === 'product')
                                        <x-icon name="package" class="w-3 h-3 inline mr-1" />
                                        {{ $order->product->title ?? 'N/A' }}
                                    @else
                                        <x-icon name="target" class="w-3 h-3 inline mr-1" />
                                        {{ $order->service->title ?? 'N/A' }}
                                    @endif
                                </p>
                            </div>
                            @if($order->priority === 'urgent')
                            <span class="px-1.5 py-0.5 bg-red-500/20 text-red-400 rounded text-xs font-bold border border-red-500/30 flex items-center gap-1 flex-shrink-0 ml-2">
                                <x-icon name="alert" class="w-3 h-3" />
                                Urgent
                            </span>
                            @elseif($order->priority === 'high')
                            <span class="px-1.5 py-0.5 bg-orange-500/20 text-orange-400 rounded text-xs font-bold border border-orange-500/30 flex items-center gap-1 flex-shrink-0 ml-2">
                                <x-icon name="lightning" class="w-3 h-3" />
                                High
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/10">
                            <p class="text-xs font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            @if($order->deadline_at)
                            <div class="flex items-center gap-1 text-xs text-white/60">
                                <x-icon name="clock" class="w-3 h-3" />
                                <span class="{{ $order->deadline_at->diffInHours() < 24 ? 'text-red-400 font-bold' : '' }}">{{ $order->deadline_at->format('d M') }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6 px-4">
                        <x-icon name="mailbox" class="w-10 h-10 text-white/20 mx-auto mb-2" />
                        <p class="text-white/40 text-xs">Tidak ada pesanan pending</p>
                    </div>
                    @endforelse
                </div>
                @if($pendingOrders->count() > 0)
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                   class="mt-3 block text-center py-2 px-4 glass hover:bg-white/10 rounded-lg text-xs font-semibold transition-all border border-white/20 text-yellow-400 hover:text-yellow-300">
                    Lihat Semua Pending →
                </a>
                @endif
            </div>
            
            <!-- Processing Column -->
            <div class="rounded-2xl p-4 sm:p-6 bg-gradient-to-b from-blue-500/10 via-blue-500/5 to-transparent border-2 border-blue-500/30 hover:border-blue-500/50 transition-all"
                 x-show="!isMobile || activeTab === 'processing'"
                 x-transition
                 class="hidden md:block">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30">
                            <x-icon name="refresh" class="w-5 h-5 text-blue-400" />
                        </div>
                        <h3 class="font-bold text-blue-400">Processing</h3>
                    </div>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs sm:text-sm font-bold border border-blue-500/30">
                        {{ $processingOrders->count() }}
                    </span>
                </div>
                <div class="space-y-2 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($processingOrders as $order)
                    <a href="{{ route('orders.show', $order) }}" 
                       class="block glass glass-hover p-3 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 hover:border-blue-500/30 group">
                        <div class="flex items-start justify-between mb-1.5">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm truncate group-hover:text-blue-400 transition-colors">{{ $order->order_number }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">
                                    @if($order->type === 'product')
                                        <x-icon name="package" class="w-3 h-3 inline mr-1" />
                                        {{ $order->product->title ?? 'N/A' }}
                                    @else
                                        <x-icon name="target" class="w-3 h-3 inline mr-1" />
                                        {{ $order->service->title ?? 'N/A' }}
                                    @endif
                                </p>
                            </div>
                            @if($order->priority === 'urgent')
                            <span class="px-1.5 py-0.5 bg-red-500/20 text-red-400 rounded text-xs font-bold border border-red-500/30 flex items-center gap-1 flex-shrink-0 ml-2">
                                <x-icon name="alert" class="w-3 h-3" />
                                Urgent
                            </span>
                            @elseif($order->priority === 'high')
                            <span class="px-1.5 py-0.5 bg-orange-500/20 text-orange-400 rounded text-xs font-bold border border-orange-500/30 flex items-center gap-1 flex-shrink-0 ml-2">
                                <x-icon name="lightning" class="w-3 h-3" />
                                High
                            </span>
                            @endif
                        </div>
                        <!-- Progress Bar -->
                        <div class="mt-2 mb-2">
                            <div class="flex items-center justify-between mb-0.5">
                                <span class="text-xs text-white/60">Progress</span>
                                <span class="text-xs font-bold text-blue-400">{{ $order->progress ?? 0 }}%</span>
                            </div>
                            <div class="h-1.5 bg-white/10 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-500" 
                                     style="width: {{ $order->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-white/10">
                            <p class="text-xs font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            @if($order->deadline_at)
                            <div class="flex items-center gap-1 text-xs font-semibold {{ $order->deadline_at->diffInHours() < 24 ? 'text-red-400' : ($order->deadline_at->diffInHours() < 48 ? 'text-orange-400' : 'text-white/60') }}">
                                <x-icon name="clock" class="w-3 h-3" />
                                <span>{{ $order->deadline_at->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6 px-4">
                        <x-icon name="mailbox" class="w-10 h-10 text-white/20 mx-auto mb-2" />
                        <p class="text-white/40 text-xs">Tidak ada pesanan processing</p>
                    </div>
                    @endforelse
                </div>
                @if($processingOrders->count() > 0)
                <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
                   class="mt-3 block text-center py-2 px-4 glass hover:bg-white/10 rounded-lg text-xs font-semibold transition-all border border-white/20 text-blue-400 hover:text-blue-300">
                    Lihat Semua Processing →
                </a>
                @endif
            </div>
            
            <!-- Completed Today Column -->
            <div class="rounded-2xl p-4 sm:p-6 bg-gradient-to-b from-green-500/10 via-green-500/5 to-transparent border-2 border-green-500/30 hover:border-green-500/50 transition-all"
                 x-show="!isMobile || activeTab === 'completed'"
                 x-transition
                 class="hidden md:block">
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-green-500/20 backdrop-blur-lg flex items-center justify-center border border-green-500/30">
                            <x-icon name="check" class="w-5 h-5 text-green-400" />
                        </div>
                        <h3 class="font-bold text-green-400">Completed</h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs sm:text-sm font-bold border border-green-500/30">
                            {{ $completedOrdersToday->count() }}
                        </span>
                        <button @click="completedCollapsed = !completedCollapsed" 
                                class="md:hidden p-1.5 glass hover:bg-white/10 rounded-lg transition-all border border-white/20">
                            <x-icon name="chevron-down" 
                                    class="w-4 h-4 text-green-400 transition-transform"
                                    x-bind:class="completedCollapsed ? '' : 'rotate-180'" />
                        </button>
                    </div>
                </div>
                <div class="space-y-2 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar"
                     x-show="!completedCollapsed || !isMobile"
                     x-transition>
                    @forelse($completedOrdersToday as $order)
                    <a href="{{ route('orders.show', $order) }}" 
                       class="block glass glass-hover p-3 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 hover:border-green-500/30 group">
                        <div class="flex items-start justify-between mb-1.5">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm truncate group-hover:text-green-400 transition-colors">{{ $order->order_number }}</p>
                                <p class="text-xs text-white/60 truncate mt-0.5">
                                    @if($order->type === 'product')
                                        <x-icon name="package" class="w-3 h-3 inline mr-1" />
                                        {{ $order->product->title ?? 'N/A' }}
                                    @else
                                        <x-icon name="target" class="w-3 h-3 inline mr-1" />
                                        {{ $order->service->title ?? 'N/A' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-white/10">
                            <p class="text-xs font-bold text-green-400">+Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="text-xs text-white/60">{{ $order->completed_at ? $order->completed_at->format('H:i') : '' }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6 px-4">
                        <x-icon name="sparkles" class="w-10 h-10 text-white/20 mx-auto mb-2" />
                        <p class="text-white/40 text-xs">Belum ada yang selesai hari ini</p>
                    </div>
                    @endforelse
                </div>
                @if($completedOrdersToday->count() > 0)
                <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
                   class="mt-3 block text-center py-2 px-4 glass hover:bg-white/10 rounded-lg text-xs font-semibold transition-all border border-white/20 text-green-400 hover:text-green-300">
                    Lihat Semua Completed →
                </a>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Analytics Quick Link -->
    <a href="{{ route('seller.analytics') }}" 
       class="block glass p-4 sm:p-6 rounded-2xl border border-white/10 hover:border-primary/30 transition-all hover:scale-[1.02] group">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30 group-hover:bg-primary/30 transition-colors">
                    <x-icon name="chart" class="w-6 h-6 text-primary" />
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-bold group-hover:text-primary transition-colors">Analytics</h3>
                    <p class="text-sm text-white/60">Lihat Revenue & Order Trend lengkap</p>
                </div>
            </div>
            <x-icon name="arrow-right" class="w-5 h-5 text-white/40 group-hover:text-primary transition-colors" />
        </div>
    </a>
    
    <!-- Recent Orders & Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Orders -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <x-icon name="list" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Pesanan Terbaru</h2>
                        <p class="text-sm text-white/60">10 pesanan terakhir</p>
                    </div>
                </div>
                <a href="{{ route('orders.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                    Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" 
                   class="block glass glass-hover p-4 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10 group">
                    <div class="flex items-start sm:items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-bold text-sm sm:text-base truncate group-hover:text-primary transition-colors">{{ $order->order_number }}</p>
                                @include('components.order-status-badge', ['status' => $order->status])
                            </div>
                            <p class="text-xs sm:text-sm text-white/60 truncate flex items-center gap-1">
                                @if($order->type === 'product')
                                    <x-icon name="package" class="w-3 h-3 flex-shrink-0" />
                                    {{ $order->product->title ?? 'N/A' }}
                                @else
                                    <x-icon name="target" class="w-3 h-3 flex-shrink-0" />
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
                @empty
                <div class="text-center py-12 px-4">
                    <x-icon name="mailbox" class="w-16 h-16 text-white/20 mx-auto mb-3" />
                    <p class="text-white/40 text-sm">Belum ada pesanan</p>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-500/20 backdrop-blur-lg flex items-center justify-center border border-green-500/30">
                        <x-icon name="dollar" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Transaksi Terbaru</h2>
                        <p class="text-sm text-white/60">5 transaksi terakhir</p>
                    </div>
                </div>
                <a href="{{ route('wallet.index') }}" 
                   class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20">
                    Semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                <div class="glass glass-hover p-4 rounded-xl transition-all hover:scale-[1.02] hover:shadow-lg border border-white/10">
                    <div class="flex items-start sm:items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xl">
                                    @if($transaction->status === 'available')
                                        <x-icon name="check" class="w-4 h-4 text-green-400" />
                                    @elseif($transaction->status === 'pending')
                                        <x-icon name="clock" class="w-4 h-4 text-yellow-400" />
                                    @else
                                        <x-icon name="dollar" class="w-4 h-4 text-primary" />
                                    @endif
                                </span>
                                <p class="font-bold text-sm sm:text-base truncate">
                                    {{ $transaction->order->order_number ?? 'Earning #' . $transaction->id }}
                                </p>
                            </div>
                            <p class="text-xs sm:text-sm text-white/60 truncate flex items-center gap-1">
                                @if($transaction->order)
                                    @if($transaction->order->type === 'product')
                                        <x-icon name="package" class="w-3 h-3 flex-shrink-0" />
                                        {{ $transaction->order->product->title ?? 'Produk Digital' }}
                                    @else
                                        <x-icon name="target" class="w-3 h-3 flex-shrink-0" />
                                        {{ $transaction->order->service->title ?? 'Jasa Joki Tugas' }}
                                    @endif
                                @else
                                    Earning dari penjualan
                                @endif
                                @if($transaction->status === 'pending')
                                    <span class="text-yellow-400"> (Pending)</span>
                                @elseif($transaction->status === 'available')
                                    <span class="text-green-400"> (Tersedia)</span>
                                @else
                                    <span class="text-blue-400"> (Ditarik)</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-bold text-green-400 text-base sm:text-lg">+Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                            <p class="text-xs text-white/60">{{ $transaction->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 px-4">
                    <x-icon name="dollar" class="w-16 h-16 text-white/20 mx-auto mb-3" />
                    <p class="text-white/40 text-sm">Belum ada transaksi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Custom Scrollbar Style -->
<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
@endsection
