@extends('layouts.app')

@section('title', 'Transaksi - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">Riwayat Transaksi</h1>
    
    <!-- Filter -->
    <div class="glass p-3 sm:p-4 rounded-lg mb-4 sm:mb-6">
        <form method="GET" action="{{ route('orders.index') }}" class="space-y-4">
            <!-- Row 1: Search & Status -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari order number atau produk/jasa..." 
                       class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                
                <select name="status" 
                        class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="bg-dark text-white">Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }} class="bg-dark text-white">Paid</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }} class="bg-dark text-white">Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }} class="bg-dark text-white">Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }} class="bg-dark text-white">Cancelled</option>
                </select>

                <select name="type" 
                        class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Tipe</option>
                    <option value="product" {{ request('type') == 'product' ? 'selected' : '' }} class="bg-dark text-white">Produk</option>
                    <option value="service" {{ request('type') == 'service' ? 'selected' : '' }} class="bg-dark text-white">Jasa</option>
                </select>
            </div>

            <!-- Row 2: Date Range & Sort -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs text-white/60 mb-1">Tanggal Mulai</label>
                    <input type="date" 
                           name="date_from" 
                           value="{{ request('date_from') }}" 
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>
                
                <div>
                    <label class="block text-xs text-white/60 mb-1">Tanggal Akhir</label>
                    <input type="date" 
                           name="date_to" 
                           value="{{ request('date_to') }}" 
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Urutkan</label>
                    <select name="sort" class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }} class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }} class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                        <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }} class="bg-dark text-white">Status</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Actions & Per Page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="px-4 sm:px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-base sm:text-sm touch-target">
                        Terapkan Filter
                    </button>
                    
                    @if(request()->anyFilled(['search', 'status', 'type', 'date_from', 'date_to', 'sort', 'per_page']))
                    <a href="{{ route('orders.index') }}" class="px-4 sm:px-6 py-2 glass glass-hover rounded-lg text-center text-base sm:text-sm touch-target">
                        Reset
                    </a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <label class="text-xs text-white/60">Per Halaman:</label>
                    <select name="per_page" onchange="this.form.submit()" class="glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }} class="bg-dark text-white">10</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }} class="bg-dark text-white">15</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }} class="bg-dark text-white">20</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }} class="bg-dark text-white">30</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }} class="bg-dark text-white">50</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Orders Table -->
    <div class="glass p-4 sm:p-6 rounded-lg">
        @if($orders->count() > 0)
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Order Number</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Produk/Jasa</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Pembeli</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Total</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Payment</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Status</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Tanggal</th>
                        <th class="text-left py-3 px-4 text-white/60 text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr class="border-b border-white/5 hover:bg-white/5">
                        <td class="py-3 px-4 font-mono text-xs sm:text-sm">{{ $order->order_number }}</td>
                        <td class="py-3 px-4 text-sm">
                            @if($order->type === 'product' && $order->product)
                                <span class="truncate block max-w-xs">{{ $order->product->title }}</span>
                            @elseif($order->type === 'service' && $order->service)
                                <span class="truncate block max-w-xs">{{ $order->service->title }}</span>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="py-3 px-4 text-sm">
                            @if($order->user)
                                <span class="text-white/90">{{ $order->user->name }}</span>
                            @else
                                <span class="text-white/40">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-semibold text-sm">
                            @php
                                $total = $order->total;
                                $total = is_numeric($total) ? (float)$total : 0;
                            @endphp
                            @if($total > 0)
                                <span class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            @else
                                <span class="text-white/40">Rp 0</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($order->payment)
                                <span class="text-xs text-white/70 font-medium">{{ $order->payment->getMethodDisplayName() }}</span>
                            @else
                                <span class="text-xs text-white/40 italic">Belum ada</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($order->status)
                                @include('components.order-status-badge', ['status' => $order->status])
                            @else
                                <span class="text-xs text-white/40 italic">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-white/60 text-xs sm:text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <a href="{{ route('orders.show', $order) }}" 
                               class="text-primary hover:underline text-sm touch-target">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @foreach($orders as $order)
            <a href="{{ route('orders.show', $order) }}" class="block glass glass-hover p-4 rounded-lg touch-target">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="font-mono text-xs text-white/60 mb-1">{{ $order->order_number }}</p>
                        <p class="font-semibold text-sm truncate">
                            @if($order->type === 'product' && $order->product)
                                {{ $order->product->title }}
                            @elseif($order->type === 'service' && $order->service)
                                {{ $order->service->title }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="ml-2 flex-shrink-0 flex flex-col gap-2 items-end">
                        @include('components.order-status-badge', ['status' => $order->status])
                        @if($order->canBeRated())
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-500/20 border border-yellow-500/50 rounded text-xs text-yellow-400">
                            <x-icon name="star" class="w-3 h-3" />
                            <span>Rating</span>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <div>
                        <span class="font-bold text-primary text-base">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        @if($order->payment)
                            <p class="text-xs text-white/60 mt-1">{{ $order->payment->getMethodDisplayName() }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-xs text-white/60">{{ $order->created_at->format('d M Y') }}</span>
                        @if($order->canBeRated())
                        <a href="{{ route('ratings.create', $order) }}" 
                           class="text-yellow-400 hover:text-yellow-300 text-xs touch-target flex items-center gap-1">
                            <x-icon name="star" class="w-3 h-3" />
                            <span>Beri Rating</span>
                        </a>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-white/60 text-lg mb-4">Belum ada transaksi.</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                Mulai Belanja
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

