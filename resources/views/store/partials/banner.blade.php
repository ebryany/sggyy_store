{{-- Store Banner --}}
<div class="relative w-full h-48 sm:h-64 lg:h-80 bg-gradient-to-br from-primary/20 to-purple-900/20 overflow-hidden">
    @if($store->store_banner)
        <img src="{{ asset('storage/' . $store->store_banner) }}" 
             alt="{{ $store->store_name ?? $store->name }}" 
             class="w-full h-full object-cover">
    @else
        {{-- Default gradient banner --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-purple-600 to-pink-600 opacity-30"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <svg class="w-32 h-32 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
    @endif
    
    {{-- Dark overlay for better text readability --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
</div>


