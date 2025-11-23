@extends('layouts.app')

@section('title', 'Keranjang - Ebrystoree')

@section('content')
<div class="container mx-auto px-4 py-8 sm:py-12">
    <h1 class="text-3xl sm:text-4xl font-bold mb-6 sm:mb-8">Keranjang Belanja</h1>
    
    @if(count($cartItems) > 0)
        <div class="space-y-4">
            @foreach($cartItems as $item)
                <div class="glass p-4 sm:p-6 rounded-lg">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Image/Icon -->
                        <div class="flex-shrink-0">
                            @if($item['type'] === 'product')
                                @if($item['data']->image)
                                    <img src="{{ asset('storage/' . $item['data']->image) }}" 
                                         alt="{{ $item['data']->title }}" 
                                         class="w-full sm:w-24 h-24 object-cover rounded-lg">
                                @else
                                    <div class="w-full sm:w-24 h-24 bg-white/5 rounded-lg flex items-center justify-center">
                                        <x-icon name="package" class="w-8 h-8 text-white/40" />
                                    </div>
                                @endif
                            @else
                                <div class="w-full sm:w-24 h-24 bg-white/5 rounded-lg flex items-center justify-center">
                                    <x-icon name="file-text" class="w-8 h-8 text-white/40" />
                                </div>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl font-semibold mb-2">{{ $item['data']->title }}</h3>
                            <p class="text-white/60 text-sm mb-3 line-clamp-2">{{ $item['data']->description }}</p>
                            
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <span class="text-xl sm:text-2xl font-bold text-primary">
                                        Rp {{ number_format($item['data']->price, 0, ',', '.') }}
                                    </span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-1 bg-white/10 rounded text-xs">
                                            {{ $item['type'] === 'product' ? 'Produk Digital' : 'Jasa Joki Tugas' }}
                                        </span>
                                        @if($item['type'] === 'product' && $item['data']->isInStock())
                                            <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">
                                                Stok: {{ $item['data']->stock }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    @auth
                                        <a href="{{ $item['type'] === 'product' ? route('products.show', $item['data']->id) : route('services.show', $item['data']->id) }}" 
                                           class="px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm font-semibold">
                                            Checkout
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm font-semibold">
                                            Login untuk Checkout
                                        </a>
                                    @endauth
                                    <form method="POST" action="{{ route('cart.remove') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="type" value="{{ $item['type'] }}">
                                        <input type="hidden" name="id" value="{{ $item['data']->id }}">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-colors text-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Summary & Checkout -->
        <div class="mt-6 glass p-6 rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-white/60 text-sm mb-1">Total Items</p>
                    <p class="text-2xl sm:text-3xl font-bold text-primary">
                        Rp {{ number_format(collect($cartItems)->sum(function($item) { return $item['data']->price; }), 0, ',', '.') }}
                    </p>
                    <p class="text-white/60 text-xs mt-1">{{ count($cartItems) }} item</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-white/10 hover:bg-white/20 rounded-lg transition-colors text-sm">
                            Kosongkan Keranjang
                        </button>
                    </form>
                    
                    <a href="{{ route('products.index') }}" 
                       class="w-full sm:w-auto px-6 py-3 bg-primary/50 hover:bg-primary/70 rounded-lg transition-colors text-center font-semibold">
                        Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="glass p-6 rounded-lg">
            <div class="text-center py-12">
                <x-icon name="shopping-bag" class="w-16 h-16 text-white/20 mx-auto mb-4" />
                <p class="text-white/60 text-lg mb-4">Keranjang Anda kosong.</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @endif
</div>
@endsection





