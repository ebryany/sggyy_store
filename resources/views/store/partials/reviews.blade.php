{{-- Reviews Tab --}}
<div class="py-6">
    @if($reviews->count() > 0)
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="glass p-4 sm:p-6 rounded-lg">
                <div class="flex items-start gap-4">
                    {{-- User Avatar --}}
                    <img src="{{ $review->user_avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user_name) }}" 
                         alt="{{ $review->user_name }}"
                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex-shrink-0">
                    
                    <div class="flex-1 min-w-0">
                        {{-- User Name & Date --}}
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div>
                                <h4 class="font-semibold">{{ $review->user_name }}</h4>
                                <p class="text-xs text-white/60">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                            </div>
                            
                            {{-- Rating Stars --}}
                            <div class="flex items-center gap-1 flex-shrink-0">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-white/20' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                        </div>
                        
                        {{-- Item Type Badge --}}
                        <span class="inline-block px-2 py-1 text-xs rounded-full mb-2 {{ $review->item_type === 'product' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                            {{ $review->item_type === 'product' ? 'Produk' : 'Jasa' }}: {{ $review->item_title }}
                        </span>
                        
                        {{-- Comment --}}
                        <p class="text-white/80 text-sm leading-relaxed">{{ $review->comment }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="glass p-12 rounded-lg text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            <h3 class="text-xl font-semibold mb-2">Belum Ada Ulasan</h3>
            <p class="text-white/60">Toko ini belum memiliki ulasan dari pembeli.</p>
        </div>
    @endif
</div>


