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
    <div class="glass p-4 sm:p-6 rounded-lg mb-6 border-2 border-yellow-500/30">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
            <x-icon name="bell" class="w-5 h-5 sm:w-6 sm:h-6" />
            <span>Memerlukan Perhatian</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            @if($alerts['pending_payments'] > 0)
            <a href="{{ route('admin.payments.index') }}" 
               class="glass glass-hover p-4 rounded-lg transition-all hover:scale-[1.02] border border-yellow-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-yellow-400">
                        <x-icon name="warning" class="w-6 h-6 sm:w-8 sm:h-8" />
                    </span>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-bold">
                        {{ $alerts['pending_payments'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm">Verifikasi Pembayaran Tertunda</p>
                <p class="text-xs text-white/60 mt-1">Memerlukan persetujuan</p>
            </a>
            @endif
            
            @if($alerts['pending_topups'] > 0)
            <a href="{{ route('admin.wallet.index') }}" 
               class="glass glass-hover p-4 rounded-lg transition-all hover:scale-[1.02] border border-blue-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-blue-400">
                        <x-icon name="currency" class="w-6 h-6 sm:w-8 sm:h-8" />
                    </span>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold">
                        {{ $alerts['pending_topups'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm">Permintaan Top-Up Wallet</p>
                <p class="text-xs text-white/60 mt-1">Menunggu persetujuan</p>
            </a>
            @endif
            
            @if($alerts['pending_withdrawals'] > 0)
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="glass glass-hover p-4 rounded-lg transition-all hover:scale-[1.02] border border-primary/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-primary">
                        <x-icon name="withdraw" class="w-6 h-6 sm:w-8 sm:h-8" />
                    </span>
                    <span class="px-3 py-1 bg-primary/20 text-primary rounded-full text-xs font-bold">
                        {{ $alerts['pending_withdrawals'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm">Penarikan Tertunda</p>
                <p class="text-xs text-white/60 mt-1">Permintaan penarikan seller</p>
            </a>
            @endif
            
            @if($alerts['pending_verifications'] > 0)
            <a href="{{ route('admin.verifications.index') }}" 
               class="glass glass-hover p-4 rounded-lg transition-all hover:scale-[1.02] border border-purple-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-purple-400">
                        <x-icon name="user-check" class="w-6 h-6 sm:w-8 sm:h-8" />
                    </span>
                    <span class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs font-bold">
                        {{ $alerts['pending_verifications'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm">Verifikasi Seller Tertunda</p>
                <p class="text-xs text-white/60 mt-1">Permintaan menjadi seller</p>
            </a>
            @endif
            
            @if($alerts['overdue_orders'] > 0)
            <a href="{{ route('orders.index') }}?overdue=1" 
               class="glass glass-hover p-4 rounded-lg transition-all hover:scale-[1.02] border border-red-500/30">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-red-400">
                        <x-icon name="alert" class="w-6 h-6 sm:w-8 sm:h-8" />
                    </span>
                    <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-bold">
                        {{ $alerts['overdue_orders'] }}
                    </span>
                </div>
                <p class="font-semibold text-sm">Pesanan Melewati Deadline</p>
                <p class="text-xs text-white/60 mt-1">Perlu tindakan</p>
            </a>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Overview Statistics -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- Total Users -->
        <div class="glass glass-hover p-4 sm:p-6 rounded-lg">
            <div class="flex items-center justify-between mb-3">
                <x-icon name="users" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
                @if($stats['new_users_today'] > 0)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold">
                    +{{ $stats['new_users_today'] }}
                </span>
                @endif
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1">Total Pengguna</p>
            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-primary">
                {{ number_format($stats['total_users']) }}
            </p>
            <p class="text-xs text-white/60 mt-2">
                {{ $stats['active_sellers'] }} sellers
            </p>
        </div>
        
        <!-- Total Orders -->
        <div class="glass glass-hover p-4 sm:p-6 rounded-lg">
            <div class="flex items-center justify-between mb-3">
                <x-icon name="document" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
                @if($stats['orders_today'] > 0)
                <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold">
                    +{{ $stats['orders_today'] }}
                </span>
                @endif
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1">Total Pesanan</p>
            <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-primary">
                {{ number_format($stats['total_orders']) }}
            </p>
            <p class="text-xs text-white/60 mt-2">
                {{ $stats['completed_orders'] }} completed
            </p>
        </div>
        
        <!-- Total Revenue -->
        <div class="glass glass-hover p-4 sm:p-6 rounded-lg">
            <div class="flex items-center justify-between mb-3">
                <x-icon name="currency" class="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
                @if($stats['revenue_today'] > 0)
                <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold">
                    Today
                </span>
                @endif
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1">Total Pendapatan</p>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-primary break-words">
                Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-white/60 mt-2">
                Rp {{ number_format($stats['revenue_this_month'], 0, ',', '.') }} this month
            </p>
        </div>
        
        <!-- Platform Commission -->
        <div class="glass glass-hover p-4 sm:p-6 rounded-lg">
            <div class="flex items-center justify-between mb-3">
                <x-icon name="diamond" class="w-6 h-6 sm:w-8 sm:h-8 text-green-400" />
            </div>
            <p class="text-white/60 text-xs sm:text-sm mb-1">Komisi Platform</p>
            <p class="text-lg sm:text-xl lg:text-2xl font-bold text-green-400 break-words">
                Rp {{ number_format($stats['platform_commission'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-white/60 mt-2">
                Avg order: Rp {{ number_format($stats['average_order_value'], 0, ',', '.') }}
            </p>
        </div>
    </div>
    
    <!-- Secondary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
        <div class="glass p-3 sm:p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Pesanan Tertunda</p>
            <p class="text-lg sm:text-xl font-bold text-yellow-400">{{ $stats['pending_orders'] }}</p>
        </div>
        <div class="glass p-3 sm:p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Diproses</p>
            <p class="text-lg sm:text-xl font-bold text-blue-400">{{ $stats['processing_orders'] }}</p>
        </div>
        <div class="glass p-3 sm:p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Produk Aktif</p>
            <p class="text-lg sm:text-xl font-bold text-primary">{{ $stats['active_products'] }}</p>
        </div>
        <div class="glass p-3 sm:p-4 rounded-lg">
            <p class="text-white/60 text-xs mb-1">Layanan Aktif</p>
            <p class="text-lg sm:text-xl font-bold text-primary">{{ $stats['active_services'] }}</p>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Revenue Chart -->
        <div class="glass p-4 sm:p-6 rounded-lg overflow-x-auto">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                <x-icon name="currency" class="w-5 h-5" />
                Tren Pendapatan (6 Bulan)
            </h2>
            <div class="h-48 sm:h-64 flex items-end justify-between space-x-1 sm:space-x-2 min-w-[300px]">
                @foreach($revenueChart as $data)
                <div class="flex-1 flex flex-col items-center min-w-0">
                    <div class="w-full bg-primary/30 rounded-t-lg mb-1 sm:mb-2" 
                         style="height: {{ $data['revenue'] > 0 ? max(20, ($data['revenue'] / max(array_column($revenueChart, 'revenue'))) * 100) : 0 }}%">
                    </div>
                    <p class="text-[10px] sm:text-xs text-white/60 truncate w-full text-center">{{ $data['month'] }}</p>
                    <p class="text-[10px] sm:text-xs text-primary font-semibold">
                        Rp {{ number_format($data['revenue'] / 1000000, 1) }}M
                    </p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Trend -->
        <div class="glass p-4 sm:p-6 rounded-lg overflow-x-auto">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                <x-icon name="chart" class="w-5 h-5" />
                Volume Pesanan (30 Hari)
            </h2>
            <div class="h-48 sm:h-64 flex items-end justify-between space-x-0.5 sm:space-x-1 min-w-[400px]">
                @foreach(array_slice($orderTrend, -14) as $data)
                <div class="flex-1 flex flex-col items-center min-w-0">
                    <div class="w-full bg-blue-500/30 rounded-t mb-1" 
                         style="height: {{ $data['orders'] > 0 ? max(10, ($data['orders'] / max(array_column($orderTrend, 'orders'))) * 100) : 0 }}%">
                    </div>
                    <p class="text-[8px] sm:text-xs text-white/60 transform rotate-90 origin-bottom-left whitespace-nowrap" style="writing-mode: vertical-rl;">
                        {{ $data['date'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- Three Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Recent Activities -->
        <div class="glass p-4 sm:p-6 rounded-lg">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                <x-icon name="bell" class="w-5 h-5" />
                Aktivitas Terkini
            </h2>
            <div class="space-y-2 sm:space-y-3 max-h-96 overflow-y-auto">
                @forelse($recentActivities as $activity)
                <div class="glass glass-hover p-3 rounded-lg text-sm">
                    <div class="flex items-start gap-2">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($activity['icon'] === 'user')
                                <x-icon name="user" class="w-5 h-5 text-blue-400" />
                            @elseif($activity['icon'] === 'check')
                                <x-icon name="check" class="w-5 h-5 text-green-400" />
                            @elseif($activity['icon'] === 'package')
                                <x-icon name="package" class="w-5 h-5 text-purple-400" />
                            @else
                                <x-icon name="{{ $activity['icon'] }}" class="w-5 h-5" />
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs sm:text-sm break-words">{{ $activity['message'] }}</p>
                            <p class="text-xs text-white/60 mt-1">
                                {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-white/40 py-4 text-sm">Tidak ada aktivitas terkini</p>
                @endforelse
            </div>
        </div>
        
        <!-- Top Sellers -->
        <div class="glass p-4 sm:p-6 rounded-lg">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                <x-icon name="star" class="w-5 h-5" />
                Seller Teratas
            </h2>
            <div class="space-y-2 sm:space-y-3">
                @forelse($topSellers as $index => $seller)
                <div class="glass glass-hover p-3 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary/20 rounded-full flex items-center justify-center font-bold text-primary flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $seller->name }}</p>
                            <p class="text-xs text-white/60">
                                {{ $seller->products_count + $seller->services_count }} listings
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">
                                Rp {{ number_format(($seller->total_sales ?? 0) / 1000, 0) }}k
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-white/40 py-4 text-sm">Belum ada seller</p>
                @endforelse
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="glass p-4 sm:p-6 rounded-lg">
            <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                <x-icon name="trophy" class="w-5 h-5" />
                Produk Teratas
            </h2>
            <div class="space-y-2 sm:space-y-3">
                @forelse($topProducts as $index => $product)
                <div class="glass glass-hover p-3 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center font-bold text-green-400 flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate">{{ $product->title }}</p>
                            <p class="text-xs text-white/60">
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
                <p class="text-center text-white/40 py-4 text-sm">Belum ada produk</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Management Quick Links -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
            <x-icon name="lightning" class="w-5 h-5" />
            Manajemen Cepat
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="users" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Pengguna</p>
            </a>
            <a href="{{ route('products.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="package" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Produk</p>
            </a>
            <a href="{{ route('services.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="shopping-bag" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Layanan</p>
            </a>
            <a href="{{ route('orders.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="document" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Pesanan</p>
            </a>
            <a href="{{ route('admin.wallet.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="currency" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Dompet</p>
            </a>
            <a href="{{ route('admin.withdrawals.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="withdraw" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Penarikan</p>
            </a>
            <a href="{{ route('notifications.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="bell" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Notifikasi</p>
            </a>
            <a href="{{ route('admin.settings.index') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="settings" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Pengaturan</p>
            </a>
            <a href="{{ route('admin.dashboard') }}" 
               class="glass glass-hover p-4 rounded-lg text-center hover:scale-105 transition-all">
                <x-icon name="chart" class="w-6 h-6 mx-auto mb-2 text-primary" />
                <p class="text-xs font-semibold">Laporan</p>
            </a>
        </div>
    </div>
</div>
@endsection

