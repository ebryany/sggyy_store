@php
    // Get seller info
    $seller = null;
    if ($order->type === 'product' && $order->product) {
        $seller = $order->product->user;
    } elseif ($order->type === 'service' && $order->service) {
        $seller = $order->service->user;
    }
    
    // Get product/service image
    $itemImage = null;
    $itemImageUrl = null;
    $itemTitle = 'N/A';
    $itemCategory = null;
    
    if ($order->type === 'product' && $order->product) {
        $itemImage = $order->product->image;
        $itemImageUrl = $order->product->image_url;
        $itemTitle = $order->product->title;
        $itemCategory = $order->product->category;
    } elseif ($order->type === 'service' && $order->service) {
        $itemImage = $order->service->image;
        $itemImageUrl = $order->service->image_url;
        $itemTitle = $order->service->title;
        $itemCategory = 'Jasa';
    }
    
    // Determine available actions based on order status
    $canComplete = $order->status === 'processing' || $order->status === 'paid';
    $canRate = $order->canBeRated();
    $canDispute = $order->escrow && $order->escrow->isHolding() && !$order->is_disputed;
@endphp

<div class="glass p-4 sm:p-6 rounded-xl border border-white/10 hover:border-primary/30 transition-all">
    <!-- Seller Info & Status -->
    <div class="flex items-start justify-between mb-4 pb-4 border-b border-white/10">
        <div class="flex-1 min-w-0">
            @if($seller)
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center border border-primary/30 flex-shrink-0">
                    @if($seller->avatar)
                        <img src="{{ asset('storage/' . $seller->avatar) }}" alt="{{ $seller->name }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <x-icon name="user" class="w-5 h-5 text-primary" />
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm sm:text-base truncate">{{ $seller->name }}</p>
                    <p class="text-xs text-white/60">Seller</p>
                </div>
            </div>
            @endif
            
            <div class="flex items-center gap-2 flex-wrap">
                @include('components.order-status-badge', ['status' => $order->status])
                @if($order->payment && $order->payment->status === 'verified')
                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-500/20 border border-green-500/50 rounded text-xs text-green-400">
                    <x-icon name="check" class="w-3 h-3" />
                    <span>Terbayar</span>
                </span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-2 flex-shrink-0 ml-4">
            @if($seller)
            <a href="{{ route('chat.show', '@' . $seller->username) }}" 
               class="px-3 py-2 bg-primary/20 hover:bg-primary/30 text-primary rounded-lg transition-all text-sm font-semibold touch-target flex items-center gap-2 border border-primary/30">
                <x-icon name="message" class="w-4 h-4" />
                <span class="hidden sm:inline">Chat</span>
            </a>
            @endif
        </div>
    </div>
    
    <!-- Product/Service Info -->
    <div class="flex items-start gap-4 mb-4">
        @if($itemImageUrl)
        <img src="{{ $itemImageUrl }}" 
             alt="{{ $itemTitle }}" 
             class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg object-cover flex-shrink-0 border border-white/10">
        @else
        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 border border-white/10">
            @if($order->type === 'product')
                <x-icon name="package" class="w-8 h-8 text-white/40" />
            @else
                <x-icon name="target" class="w-8 h-8 text-white/40" />
            @endif
        </div>
        @endif
        
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-base sm:text-lg mb-1 line-clamp-2">{{ $itemTitle }}</h3>
            @if($itemCategory)
            <p class="text-xs sm:text-sm text-white/60 mb-2">{{ $itemCategory }}</p>
            @endif
            <p class="text-xs text-white/40 font-mono">{{ $order->order_number }}</p>
        </div>
        
        <div class="text-right flex-shrink-0 ml-2">
            <p class="text-lg sm:text-xl font-bold text-primary mb-1">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <p class="text-xs text-white/60">{{ $order->created_at->format('d M Y') }}</p>
        </div>
    </div>
    
    <!-- Total & Actions -->
    <div class="pt-4 border-t border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-white/60 mb-1">Total Pesanan</p>
                <p class="text-lg font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>
            @if($order->payment)
            <div class="text-right">
                <p class="text-xs text-white/60 mb-1">Metode Pembayaran</p>
                <p class="text-sm font-semibold">{{ $order->payment->getMethodDisplayName() }}</p>
            </div>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
            @if($canComplete && $order->status === 'processing')
            <form action="{{ route('orders.confirm', $order) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2.5 bg-primary hover:bg-primary-dark rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center gap-2 min-w-[140px]">
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Pesanan Selesai</span>
                </button>
            </form>
            @endif
            
            @if($canRate)
            <a href="{{ route('ratings.create', $order) }}" 
               class="px-4 py-2.5 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center gap-2 border border-yellow-500/30 min-w-[120px]">
                <x-icon name="star" class="w-4 h-4" />
                <span>Beri Rating</span>
            </a>
            @endif
            
            @if($canDispute)
            <a href="{{ route('disputes.create', $order) }}" 
               class="px-4 py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center gap-2 border border-red-500/30 min-w-[140px]">
                <x-icon name="alert" class="w-4 h-4" />
                <span>Ajukan Refund</span>
            </a>
            @endif
            
            @if($seller)
            <a href="{{ route('chat.show', '@' . $seller->username) }}" 
               class="px-4 py-2.5 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center gap-2 border border-white/10 min-w-[140px]">
                <x-icon name="message" class="w-4 h-4" />
                <span>Hubungi Penjual</span>
            </a>
            @endif
            
            <a href="{{ route('orders.show', $order) }}" 
               class="px-4 py-2.5 glass glass-hover rounded-lg transition-all text-sm font-semibold touch-target flex items-center justify-center gap-2 border border-white/10 min-w-[120px]">
                <x-icon name="eye" class="w-4 h-4" />
                <span>Lihat Detail</span>
            </a>
        </div>
    </div>
</div>

