{{-- Store Header with Stats --}}
<div class="container mx-auto px-3 sm:px-4 -mt-16 sm:-mt-20 relative z-10">
    <div class="glass p-4 sm:p-6 rounded-lg">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
            {{-- Store Avatar --}}
            <div class="relative flex-shrink-0">
                <img src="{{ $store->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($store->store_name ?? $store->name) }}" 
                     alt="{{ $store->store_name ?? $store->name }}" 
                     class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-gray-900 shadow-lg">
            </div>
            
            {{-- Store Info --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">
                    {{ $store->store_name ?? $store->name }}
                </h1>
                
                {{-- Rating and Reviews --}}
                <div class="flex flex-wrap items-center gap-4 mb-3">
                    @if($stats['rating'] > 0)
                    <div class="flex items-center gap-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($stats['rating']))
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @elseif($i - 0.5 <= $stats['rating'])
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-{{ $i }}">
                                                <stop offset="50%" stop-color="currentColor"/>
                                                <stop offset="50%" stop-color="transparent" stop-opacity="1"/>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white/20" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="font-semibold">{{ number_format($stats['rating'], 1) }}</span>
                        <span class="text-white/60 text-sm">({{ $stats['reviews_count'] }} ulasan)</span>
                    </div>
                    @else
                    <div class="text-white/60 text-sm">Belum ada rating</div>
                    @endif
                    
                    <div class="text-white/60 text-sm">
                        Bergabung {{ $stats['member_since']->format('M Y') }}
                    </div>
                </div>
                
                {{-- Stats Grid --}}
                <div class="flex flex-wrap gap-4 sm:gap-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="font-semibold">{{ number_format($stats['followers_count']) }}</span>
                        <span class="text-white/60 text-sm">Pengikut</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">{{ number_format($stats['total_sold']) }}</span>
                        <span class="text-white/60 text-sm">Terjual</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span class="font-semibold">{{ $stats['total_products'] + $stats['total_services'] }}</span>
                        <span class="text-white/60 text-sm">Total Item</span>
                    </div>
                </div>
            </div>
            
            {{-- Follow Button --}}
            @auth
            @if(auth()->id() !== $store->id)
            <div class="flex-shrink-0">
                <form action="{{ route('store.toggleFollow', $store->store_slug) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-lg font-semibold transition-colors {{ $isFollowing ? 'bg-white/10 hover:bg-white/20 text-white border border-white/20' : 'bg-primary hover:bg-primary/90 text-white' }}">
                        {{ $isFollowing ? '✓ Mengikuti' : '+ Ikuti Toko' }}
                    </button>
                </form>
            </div>
            @endif
            @else
            <div class="flex-shrink-0">
                <a href="{{ route('login') }}" 
                   class="px-6 py-2.5 rounded-lg font-semibold transition-colors bg-primary hover:bg-primary/90 text-white inline-block">
                    + Ikuti Toko
                </a>
            </div>
            @endauth
        </div>
    </div>
</div>

