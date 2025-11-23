@extends('layouts.app')

@section('title', 'Home - Ebrystoree')

@section('content')
@php
    $featuredItems = \App\Models\FeaturedItem::with(['product', 'service'])
        ->active()
        ->ordered()
        ->get();
@endphp

@php
    // Get popular products (top sellers or most viewed)
    $popularProducts = \App\Models\Product::where('is_active', true)
        ->where('is_draft', false)
        ->with(['user.sellerVerification', 'user.ratings', 'ratings'])
        ->orderBy('sold_count', 'desc')
        ->orderBy('views_count', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(8)
        ->get();
@endphp

@php
    $settingsService = app(\App\Services\SettingsService::class);
    $bannerSettings = $settingsService->getBannerSettings();
@endphp

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-16">
    @if($bannerSettings['banner_enabled'] && $bannerSettings['banner_image_url'])
    <!-- Banner Selamat Datang Section -->
    <section class="mb-12 sm:mb-16 lg:mb-20">
        <div class="relative overflow-hidden rounded-2xl lg:rounded-3xl shadow-2xl group">
            <!-- Background Image -->
            <div class="relative h-64 sm:h-80 lg:h-96">
                <img src="{{ $bannerSettings['banner_image_url'] }}" 
                     alt="{{ $bannerSettings['banner_title'] }}"
                     class="w-full h-full object-cover">
                <!-- Overlay -->
                @php
                    $opacity = $bannerSettings['banner_overlay_opacity'] ?? 0.4;
                    $opacity1 = (int)($opacity * 100);
                    $opacity2 = (int)($opacity * 80);
                    $opacity3 = (int)($opacity * 60);
                @endphp
                <div class="absolute inset-0" 
                     style="background: linear-gradient(to right, rgba(0, 0, 0, {{ $opacity }}), rgba(0, 0, 0, {{ $opacity * 0.8 }}), rgba(0, 0, 0, {{ $opacity * 0.6 }}));"></div>
                
                <!-- Content -->
                <div class="absolute inset-0 flex items-center">
                    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-2xl">
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-lg">
                                {{ $bannerSettings['banner_title'] }}
                            </h1>
                            @if($bannerSettings['banner_subtitle'])
                            <p class="text-lg sm:text-xl lg:text-2xl text-white/90 mb-6 drop-shadow-md">
                                {{ $bannerSettings['banner_subtitle'] }}
                            </p>
                            @endif
                            @if($bannerSettings['banner_button_text'] && $bannerSettings['banner_button_link'])
                            <a href="{{ $bannerSettings['banner_button_link'] }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 sm:px-8 sm:py-4 bg-primary hover:bg-primary-dark text-white font-semibold rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105">
                                <span>{{ $bannerSettings['banner_button_text'] }}</span>
                                <x-icon name="arrow-right" class="w-5 h-5" />
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if($featuredItems->count() > 0)
    <!-- Featured Section -->
    <section class="mb-12 sm:mb-16">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8 text-center">Featured Promosi</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach($featuredItems as $item)
            @php
                $product = $item->type === 'product' ? $item->product : null;
                $service = $item->type === 'service' ? $item->service : null;
                $itemData = $product ?? $service;
                $displayTitle = $item->title ?? $itemData->title ?? 'Featured Item';
                $displayDescription = $item->description ?? $itemData->description ?? '';
                $itemUrl = $item->type === 'product' 
                    ? route('products.show', $itemData->slug ?? $itemData->id)
                    : route('services.show', $itemData->slug ?? $itemData->id);
                
                // Get image
                $imageUrl = null;
                if ($product) {
                    $primaryImage = $product->primary_image;
                    if ($primaryImage) {
                        $imageUrl = str_starts_with($primaryImage, 'http') 
                            ? $primaryImage 
                            : asset('storage/' . $primaryImage);
                    }
                } elseif ($service && $service->image) {
                    $imageUrl = str_starts_with($service->image, 'http') 
                        ? $service->image 
                        : asset('storage/' . $service->image);
                }
    @endphp
            
            <a href="{{ $itemUrl }}" class="group block">
                <div class="relative overflow-hidden rounded-lg shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] min-h-[300px] sm:min-h-[350px]">
                    <!-- Background Image -->
                    @if($imageUrl)
                    <div class="absolute inset-0">
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $displayTitle }}"
                             class="w-full h-full object-cover">
                        <!-- Dark Overlay for better text readability -->
                        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/60 to-black/80"></div>
        </div>
                    @else
                    <!-- Fallback background color if no image -->
                    <div class="absolute inset-0" 
                         style="background: {{ $item->main_bg_color ?? '#000000' }}"></div>
                    @endif
                    
                    <!-- Header -->
                    <div class="relative px-4 py-2 text-center text-white font-semibold text-sm z-10" 
                         style="background-color: {{ $item->header_bg_color ?? '#8B4513' }}">
                        FEATURED
        </div>
        
                    <!-- Banner -->
                    @if($item->banner_bg_color)
                    <div class="relative px-4 py-2 text-center text-white font-semibold text-xs sm:text-sm z-10" 
                         style="background-color: {{ $item->banner_bg_color }}">
                        {{ $displayTitle }}
                    </div>
                    @endif
                    
                    <!-- Main Content -->
                    <div class="relative p-6 sm:p-8 h-full flex flex-col justify-between z-10" 
                         style="color: {{ $item->main_text_color ?? '#FFFFFF' }}">
                        <div>
                            <!-- Large Text -->
                            <div class="mb-4">
                                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight drop-shadow-lg" 
                                    style="color: {{ $item->accent_color ?? '#FCD34D' }}">
                                    {{ strtoupper($displayTitle) }}
                                </h3>
        </div>
        
                            <!-- Description -->
                            @if($displayDescription)
                            <p class="text-sm sm:text-base mb-4 opacity-90 drop-shadow-md">{{ Str::limit($displayDescription, 100) }}</p>
                            @endif
                            
                            <!-- Features List -->
                            @if($item->features && count($item->features) > 0)
                            <ul class="space-y-2 mb-4">
                                @foreach($item->features as $feature)
                                <li class="flex items-center gap-2 text-sm sm:text-base drop-shadow-md">
                                    <x-icon name="check" class="w-4 h-4 text-green-400 flex-shrink-0" />
                                    <span class="font-medium">{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
        </div>
        
                        <!-- Footer Text -->
                        @if($item->footer_text)
                        <div class="mt-4 pt-4 border-t border-white/30">
                            <p class="text-sm font-semibold drop-shadow-md" style="color: {{ $item->accent_color ?? '#FCD34D' }}">
                                {{ $item->footer_text }}
            </p>
        </div>
                        @endif
            </div>
            
                    <!-- Icon Overlay -->
                    <div class="absolute bottom-4 right-4 opacity-10 z-0">
                        <x-icon name="award" class="w-16 h-16 sm:w-20 sm:h-20" />
                    </div>
                </div>
            </a>
            @endforeach
            </div>
    </section>
    @endif

    <!-- Products Section -->
    @if($popularProducts->count() > 0)
    <section class="mb-12 sm:mb-16">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6 sm:mb-8">Produk Populer</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($popularProducts as $product)
            @php
                $imageUrl = null;
                $primaryImage = $product->primary_image;
                if ($primaryImage) {
                    $imageUrl = str_starts_with($primaryImage, 'http') 
                        ? $primaryImage 
                        : asset('storage/' . $primaryImage);
                } elseif ($product->image) {
                    $imageUrl = str_starts_with($product->image, 'http') 
                        ? $product->image 
                        : asset('storage/' . $product->image);
                }
                
                // Determine badges
                $isTopSeller = $product->sold_count >= 10; // Top seller if sold >= 10
                $isPremium = $product->price >= 100000 || $product->sale_price >= 100000; // Premium if price >= 100k
                
                // Format price
                $displayPrice = $product->sale_price ?? $product->price;
                $priceText = $displayPrice >= 1000 
                    ? number_format($displayPrice / 1000, 0, ',', '') . 'rb' 
                    : number_format($displayPrice, 0, ',', '.');
            @endphp
            
            @php
                $seller = $product->user;
                $sellerAvatar = $seller->avatar 
                    ? (str_starts_with($seller->avatar, 'http') ? $seller->avatar : asset('storage/' . $seller->avatar))
                    : null;
                $sellerName = $seller->store_name ?? $seller->name;
                // Get seller ratings from products/services they sold
                $sellerProducts = \App\Models\Product::where('user_id', $seller->id)->pluck('id');
                $sellerServices = \App\Models\Service::where('user_id', $seller->id)->pluck('id');
                $sellerRating = \App\Models\Rating::where(function($q) use ($sellerProducts, $sellerServices) {
                    $q->whereIn('product_id', $sellerProducts)
                      ->orWhereIn('service_id', $sellerServices);
                })->avg('rating') ?? 0;
                $sellerRatingCount = \App\Models\Rating::where(function($q) use ($sellerProducts, $sellerServices) {
                    $q->whereIn('product_id', $sellerProducts)
                      ->orWhereIn('service_id', $sellerServices);
                })->count();
                $isVerified = $seller->sellerVerification && $seller->sellerVerification->status === 'verified';
            @endphp
            
            <a href="{{ route('products.show', $product->slug ?: $product->id) }}" class="group block">
                <div class="relative bg-white/5 backdrop-blur-sm rounded-xl overflow-hidden border border-white/10 hover:border-primary/30 shadow-lg hover:shadow-2xl transition-all duration-300 hover:scale-[1.02] h-full flex flex-col">
                    <!-- Image Container -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-white/5 to-white/10">
                        @if($imageUrl)
                        <img src="{{ $imageUrl }}" 
                             alt="{{ $product->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5">
                            <x-icon name="package" class="w-16 h-16 text-white/30" />
                        </div>
                        @endif
                        
                        <!-- Badges Overlay (Top Left) -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2 z-20">
                            @if($isTopSeller)
                            <span class="px-2.5 py-1 bg-white/95 text-gray-900 text-xs font-bold rounded-md shadow-lg backdrop-blur-sm">
                                Top Seller
                            </span>
                            @endif
                            @if($isPremium)
                            <span class="px-2.5 py-1 bg-yellow-500 text-white text-xs font-bold rounded-md shadow-lg">
                                Premium
                            </span>
                            @endif
                            <!-- Kilat Badge -->
                            <span class="px-2.5 py-1 bg-primary/90 text-white text-xs font-semibold rounded-md shadow-lg backdrop-blur-sm">
                                Kilat
                            </span>
                        </div>
                        
                        <!-- Package Icon (Top Right) -->
                        <div class="absolute top-3 right-3 z-20">
                            <div class="w-10 h-10 rounded-lg bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center group-hover:bg-primary/20 group-hover:border-primary/40 transition-all duration-300">
                                <x-icon name="package" class="w-5 h-5 text-white/70 group-hover:text-primary transition-colors" />
                            </div>
                        </div>
                        
                        <!-- Seller Info Overlay (Hover) -->
                        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 z-30 flex items-center justify-center">
                            <div class="p-4 sm:p-6 text-center">
                                <!-- Seller Avatar -->
                                <div class="relative inline-block mb-4">
                                    @if($sellerAvatar)
                                    <img src="{{ $sellerAvatar }}" 
                                         alt="{{ $sellerName }}"
                                         class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-primary/50 shadow-lg">
                                    @else
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-primary/20 border-2 border-primary/50 flex items-center justify-center shadow-lg">
                                        <x-icon name="user" class="w-8 h-8 sm:w-10 sm:h-10 text-primary" />
                                    </div>
                                    @endif
                                    @if($isVerified)
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center shadow-lg">
                                        <x-icon name="check" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" />
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Seller Name -->
                                <h4 class="text-base sm:text-lg font-bold text-white mb-2 truncate max-w-[200px] mx-auto">
                                    {{ $sellerName }}
                                </h4>
                                
                                <!-- Rating -->
                                @if($sellerRatingCount > 0)
                                <div class="flex items-center justify-center gap-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($sellerRating))
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-white/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    @endif
                                    @endfor
                                    <span class="text-xs text-white/70 ml-1">({{ $sellerRatingCount }})</span>
                                </div>
                                @endif
                                
                                <!-- Location Flag (Indonesia) -->
                                <div class="flex items-center justify-center gap-1.5 text-xs text-white/70">
                                    <div class="w-4 h-3 bg-red-500 relative overflow-hidden rounded-sm shadow-sm">
                                        <div class="absolute top-0 left-0 w-full h-1/2 bg-red-500"></div>
                                        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-white"></div>
                                    </div>
                                    <span>Indonesia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Area -->
                    <div class="flex-1 p-4 sm:p-5 flex flex-col">
                        <!-- Title -->
                        <h3 class="text-base sm:text-lg font-bold mb-2 line-clamp-2 text-white group-hover:text-primary transition-colors">
                            {{ $product->title }}
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-sm text-white/70 line-clamp-3 leading-relaxed mb-3 flex-1">
                            {{ $product->short_description ?? Str::limit($product->description, 100) }}
                        </p>
                        
                        <!-- Footer: Price & Rating -->
                        <div class="flex items-center justify-between pt-3 border-t border-white/10">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-lg font-bold text-primary">
                                        Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </span>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-xs text-white/50 line-through">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @endif
                                </div>
                                @if($product->ratings->count() > 0)
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                    <span class="text-xs text-white/60">{{ number_format($product->averageRating(), 1) }}</span>
                                    <span class="text-xs text-white/40">({{ $product->ratings->count() }})</span>
                </div>
                                @endif
            </div>
            
                            <!-- Quick Action -->
                            <div class="flex items-center gap-2">
                                @if($product->isInStock())
                                <span class="text-xs text-green-400 font-medium">Stok: {{ $product->stock }}</span>
                                @else
                                <span class="text-xs text-red-400 font-medium">Habis</span>
                                @endif
                </div>
            </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection

