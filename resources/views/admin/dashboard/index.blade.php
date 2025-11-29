@extends('layouts.app')

@section('title', 'Admin Dashboard - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 flex items-center gap-2">
                <x-icon name="target" class="w-6 h-6 sm:w-8 sm:h-8" />
                Pusat Kontrol Admin
            </h1>
            <p class="text-white/60 text-sm sm:text-base">Manajemen dan analitik platform lengkap</p>
        </div>
        <div class="flex gap-2 sm:gap-3">
            <a href="{{ route('admin.financial-report.index') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all flex items-center gap-2">
                <x-icon name="dollar-sign" class="w-4 h-4" />
                Laporan Keuangan
            </a>
            <a href="{{ route('admin.settings.index') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all flex items-center gap-2">
                <x-icon name="settings" class="w-4 h-4" />
                Pengaturan
            </a>
            <a href="{{ route('dashboard') }}" 
               class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold hover:scale-105 transition-all flex items-center gap-2">
                <x-icon name="user" class="w-4 h-4" />
                Tampilan User
            </a>
        </div>
    </div>
    
    <!-- Alerts & Pending Actions -->
    @if($alerts['pending_payments'] > 0 || $alerts['pending_topups'] > 0 || $alerts['pending_withdrawals'] > 0 || $alerts['pending_verifications'] > 0 || $alerts['overdue_orders'] > 0)
    <div class="glass p-6 rounded-xl mb-6 border border-yellow-500/30 bg-yellow-500/5">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                <x-icon name="bell" class="w-5 h-5 text-yellow-400" />
            </div>
            <div>
                <h2 class="text-lg font-semibold">Memerlukan Perhatian</h2>
                <p class="text-xs text-white/50">Tindakan yang perlu diselesaikan</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @if($alerts['pending_payments'] > 0)
            <a href="{{ route('admin.payments.index') }}" 
               class="glass glass-hover p-4 rounded-xl transition-all border border-white/5 hover:border-yellow-500/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center">
                        <x-icon name="warning" class="w-5 h-5 text-yellow-400" />
                    </div>
                    <span class="px-2.5 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-medium">
                        {{ $alerts['pending_payments'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm text-white mb-1">Verifikasi Pembayaran Tertunda</p>
                <p class="text-xs text-white/50">Memerlukan persetujuan</p>
            </a>
            @endif
            
            @if($alerts['pending_topups'] > 0)
            <a href="{{ route('admin.wallet.index') }}" 
               class="glass glass-hover p-4 rounded-xl transition-all border border-white/5 hover:border-blue-500/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                        <x-icon name="currency" class="w-5 h-5 text-blue-400" />
                    </div>
                    <span class="px-2.5 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-medium">
                        {{ $alerts['pending_topups'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm text-white mb-1">Permintaan Top-Up Wallet</p>
                <p class="text-xs text-white/50">Menunggu persetujuan</p>
            </a>
            @endif
            
            @if($alerts['pending_withdrawals'] > 0)
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="glass glass-hover p-4 rounded-xl transition-all border border-white/5 hover:border-primary/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <x-icon name="withdraw" class="w-5 h-5 text-primary" />
                    </div>
                    <span class="px-2.5 py-1 bg-primary/20 text-primary rounded-full text-xs font-medium">
                        {{ $alerts['pending_withdrawals'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm text-white mb-1">Penarikan Tertunda</p>
                <p class="text-xs text-white/50">Permintaan penarikan seller</p>
            </a>
            @endif
            
            @if($alerts['pending_verifications'] > 0)
            <a href="{{ route('admin.verifications.index') }}" 
               class="glass glass-hover p-4 rounded-xl transition-all border border-white/5 hover:border-primary/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <x-icon name="user-check" class="w-5 h-5 text-primary" />
                    </div>
                    <span class="px-2.5 py-1 bg-primary/20 text-primary rounded-full text-xs font-medium">
                        {{ $alerts['pending_verifications'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm text-white mb-1">Verifikasi Seller Tertunda</p>
                <p class="text-xs text-white/50">Permintaan menjadi seller</p>
            </a>
            @endif
            
            @if($alerts['overdue_orders'] > 0)
            <a href="{{ route('orders.index') }}?overdue=1" 
               class="glass glass-hover p-4 rounded-xl transition-all border border-white/5 hover:border-red-500/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-lg bg-red-500/10 flex items-center justify-center">
                        <x-icon name="alert" class="w-5 h-5 text-red-400" />
                    </div>
                    <span class="px-2.5 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-medium">
                        {{ $alerts['overdue_orders'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm text-white mb-1">Pesanan Melewati Deadline</p>
                <p class="text-xs text-white/50">Perlu tindakan</p>
            </a>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Overview Statistics - KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <div class="glass p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="users" class="w-6 h-6 text-primary" />
                </div>
                @if($stats['new_users_today'] > 0)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded">
                    +{{ $stats['new_users_today'] }}
                </span>
                @endif
            </div>
            <p class="text-white/60 text-sm mb-2">Total Pengguna</p>
            <p class="text-3xl font-bold text-white mb-1">
                {{ number_format($stats['total_users']) }}
            </p>
            <p class="text-xs text-white/50">
                {{ $stats['active_sellers'] }} sellers aktif
            </p>
        </div>
        
        <!-- Total Orders -->
        <div class="glass p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="document" class="w-6 h-6 text-primary" />
                </div>
                @if($stats['orders_today'] > 0)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded">
                    +{{ $stats['orders_today'] }}
                </span>
                @endif
            </div>
            <p class="text-white/60 text-sm mb-2">Total Pesanan</p>
            <p class="text-3xl font-bold text-white mb-1">
                {{ number_format($stats['total_orders']) }}
            </p>
            <p class="text-xs text-white/50">
                {{ $stats['completed_orders'] }} selesai
            </p>
        </div>
        
        <!-- Total Revenue -->
        <div class="glass p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <x-icon name="currency" class="w-6 h-6 text-green-400" />
                </div>
                @if($stats['revenue_today'] > 0)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded">
                    Today
                </span>
                @endif
            </div>
            <p class="text-white/60 text-sm mb-2">Total Pendapatan</p>
            <p class="text-2xl font-bold text-green-400 mb-1">
                Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}M
            </p>
            <p class="text-xs text-white/50">
                Rp {{ number_format($stats['revenue_this_month'] / 1000, 0) }}k bulan ini
            </p>
        </div>
        
        <!-- Platform Commission -->
        <div class="glass p-6 rounded-xl border border-white/5 hover:border-primary/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <x-icon name="diamond" class="w-6 h-6 text-green-400" />
                </div>
            </div>
            <p class="text-white/60 text-sm mb-2">Komisi Platform</p>
            <p class="text-2xl font-bold text-green-400 mb-1">
                Rp {{ number_format($stats['platform_commission'] / 1000, 0) }}k
            </p>
            <p class="text-xs text-white/50">
                Avg: Rp {{ number_format($stats['average_order_value'] / 1000, 0) }}k/order
            </p>
        </div>
    </div>
    
    <!-- Quick Stats - Horizontal Bar -->
    <div class="glass p-4 rounded-xl mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center flex-shrink-0">
                    <x-icon name="clock" class="w-5 h-5 text-yellow-400" />
                </div>
                <div>
                    <p class="text-white/60 text-xs">Pesanan Tertunda</p>
                    <p class="text-xl font-bold text-white">{{ $stats['pending_orders'] }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                    <x-icon name="refresh" class="w-5 h-5 text-blue-400" />
                </div>
                <div>
                    <p class="text-white/60 text-xs">Diproses</p>
                    <p class="text-xl font-bold text-white">{{ $stats['processing_orders'] }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <x-icon name="package" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <p class="text-white/60 text-xs">Produk Aktif</p>
                    <p class="text-xl font-bold text-white">{{ $stats['active_products'] }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <x-icon name="shopping-bag" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <p class="text-white/60 text-xs">Layanan Aktif</p>
                    <p class="text-xl font-bold text-white">{{ $stats['active_services'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Escrow Statistics - Integrated Design -->
    @if(isset($escrowStats))
    <div class="glass p-6 rounded-xl mb-6 border border-white/5">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <x-icon name="shield" class="w-5 h-5 text-primary" />
            </div>
            <div>
                <h2 class="text-lg font-semibold">Statistik Escrow / Rekber</h2>
                <p class="text-xs text-white/50">Ringkasan dana yang ditahan dan dilepas</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Holding -->
            <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                <p class="text-white/60 text-xs mb-2">Dana Ditahan</p>
                <p class="text-2xl font-bold text-white mb-1">{{ $escrowStats['holding_count'] }}</p>
                <p class="text-xs text-white/50">Rp {{ number_format($escrowStats['holding_amount'] / 1000, 0) }}k</p>
            </div>
            
            <!-- Released -->
            <div class="p-4 rounded-lg bg-white/5 border border-green-500/20">
                <p class="text-white/60 text-xs mb-2">Dilepas</p>
                <p class="text-2xl font-bold text-green-400 mb-1">{{ $escrowStats['released_count'] }}</p>
                <p class="text-xs text-white/50">Rp {{ number_format($escrowStats['released_amount'] / 1000, 0) }}k</p>
            </div>
            
            <!-- Dispute -->
            <div class="p-4 rounded-lg bg-white/5 border border-yellow-500/20">
                <p class="text-white/60 text-xs mb-2">Dispute</p>
                <p class="text-2xl font-bold text-yellow-400 mb-1">{{ $escrowStats['disputed_count'] }}</p>
                <p class="text-xs text-white/50">Rp {{ number_format($escrowStats['disputed_amount'] / 1000, 0) }}k</p>
            </div>
            
            <!-- Total Volume -->
            <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                <p class="text-white/60 text-xs mb-2">Total Volume</p>
                <p class="text-2xl font-bold text-white mb-1">Rp {{ number_format($escrowStats['total_volume'] / 1000000, 1) }}M</p>
                <p class="text-xs text-white/50">Dispute Rate: {{ number_format($escrowStats['dispute_rate_percent'], 1) }}%</p>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="currency" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Tren Pendapatan</h2>
                    <p class="text-xs text-white/50">6 Bulan Terakhir</p>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between space-x-2 min-w-[300px] overflow-x-auto">
                @foreach($revenueChart as $data)
                <div class="flex-1 flex flex-col items-center min-w-0">
                    <div class="w-full bg-primary/40 rounded-t-lg mb-2 transition-all hover:bg-primary/60" 
                         style="height: {{ $data['revenue'] > 0 ? max(20, ($data['revenue'] / max(array_column($revenueChart, 'revenue'))) * 100) : 0 }}%">
                    </div>
                    <p class="text-xs text-white/60 truncate w-full text-center mb-1">{{ $data['month'] }}</p>
                    <p class="text-xs text-white font-medium">
                        Rp {{ number_format($data['revenue'] / 1000000, 1) }}M
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Trend -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <x-icon name="chart" class="w-5 h-5 text-blue-400" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Volume Pesanan</h2>
                    <p class="text-xs text-white/50">30 Hari Terakhir</p>
                </div>
            </div>
            <div class="h-64 flex items-end justify-between space-x-1 min-w-[400px] overflow-x-auto">
                @foreach(array_slice($orderTrend, -14) as $data)
                <div class="flex-1 flex flex-col items-center min-w-0">
                    <div class="w-full bg-blue-500/40 rounded-t mb-1 transition-all hover:bg-blue-500/60" 
                         style="height: {{ $data['orders'] > 0 ? max(10, ($data['orders'] / max(array_column($orderTrend, 'orders'))) * 100) : 0 }}%">
                    </div>
                    <p class="text-[10px] text-white/50 transform rotate-90 origin-bottom-left whitespace-nowrap" style="writing-mode: vertical-rl;">
                        {{ $data['date'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Three Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Activities -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="bell" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Aktivitas Terkini</h2>
                    <p class="text-xs text-white/50">Update terbaru platform</p>
                </div>
            </div>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="glass glass-hover p-3 rounded-lg text-sm border border-white/5">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($activity['icon'] === 'user')
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                    <x-icon name="user" class="w-4 h-4 text-blue-400" />
                                </div>
                            @elseif($activity['icon'] === 'check')
                                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center">
                                    <x-icon name="check" class="w-4 h-4 text-green-400" />
                                </div>
                            @elseif($activity['icon'] === 'package')
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <x-icon name="package" class="w-4 h-4 text-primary" />
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                    <x-icon name="{{ $activity['icon'] }}" class="w-4 h-4 text-white/60" />
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm break-words text-white/90">{{ $activity['message'] }}</p>
                            <p class="text-xs text-white/50 mt-1">
                                {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-white/40 py-8 text-sm">Tidak ada aktivitas terkini</p>
                @endforelse
            </div>
        </div>
        
        <!-- Top Sellers -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <x-icon name="star" class="w-5 h-5 text-primary" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Seller Teratas</h2>
                    <p class="text-xs text-white/50">Berdasarkan penjualan</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($topSellers as $index => $seller)
                <div class="glass glass-hover p-3 rounded-lg border border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center font-bold text-primary flex-shrink-0 text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate text-white">{{ $seller->name }}</p>
                            <p class="text-xs text-white/50">
                                {{ $seller->products_count + $seller->services_count }} listings
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-white">
                                Rp {{ number_format(($seller->total_sales ?? 0) / 1000, 0) }}k
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-white/40 py-8 text-sm">Belum ada seller</p>
                @endforelse
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="glass p-6 rounded-xl border border-white/5">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <x-icon name="trophy" class="w-5 h-5 text-green-400" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold">Produk Teratas</h2>
                    <p class="text-xs text-white/50">Berdasarkan penjualan</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse($topProducts as $index => $product)
                <div class="glass glass-hover p-3 rounded-lg border border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center font-bold text-green-400 flex-shrink-0 text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate text-white">{{ $product->title }}</p>
                            <p class="text-xs text-white/50">
                                {{ $product->orders_count ?? 0 }} sales
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-400">
                                Rp {{ number_format(($product->revenue ?? 0) / 1000, 0) }}k
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-white/40 py-8 text-sm">Belum ada produk</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Management Quick Links -->
    <div class="glass p-6 rounded-xl border border-white/5">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                <x-icon name="lightning" class="w-5 h-5 text-primary" />
            </div>
            <div>
                <h2 class="text-lg font-semibold">Manajemen Cepat</h2>
                <p class="text-xs text-white/50">Akses cepat ke fitur utama</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
            <a href="{{ route('admin.users.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="users" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Pengguna</p>
            </a>
            <a href="{{ route('products.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="package" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Produk</p>
            </a>
            <a href="{{ route('services.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="shopping-bag" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Layanan</p>
            </a>
            <a href="{{ route('orders.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="document" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Pesanan</p>
            </a>
            <a href="{{ route('admin.wallet.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="currency" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Dompet</p>
            </a>
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="withdraw" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Penarikan</p>
            </a>
            <a href="{{ route('notifications.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="bell" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Notifikasi</p>
            </a>
            <a href="{{ route('admin.settings.index') }}" 
               class="glass glass-hover p-4 rounded-xl text-center border border-white/5 hover:border-primary/30 transition-all group">
                <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-primary/20 transition-colors">
                    <x-icon name="settings" class="w-6 h-6 text-primary" />
                </div>
                <p class="text-xs font-medium text-white/90">Pengaturan</p>
            </a>
        </div>
    </div>
</div>
@endsection

