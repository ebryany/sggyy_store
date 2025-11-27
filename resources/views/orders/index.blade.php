@extends('layouts.user')

@section('title', 'Pesanan Saya - Ebrystoree')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Pesanan Saya</h1>
        <p class="text-white/60 text-sm sm:text-base">Kelola dan lacak semua pesanan Anda</p>
    </div>
    
    <!-- Prominent Search Bar -->
    <div class="glass p-4 rounded-xl border border-white/10">
        <form method="GET" action="{{ route('orders.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <x-icon name="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-white/40" />
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan Nama Penjual, No. Pesanan atau Nama Produk" 
                       class="w-full glass border border-white/10 rounded-lg pl-12 pr-4 py-3 bg-white/5 focus:outline-none focus:border-primary focus:bg-white/10 text-base sm:text-sm touch-target">
            </div>
            <button type="submit" 
                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all text-sm font-semibold touch-target flex items-center gap-2 min-w-[120px] justify-center">
                <x-icon name="search" class="w-5 h-5" />
                <span class="hidden sm:inline">Cari</span>
            </button>
            @if(request()->filled('search'))
            <a href="{{ route('orders.index') }}" 
               class="px-4 py-3 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center border border-white/10">
                <x-icon name="x" class="w-5 h-5" />
            </a>
            @endif
        </form>
    </div>
    
    <!-- Tab Navigation -->
    <div class="glass p-2 rounded-xl border border-white/10">
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <a href="{{ route('orders.index') }}" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target {{ !request('status') ? 'bg-primary/20 text-primary border border-primary/30' : 'glass-hover text-white/70 hover:text-white border border-transparent' }}">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ !request('status') ? 'bg-primary/30 text-primary' : 'bg-white/10 text-white/60' }}">
                    {{ $orderStatusCounts['all'] ?? 0 }}
                </span>
            </a>
            
            <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target {{ request('status') == 'pending' ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent' }}">
                <x-icon name="clock" class="w-4 h-4" />
                <span>Belum Bayar</span>
                @if(($orderStatusCounts['pending'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ request('status') == 'pending' ? 'bg-yellow-500/30 text-yellow-300' : 'bg-white/10 text-white/60' }}">
                    {{ $orderStatusCounts['pending'] }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target {{ request('status') == 'processing' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent' }}">
                <x-icon name="refresh" class="w-4 h-4" />
                <span>Sedang Diproses</span>
                @if(($orderStatusCounts['processing'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ request('status') == 'processing' ? 'bg-blue-500/30 text-blue-300' : 'bg-white/10 text-white/60' }}">
                    {{ $orderStatusCounts['processing'] }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('orders.index', ['status' => 'completed']) }}" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target {{ request('status') == 'completed' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent' }}">
                <x-icon name="check" class="w-4 h-4" />
                <span>Selesai</span>
                @if(($orderStatusCounts['completed'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ request('status') == 'completed' ? 'bg-green-500/30 text-green-300' : 'bg-white/10 text-white/60' }}">
                    {{ $orderStatusCounts['completed'] }}
                </span>
                @endif
            </a>
            
            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" 
               class="flex-shrink-0 px-4 py-3 rounded-lg transition-all font-semibold text-sm flex items-center gap-2 touch-target {{ request('status') == 'cancelled' ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'glass-hover text-white/70 hover:text-white border border-transparent' }}">
                <x-icon name="x" class="w-4 h-4" />
                <span>Dibatalkan</span>
                @if(($orderStatusCounts['cancelled'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-lg text-xs font-bold {{ request('status') == 'cancelled' ? 'bg-red-500/30 text-red-300' : 'bg-white/10 text-white/60' }}">
                    {{ $orderStatusCounts['cancelled'] }}
                </span>
                @endif
            </a>
        </div>
    </div>
    
    <!-- Advanced Filters (Collapsible) -->
    <div x-data="{ showFilters: false }" class="glass p-4 rounded-xl border border-white/10">
        <button @click="showFilters = !showFilters" 
                class="w-full flex items-center justify-between touch-target">
            <span class="text-sm font-semibold text-white/70">Filter Lanjutan</span>
            <x-icon name="chevron-down" 
                    class="w-5 h-5 text-white/60 transition-transform"
                    x-bind:class="showFilters ? 'rotate-180' : ''" />
        </button>
        
        <div x-show="showFilters" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="mt-4 pt-4 border-t border-white/10">
            <form method="GET" action="{{ route('orders.index') }}" class="space-y-4">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs text-white/60 mb-2">Tipe</label>
                <select name="type" 
                                class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Tipe</option>
                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }} class="bg-dark text-white">Produk</option>
                    <option value="service" {{ request('type') == 'service' ? 'selected' : '' }} class="bg-dark text-white">Jasa</option>
                </select>
            </div>

                <div>
                        <label class="block text-xs text-white/60 mb-2">Tanggal Mulai</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}" 
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary text-sm touch-target">
                </div>
                
                <div>
                        <label class="block text-xs text-white/60 mb-2">Tanggal Akhir</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}" 
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary text-sm touch-target">
                </div>

                <div>
                        <label class="block text-xs text-white/60 mb-2">Urutkan</label>
                        <select name="sort" 
                                class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }} class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }} class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                    </select>
                </div>
            </div>

                <div class="flex items-center justify-between pt-2">
                <div class="flex items-center gap-2">
                    <label class="text-xs text-white/60">Per Halaman:</label>
                        <select name="per_page" 
                                onchange="this.form.submit()" 
                                class="glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }} class="bg-dark text-white">10</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }} class="bg-dark text-white">15</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }} class="bg-dark text-white">20</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }} class="bg-dark text-white">30</option>
                    </select>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="px-5 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-all text-sm font-semibold touch-target">
                            Terapkan
                        </button>
                        @if(request()->anyFilled(['type', 'date_from', 'date_to', 'sort', 'per_page']))
                        <a href="{{ route('orders.index', ['status' => request('status'), 'search' => request('search')]) }}" 
                           class="px-5 py-2.5 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target border border-white/10">
                            Reset
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Orders List -->
    @if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
            <x-order-card :order="$order" />
            @endforeach
        </div>
        
        <!-- Pagination -->
    <div class="flex justify-center pt-4">
            {{ $orders->links() }}
        </div>
        @else
    <!-- Empty State -->
    <div class="glass p-12 rounded-xl border border-white/10 text-center">
        <div class="flex justify-center mb-4">
            <div class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center border border-white/10">
                <x-icon name="shopping-bag" class="w-10 h-10 text-white/30" />
            </div>
        </div>
        <h3 class="text-xl font-bold mb-2">Belum ada pesanan</h3>
        <p class="text-white/60 mb-6 text-sm">Mulai berbelanja untuk melihat pesanan Anda di sini</p>
            <a href="{{ route('products.index') }}" 
           class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-all font-semibold touch-target">
                Mulai Belanja
            </a>
        </div>
        @endif
</div>
@endsection
