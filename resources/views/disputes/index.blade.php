@extends('layouts.app')

@section('title', 'Daftar Dispute - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-6xl">
    <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold mb-2">Daftar Dispute</h1>
        <p class="text-white/70">Semua dispute yang Anda buat atau terlibat</p>
    </div>

    @if($disputedOrders->isEmpty())
    <div class="glass p-12 rounded-xl border border-white/10 text-center">
        <x-icon name="check-circle" class="w-16 h-16 mx-auto mb-4 text-green-400" />
        <h3 class="text-xl font-semibold mb-2">Tidak Ada Dispute</h3>
        <p class="text-white/60 mb-6">Anda belum memiliki dispute aktif.</p>
        <a href="{{ route('orders.index') }}" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all">
            Lihat Pesanan
        </a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($disputedOrders as $order)
        <div class="glass p-4 sm:p-6 rounded-xl border border-orange-500/30 hover:border-orange-500/50 transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold">Pesanan #{{ $order->order_number }}</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/20 text-orange-400 border border-orange-500/30">
                            Dispute Aktif
                        </span>
                    </div>
                    
                    <div class="text-sm text-white/70 space-y-1 mb-3">
                        <div>
                            @if($order->product)
                                Produk: <strong>{{ $order->product->title }}</strong>
                            @elseif($order->service)
                                Jasa: <strong>{{ $order->service->title }}</strong>
                            @endif
                        </div>
                        <div>
                            Total: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                        </div>
                        @if($order->escrow)
                        <div>
                            Escrow: <strong>Rp {{ number_format($order->escrow->amount, 0, ',', '.') }}</strong>
                        </div>
                        <div>
                            Dispute dibuat: <strong>{{ $order->escrow->disputed_at->format('d M Y, H:i') }}</strong>
                        </div>
                        @endif
                    </div>
                    
                    @if($order->escrow && $order->escrow->dispute_reason)
                    <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                        <p class="text-sm text-white/80 line-clamp-2">
                            {{ Str::limit($order->escrow->dispute_reason, 150) }}
                        </p>
                    </div>
                    @endif
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2">
                    <a 
                        href="{{ route('orders.show', $order) }}" 
                        class="px-4 py-2 glass border border-white/20 rounded-lg hover:border-primary/50 transition-all text-center text-sm"
                    >
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="mt-6">
        {{ $disputedOrders->links() }}
    </div>
    @endif
</div>
@endsection

