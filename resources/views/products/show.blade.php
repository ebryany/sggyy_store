@extends('layouts.app')

@section('title', $product->title . ' - Ebrystoree')

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-br from-primary/10 via-transparent to-transparent py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 text-sm text-white/60">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Produk</a>
                <span class="mx-2">/</span>
                <span class="text-white/90">{{ Str::limit($product->title, 30) }}</span>
            </nav>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                <!-- Image Section -->
                <div class="relative">
                    @if($product->image)
                    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->title }}" 
                             class="w-full h-64 sm:h-80 lg:h-96 object-cover">
                    </div>
                    @else
                    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 h-64 sm:h-80 lg:h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-primary/20 flex items-center justify-center mx-auto mb-4 border border-primary/30">
                                <x-icon name="package" class="w-12 h-12 sm:w-14 sm:h-14 text-primary" />
                            </div>
                            <p class="text-primary font-bold text-xl sm:text-2xl uppercase tracking-wider">
                                {{ Str::limit($product->title, 3) }}
                            </p>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Stock Badge -->
                    @if(!$product->isInStock())
                    <div class="absolute top-4 right-4 px-4 py-2 bg-red-500/90 backdrop-blur-sm rounded-lg text-sm font-semibold text-white border border-red-400/50">
                        Stok Habis
                    </div>
                    @endif
                </div>
                
                <!-- Info Section -->
                <div class="space-y-6">
                    <!-- Title & Price -->
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 text-white leading-tight">
                            {{ $product->title }}
                        </h1>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl sm:text-4xl lg:text-5xl font-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($product->ratings->count() > 0)
                            <div class="flex items-center gap-2">
                                <div class="flex items-center">
                                    <x-icon name="star" class="w-5 h-5 text-yellow-400 fill-yellow-400" />
                                    <span class="font-bold text-lg ml-1">{{ number_format($product->averageRating(), 1) }}</span>
                                </div>
                                <span class="text-white/60 text-sm">({{ $product->ratings->count() }} ulasan)</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-6">
                            <span class="px-4 py-2 rounded-lg bg-white/5 border border-white/10 flex items-center gap-2 text-sm">
                                <x-icon name="tag" class="w-4 h-4 text-primary" />
                                <span>{{ $product->category }}</span>
                            </span>
                            @if($product->isInStock())
                            <span class="px-4 py-2 rounded-lg bg-green-500/20 text-green-400 border border-green-500/30 text-sm font-medium">Stok: {{ $product->stock }}</span>
                            @else
                            <span class="px-4 py-2 rounded-lg bg-red-500/20 text-red-400 border border-red-500/30 text-sm font-medium">Stok Habis</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="p-5 sm:p-6 rounded-xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 mb-6">
                        <h2 class="text-lg font-semibold mb-3 text-white">Deskripsi</h2>
                        <p class="text-white/80 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                            {{ $product->description }}
                        </p>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <x-icon name="eye" class="w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5" />
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1">{{ number_format($product->views_count) }}</div>
                            <div class="text-xs sm:text-sm text-white/60">Dilihat</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <x-icon name="shopping-bag" class="w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5" />
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1">{{ number_format($product->sold_count) }}</div>
                            <div class="text-xs sm:text-sm text-white/60">Terjual</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <x-icon name="shield" class="w-5 h-5 sm:w-6 sm:h-6 text-primary mx-auto mb-2.5" />
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1">{{ $product->warranty_days }}</div>
                            <div class="text-xs sm:text-sm text-white/60">Garansi</div>
                        </div>
                        <div class="p-4 sm:p-5 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30 text-center">
                            <x-icon name="star" class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400 mx-auto mb-2.5" />
                            <div class="text-xl sm:text-2xl font-bold text-white mb-1">
                                {{ $product->ratings->count() > 0 ? number_format($product->averageRating(), 1) : '-' }}
                            </div>
                            <div class="text-xs sm:text-sm text-white/60">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Left Column: Order Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Form Card -->
                @if($product->isInStock())
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-white">Beli Produk Ini</h2>
                    
                    <form method="POST" action="{{ route('checkout.store') }}" 
                          x-data="{ loading: false, paymentMethod: 'wallet' }"
                          @submit.prevent="
                              loading = true;
                              fetch($el.action, {
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/json',
                                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                      'Accept': 'application/json'
                                  },
                                  body: JSON.stringify({
                                      type: 'product',
                                      product_id: {{ $product->id }},
                                      payment_method: paymentMethod
                                  })
                              })
                              .then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Pesanan berhasil dibuat', type: 'success' } }));
                                      setTimeout(() => window.location.href = data.redirect || '{{ route('orders.index') }}', 1000);
                                  } else {
                                      window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || data.error || 'Terjadi kesalahan', type: 'error' } }));
                                      loading = false;
                                  }
                              })
                              .catch(error => {
                                  window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Terjadi kesalahan. Silakan coba lagi.', type: 'error' } }));
                                  loading = false;
                              });
                          ">
                        @csrf
                        <input type="hidden" name="type" value="product">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-3 text-white">Metode Pembayaran</label>
                            <div class="space-y-3">
                                @if(isset($featureFlags) && $featureFlags['enable_wallet'])
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'wallet' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="wallet" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <x-icon name="dollar" class="w-5 h-5 text-primary" />
                                            Saldo Wallet
                                        </div>
                                        <div class="text-xs text-white/60">Bayar langsung dari saldo</div>
                                    </div>
                                </label>
                                @endif
                                @if(isset($featureFlags) && $featureFlags['enable_bank_transfer'])
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'bank_transfer' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <x-icon name="bank" class="w-5 h-5 text-primary" />
                                            Transfer Bank
                                        </div>
                                        <div class="text-xs text-white/60">Upload bukti transfer</div>
                                    </div>
                                </label>
                                @endif
                                @if(isset($featureFlags) && $featureFlags['enable_qris'])
                                <label class="flex items-center p-4 rounded-xl bg-white/5 border-2 cursor-pointer transition-all"
                                       :class="paymentMethod === 'qris' ? 'border-primary bg-primary/10' : 'border-white/10 hover:border-white/20'">
                                    <input type="radio" name="payment_method" value="qris" x-model="paymentMethod" class="mr-3 accent-primary">
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2 text-white mb-1">
                                            <x-icon name="mobile" class="w-5 h-5 text-primary" />
                                            QRIS
                                        </div>
                                        <div class="text-xs text-white/60">Scan QR code untuk bayar</div>
                                    </div>
                                </label>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Bank Transfer Info -->
                        <div x-show="paymentMethod === 'bank_transfer'" 
                             x-transition
                             class="mb-6 p-5 rounded-xl bg-blue-500/10 border-2 border-blue-500/30">
                            @if(isset($bankAccountInfo) && ($bankAccountInfo['bank_name'] || $bankAccountInfo['bank_account_number']))
                            <h3 class="font-semibold mb-4 text-blue-400 flex items-center gap-2">
                                <x-icon name="bank" class="w-5 h-5" />
                                Transfer ke Rekening Berikut
                            </h3>
                            <div class="space-y-2 text-sm">
                                @if($bankAccountInfo['bank_name'])
                                <div class="flex justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60">Bank:</span>
                                    <span class="font-semibold text-white">{{ $bankAccountInfo['bank_name'] }}</span>
                                </div>
                                @endif
                                @if($bankAccountInfo['bank_account_number'])
                                <div class="flex justify-between py-2 border-b border-white/10">
                                    <span class="text-white/60">No. Rekening:</span>
                                    <span class="font-semibold font-mono text-white">{{ $bankAccountInfo['bank_account_number'] }}</span>
                                </div>
                                @endif
                                @if($bankAccountInfo['bank_account_name'])
                                <div class="flex justify-between py-2">
                                    <span class="text-white/60">Atas Nama:</span>
                                    <span class="font-semibold text-white">{{ $bankAccountInfo['bank_account_name'] }}</span>
                                </div>
                                @endif
                            </div>
                            <p class="text-xs text-white/60 mt-4 flex items-center gap-1">
                                <x-icon name="alert" class="w-3 h-3" />
                                Upload bukti transfer setelah checkout
                            </p>
                            @else
                            <p class="text-yellow-400 text-sm flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                Informasi rekening bank belum dikonfigurasi. Silakan hubungi admin.
                            </p>
                            @endif
                        </div>
                        
                        <!-- QRIS Info -->
                        <div x-show="paymentMethod === 'qris'" 
                             x-transition
                             class="mb-6 p-5 rounded-xl bg-green-500/10 border-2 border-green-500/30">
                            @if(isset($bankAccountInfo) && $bankAccountInfo['qris_code'])
                            <h3 class="font-semibold mb-4 text-green-400 flex items-center gap-2">
                                <x-icon name="mobile" class="w-5 h-5" />
                                Scan QRIS Berikut
                            </h3>
                            @if(filter_var($bankAccountInfo['qris_code'], FILTER_VALIDATE_URL))
                            <img src="{{ $bankAccountInfo['qris_code'] }}" 
                                 alt="QRIS Code" 
                                 class="w-full max-w-xs mx-auto rounded-lg mb-4">
                            @elseif(str_starts_with($bankAccountInfo['qris_code'], 'data:image'))
                            <img src="{{ $bankAccountInfo['qris_code'] }}" 
                                 alt="QRIS Code" 
                                 class="w-full max-w-xs mx-auto rounded-lg mb-4">
                            @else
                            <div class="bg-white p-4 rounded-lg mb-4 flex justify-center">
                                <div id="qris-code-product" class="w-full max-w-xs"></div>
                            </div>
                            <script>
                                document.getElementById('qris-code-product').innerHTML = '<img src="data:image/png;base64,{{ $bankAccountInfo['qris_code'] }}" alt="QRIS" class="w-full">';
                            </script>
                            @endif
                            <p class="text-xs text-white/60 mt-4 flex items-center gap-1">
                                <x-icon name="alert" class="w-3 h-3" />
                                Upload bukti pembayaran setelah checkout
                            </p>
                            @else
                            <p class="text-yellow-400 text-sm flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                QRIS code belum dikonfigurasi. Silakan hubungi admin.
                            </p>
                            @endif
                        </div>
                        
                        <button type="submit" 
                                :disabled="loading"
                                class="w-full px-6 py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 text-lg">
                            <span x-show="!loading" class="flex items-center justify-center gap-2">
                                <x-icon name="shopping-cart" class="w-5 h-5" />
                                Beli Sekarang
                            </span>
                            <span x-show="loading" class="flex items-center justify-center">
                                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </form>
                </div>
                @else
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8 text-center">
                    <x-icon name="x" class="w-16 h-16 text-white/40 mx-auto mb-4" />
                    <h3 class="text-xl font-semibold mb-2 text-white">Produk Tidak Tersedia</h3>
                    <p class="text-white/60">Stok produk ini sedang habis.</p>
                </div>
                @endif
                
                <!-- Reviews Section -->
                @if($product->ratings->count() > 0)
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6 sm:p-8">
                    <h2 class="text-2xl font-bold mb-6 text-white">Ulasan ({{ $product->ratings->count() }})</h2>
                    <div class="space-y-6">
                        @foreach($product->ratings as $rating)
                        <div class="pb-6 border-b border-white/10 last:border-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                <img src="{{ $rating->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($rating->user->name) }}" 
                                     alt="{{ $rating->user->name }}" 
                                     class="w-12 h-12 rounded-full border-2 border-white/10">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-white">{{ $rating->user->name }}</p>
                                            <div class="flex items-center gap-1 mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                <x-icon name="star" class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400 fill-yellow-400' : 'text-white/20' }}" />
                                                @endfor
                                            </div>
                                        </div>
                                        <span class="text-white/60 text-sm">{{ $rating->created_at->format('d M Y') }}</span>
                                    </div>
                                    @if($rating->comment)
                                    <p class="text-white/70 mt-2 leading-relaxed">{{ $rating->comment }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Right Column: Seller Info -->
            <div class="space-y-6">
                <!-- Seller Card -->
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6">
                    <h3 class="font-semibold mb-5 text-white text-lg">Dijual oleh</h3>
                    
                    @if($product->user->store_slug)
                    <a href="{{ route('store.show', $product->user->store_slug) }}" class="block group">
                    @endif
                        <div class="flex items-start gap-4 mb-5">
                            <div class="relative flex-shrink-0">
                                <img src="{{ $product->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->user->name) }}" 
                                     alt="{{ $product->user->name }}" 
                                     class="w-16 h-16 rounded-full border-2 border-white/10 group-hover:border-primary transition-colors">
                                @if($product->user->updated_at && $product->user->updated_at->gt(now()->subMinutes(5)))
                                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-dark rounded-full"></span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-lg truncate group-hover:text-primary transition-colors">{{ $product->user->name }}</p>
                                    @if($product->user->isSeller() || $product->user->isAdmin())
                                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                </div>
                                @php
                                    $sellerRating = $product->user->products()->whereHas('ratings')->with('ratings')->get()->pluck('ratings')->flatten();
                                    $avgRating = $sellerRating->avg('rating') ?? 0;
                                    $totalSold = $product->user->products()->sum('sold_count');
                                @endphp
                                @if($avgRating > 0)
                                <div class="flex items-center gap-2 text-sm mb-2">
                                    <span class="font-semibold text-yellow-400">{{ number_format($avgRating, 1) }}</span>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endfor
                                    </div>
                                    <span class="text-white/60">({{ $totalSold }} terjual)</span>
                                </div>
                                @endif
                                @if($product->user->store_slug)
                                <p class="text-xs text-white/40 group-hover:text-primary/60 transition-colors">Klik untuk melihat toko</p>
                                @endif
                            </div>
                        </div>
                    @if($product->user->store_slug)
                    </a>
                    @endif
                    
                    <!-- Chat Button -->
                    @auth
                    @if(auth()->id() !== $product->user_id)
                    <a href="{{ route('chat.show', $product->user->id) }}" class="block w-full px-4 py-3 rounded-lg border-2 border-green-500/30 bg-green-500/20 text-green-400 hover:bg-green-500/30 hover:border-green-500/50 transition-all font-semibold mb-5 flex items-center justify-center gap-2">
                        <x-icon name="chat" class="w-5 h-5" />
                        Chat Sekarang
                    </a>
                    @endif
                    @endauth
                    
                    <!-- Seller Stats -->
                    <div class="space-y-3 text-sm border-t border-white/10 pt-5">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60">Produk</span>
                            <span class="font-semibold text-white">{{ $product->user->products()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60">Bergabung</span>
                            <span class="font-semibold text-white">{{ $product->user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    @if($product->user->isSeller() && $product->user->store_slug)
                    <div class="mt-5 pt-5 border-t border-white/10">
                        <a href="{{ route('store.show', $product->user->store_slug) }}" class="text-primary hover:text-primary-dark text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                            Kunjungi Toko
                        </a>
                    </div>
                    @endif
                </div>
                
                <!-- Edit/Delete Actions -->
                @if(auth()->check() && (auth()->id() === $product->user_id || auth()->user()->isAdmin()))
                <div class="rounded-2xl bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 p-6">
                    <h3 class="font-semibold mb-4 text-white">Kelola Produk</h3>
                    <div class="space-y-3">
                        <a href="{{ route('seller.products.edit', $product) }}" 
                           class="block w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:border-primary/30 transition-all text-center font-semibold flex items-center justify-center gap-2">
                            <x-icon name="paint" class="w-5 h-5" />
                            Edit Produk
                        </a>
                        <button type="button"
                                onclick="
                                    const modal = document.getElementById('delete-product-modal');
                                    if (modal) {
                                        modal.style.display = 'flex';
                                        document.body.style.overflow = 'hidden';
                                    }
                                "
                                class="w-full px-4 py-3 rounded-lg bg-red-500/20 border border-red-500/30 hover:bg-red-500/30 hover:border-red-500/50 transition-all text-red-400 font-semibold flex items-center justify-center gap-2">
                            <x-icon name="x" class="w-5 h-5" />
                            Hapus Produk
                        </button>
                        
                        <x-confirm-modal 
                            id="delete-product-modal"
                            title="Hapus Produk"
                            message="Yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan."
                            confirm-text="Ya, Hapus"
                            cancel-text="Batal"
                            type="danger" />
                        
                        <form id="delete-product-form" method="POST" action="{{ route('seller.products.destroy', $product) }}" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const confirmBtn = document.getElementById('delete-product-modal-confirm-btn');
                                const modal = document.getElementById('delete-product-modal');
                                const form = document.getElementById('delete-product-form');
                                
                                if (confirmBtn && modal && form) {
                                    confirmBtn.addEventListener('click', function() {
                                        modal.style.display = 'none';
                                        document.body.style.overflow = '';
                                        form.submit();
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
