@extends('seller.layouts.dashboard')

@section('title', 'Analytics - Seller Dashboard - Ebrystoree')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Analytics</h1>
            <p class="text-white/60 text-sm sm:text-base">Analisis pendapatan dan tren pesanan Anda</p>
        </div>
        <a href="{{ route('seller.dashboard') }}" 
           class="px-4 py-2 glass hover:bg-white/10 rounded-xl font-semibold transition-all hover:scale-105 text-sm sm:text-base border border-white/20 flex items-center gap-2">
            <x-icon name="arrow-left" class="w-4 h-4" />
            <span>Kembali ke Dashboard</span>
        </a>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Revenue Chart -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 backdrop-blur-lg flex items-center justify-center border border-primary/30">
                        <x-icon name="trending-up" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Revenue</h2>
                        <p class="text-sm text-white/60">6 Bulan Terakhir</p>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-80 flex items-end justify-between gap-2 sm:gap-3 min-w-[400px] pb-4">
                @foreach($revenueChart as $data)
                <div class="flex-1 flex flex-col items-center group">
                    <div class="w-full bg-gradient-to-t from-primary via-primary/80 to-primary/60 rounded-t-xl mb-2 hover:from-primary hover:via-primary/90 hover:to-primary/80 transition-all duration-300 cursor-pointer relative" 
                         style="height: {{ $data['revenue'] > 0 ? max(40, ($data['revenue'] / max(array_column($revenueChart, 'revenue'))) * 100) : 0 }}%"
                         title="Rp {{ number_format($data['revenue'], 0, ',', '.') }}">
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-dark/90 px-2 py-1 rounded-lg text-xs font-semibold text-primary whitespace-nowrap pointer-events-none">
                            Rp {{ number_format($data['revenue'] / 1000, 0) }}k
                        </div>
                    </div>
                    <p class="text-xs sm:text-sm text-white/60 truncate w-full text-center font-medium">{{ $data['month'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Order Trend -->
        <div class="glass p-4 sm:p-6 lg:p-8 rounded-2xl border border-white/10 overflow-x-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 backdrop-blur-lg flex items-center justify-center border border-blue-500/30">
                        <x-icon name="chart" class="w-6 h-6 text-primary" />
                    </div>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold">Order Trend</h2>
                        <p class="text-sm text-white/60">30 Hari Terakhir</p>
                    </div>
                </div>
            </div>
            <div class="h-64 sm:h-80 flex items-end justify-between gap-1 sm:gap-2 min-w-[500px] pb-4">
                @foreach(array_slice($orderTrend, -14) as $data)
                <div class="flex-1 flex flex-col items-center group">
                    <div class="w-full bg-gradient-to-t from-blue-500 via-blue-400 to-blue-300 rounded-t-lg mb-2 hover:from-blue-400 hover:via-blue-300 hover:to-blue-200 transition-all duration-300 cursor-pointer relative" 
                         style="height: {{ $data['orders'] > 0 ? max(30, ($data['orders'] / max(array_column($orderTrend, 'orders'))) * 100) : 0 }}%"
                         title="{{ $data['orders'] }} orders">
                        <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-dark/90 px-2 py-1 rounded-lg text-xs font-semibold text-blue-400 whitespace-nowrap pointer-events-none">
                            {{ $data['orders'] }} orders
                        </div>
                    </div>
                    <p class="text-[10px] sm:text-xs text-white/60 transform -rotate-45 origin-bottom-left whitespace-nowrap" style="writing-mode: vertical-rl;">{{ \Carbon\Carbon::parse($data['date'])->format('d M') }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

