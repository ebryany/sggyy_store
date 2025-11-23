@props(['service'])

<div class="group relative h-full flex flex-col rounded-xl overflow-hidden bg-gradient-to-br from-white/5 via-white/5 to-white/5 border border-white/10 hover:border-primary/30 transition-all duration-300 hover:shadow-lg hover:shadow-primary/10">
    <a href="{{ route('services.show', $service->slug ?: $service->id) }}" class="flex flex-col h-full">
        <!-- Image Section -->
        <div class="relative overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5">
            @if($service->image)
            <img src="{{ asset('storage/' . $service->image) }}" 
                 alt="{{ $service->title }}" 
                 class="w-full h-48 sm:h-56 object-cover group-hover:scale-110 transition-transform duration-500">
            @else
            <div class="w-full h-48 sm:h-56 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-primary/20 flex items-center justify-center mx-auto mb-3 border border-primary/30">
                        <x-icon name="game" class="w-8 h-8 sm:w-10 sm:h-10 text-primary" />
                    </div>
                    <p class="text-primary font-bold text-lg sm:text-xl uppercase tracking-wider">
                        @php
                            $words = explode(' ', $service->title);
                            $initials = '';
                            foreach ($words as $word) {
                                if (strlen($initials) < 3 && !empty($word)) {
                                    $initials .= strtoupper(substr($word, 0, 1));
                                }
                            }
                            if (empty($initials)) {
                                $initials = 'JASA';
                            }
                        @endphp
                        {{ $initials }}
                    </p>
                </div>
            </div>
            @endif
            
            <!-- Status Badge -->
            @if($service->status !== 'active')
            <div class="absolute top-3 right-3 px-3 py-1.5 bg-red-500/90 backdrop-blur-sm rounded-lg text-xs font-semibold text-white border border-red-400/50">
                Tidak Aktif
            </div>
            @endif
            
            <!-- Overlay Gradient on Hover -->
            <div class="absolute inset-0 bg-gradient-to-t from-dark/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        </div>
        
        <!-- Content Section -->
        <div class="flex-1 flex flex-col p-4 sm:p-5">
            <!-- Title -->
            <h3 class="text-base sm:text-lg font-bold mb-2 line-clamp-2 text-white group-hover:text-primary transition-colors">
                {{ $service->title }}
            </h3>
            
            <!-- Description -->
            <p class="text-white/60 text-xs sm:text-sm mb-4 line-clamp-2 flex-1 leading-relaxed">
                {{ $service->description }}
            </p>
            
            <!-- Price & Rating -->
            <div class="mb-4 pb-4 border-b border-white/10">
                <div class="flex items-baseline gap-2 mb-2">
                    <span class="text-xl sm:text-2xl font-bold text-primary">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                </div>
                @if($service->ratings->count() > 0)
                <div class="flex items-center gap-1.5">
                    <div class="flex items-center">
                        <x-icon name="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-yellow-400 fill-yellow-400" />
                        <span class="text-xs sm:text-sm font-semibold text-white/90 ml-1">{{ number_format($service->averageRating(), 1) }}</span>
                    </div>
                    <span class="text-xs text-white/50">•</span>
                    <span class="text-xs sm:text-sm text-white/60">{{ $service->ratings->count() }} ulasan</span>
                </div>
                @else
                <div class="flex items-center gap-1.5">
                    <x-icon name="star" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/30" />
                    <span class="text-xs sm:text-sm text-white/50">Belum ada rating</span>
                </div>
                @endif
            </div>
            
            <!-- Footer Info -->
            <div class="flex items-center justify-between text-xs sm:text-sm">
                <div class="flex items-center gap-1.5 text-white/70">
                    <x-icon name="clock" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                    <span>{{ $service->duration_hours }} jam</span>
                </div>
                @if($service->completed_count > 0)
                <div class="flex items-center gap-1.5 text-green-400">
                    <x-icon name="check" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                    <span class="font-medium">{{ $service->completed_count }} selesai</span>
                </div>
                @endif
            </div>
        </div>
    </a>
</div>
