@extends('layouts.user')

@section('title', 'Beri Rating - Ebrystoree')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('orders.show', $order) }}" class="text-primary hover:underline flex items-center space-x-2 touch-target text-sm sm:text-base">
                <x-icon name="arrow-left" class="w-4 h-4 sm:w-5 sm:h-5" />
                <span>Kembali ke Detail Pesanan</span>
            </a>
        </div>

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Beri Rating</h1>
            <p class="text-white/60 text-sm sm:text-base">Bagikan pengalaman Anda dengan produk/jasa ini</p>
        </div>

        <!-- Order Info Card -->
        <div class="glass p-4 sm:p-6 rounded-lg mb-6 sm:mb-8 border border-white/10">
            <div class="flex items-start gap-4">
                @if($order->product)
                    @if($order->product->image && $order->product->image_url)
                        <img src="{{ $order->product->image_url }}" 
                             alt="{{ $order->product->title }}" 
                             class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center flex-shrink-0">
                            <x-icon name="package" class="w-8 h-8 sm:w-10 sm:h-10 text-primary" />
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg sm:text-xl mb-1">{{ $order->product->title }}</h3>
                        <p class="text-white/60 text-sm">Order #{{ $order->order_number }}</p>
                        <p class="text-white/60 text-sm">Selesai pada {{ $order->completed_at->format('d M Y, H:i') }}</p>
                    </div>
                @elseif($order->service)
                    @if($order->service->image && $order->service->image_url)
                        <img src="{{ $order->service->image_url }}" 
                             alt="{{ $order->service->title }}" 
                             class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-lg bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center flex-shrink-0">
                            <x-icon name="game" class="w-8 h-8 sm:w-10 sm:h-10 text-primary" />
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg sm:text-xl mb-1">{{ $order->service->title }}</h3>
                        <p class="text-white/60 text-sm">Order #{{ $order->order_number }}</p>
                        <p class="text-white/60 text-sm">Selesai pada {{ $order->completed_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rating Form -->
        <form method="POST" action="{{ route('ratings.store') }}" class="glass p-4 sm:p-6 rounded-lg border border-white/10">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <!-- Star Rating -->
            <div class="mb-6 sm:mb-8">
                <label class="block text-sm sm:text-base font-semibold mb-3 sm:mb-4">Rating *</label>
                <div class="flex items-center gap-2 sm:gap-3" 
                     x-data="{ rating: {{ old('rating', 0) }}, hoverRating: 0 }"
                     @mouseleave="hoverRating = 0">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            @click="rating = {{ $i }}; $refs.ratingInput.value = {{ $i }}"
                            @mouseenter="hoverRating = {{ $i }}"
                            class="focus:outline-none transition-transform hover:scale-110 active:scale-95 touch-target">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 transition-colors"
                             x-bind:class="(hoverRating >= {{ $i }} || rating >= {{ $i }}) ? 'text-yellow-400 fill-current' : 'text-white/20'"
                             fill="currentColor" 
                             viewBox="0 0 24 24" 
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </button>
                    @endfor
                    <input type="hidden" 
                           name="rating" 
                           x-ref="ratingInput"
                           value="{{ old('rating', 0) }}"
                           required>
                </div>
                @error('rating')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
                <p class="text-white/60 text-xs sm:text-sm mt-2">Klik bintang untuk memberikan rating (1-5)</p>
            </div>

            <!-- Comment -->
            <div class="mb-6 sm:mb-8">
                <label for="comment" class="block text-sm sm:text-base font-semibold mb-2">
                    Ulasan (Opsional)
                </label>
                <textarea name="comment" 
                          id="comment" 
                          rows="5"
                          maxlength="1000"
                          placeholder="Bagikan pengalaman Anda dengan produk/jasa ini..."
                          class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 focus:outline-none focus:border-primary focus:bg-white/10 text-white placeholder-white/40 resize-none text-sm sm:text-base"
                          x-data="{ charCount: {{ strlen(old('comment', '')) }} }"
                          @input="charCount = $event.target.value.length">{{ old('comment') }}</textarea>
                <div class="flex justify-between items-center mt-2">
                    <p class="text-white/60 text-xs sm:text-sm">Maksimal 1000 karakter</p>
                    <p class="text-white/60 text-xs sm:text-sm" x-text="charCount + '/1000'"></p>
                </div>
                @error('comment')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <button type="submit" 
                        class="flex-1 sm:flex-none px-6 sm:px-8 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-colors text-base sm:text-sm touch-target flex items-center justify-center gap-2">
                    <x-icon name="star" class="w-5 h-5" />
                    <span>Kirim Rating</span>
                </button>
                <a href="{{ route('orders.show', $order) }}" 
                   class="flex-1 sm:flex-none px-6 sm:px-8 py-3 glass glass-hover rounded-lg text-center font-semibold transition-colors text-base sm:text-sm touch-target">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

