{{-- Services Tab --}}
<div class="py-6">
    {{-- Filter & Sort --}}
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('store.show', $store->store_slug) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="hidden" name="tab" value="services">
            
            {{-- Search --}}
            <input type="text" 
                   name="service_search" 
                   value="{{ request('service_search') }}" 
                   placeholder="Cari jasa..."
                   class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
            
            {{-- Category --}}
            <select name="service_category" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary focus:bg-white/10">
                <option value="" class="bg-dark text-white">Semua Kategori</option>
                @foreach($serviceCategories as $category)
                <option value="{{ $category }}" {{ request('service_category') === $category ? 'selected' : '' }} class="bg-dark text-white">
                    {{ $category }}
                </option>
                @endforeach
            </select>
            
            {{-- Sort --}}
            <div class="flex gap-2">
                <select name="service_sort" class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="newest" class="bg-dark text-white">Terbaru</option>
                    <option value="popular" {{ request('service_sort') === 'popular' ? 'selected' : '' }} class="bg-dark text-white">Terpopuler</option>
                    <option value="price_low" {{ request('service_sort') === 'price_low' ? 'selected' : '' }} class="bg-dark text-white">Harga Terendah</option>
                    <option value="price_high" {{ request('service_sort') === 'price_high' ? 'selected' : '' }} class="bg-dark text-white">Harga Tertinggi</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
    
    {{-- Services Grid --}}
    @if($services->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($services as $service)
                <x-service-card :service="$service" />
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $services->links() }}
        </div>
    @else
        <div class="glass p-12 rounded-lg text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-xl font-semibold mb-2">Belum Ada Jasa</h3>
            <p class="text-white/60">Toko ini belum memiliki jasa yang sesuai dengan filter Anda.</p>
        </div>
    @endif
</div>

