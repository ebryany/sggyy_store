{{-- Navigation Tabs --}}
<div class="sticky top-0 z-20 bg-gray-900/95 backdrop-blur-sm border-b border-white/10 mt-6">
    <div class="container mx-auto px-3 sm:px-4">
        <div class="flex overflow-x-auto hide-scrollbar">
            <a href="{{ route('store.show', $store->store_slug) }}?tab=products" 
               class="px-4 sm:px-6 py-3 sm:py-4 font-semibold whitespace-nowrap transition-colors border-b-2 {{ $activeTab === 'products' ? 'border-primary text-primary' : 'border-transparent text-white/60 hover:text-white' }}">
                Produk ({{ $stats['total_products'] }})
            </a>
            <a href="{{ route('store.show', $store->store_slug) }}?tab=services" 
               class="px-4 sm:px-6 py-3 sm:py-4 font-semibold whitespace-nowrap transition-colors border-b-2 {{ $activeTab === 'services' ? 'border-primary text-primary' : 'border-transparent text-white/60 hover:text-white' }}">
                Jasa ({{ $stats['total_services'] }})
            </a>
            <a href="{{ route('store.show', $store->store_slug) }}?tab=reviews" 
               class="px-4 sm:px-6 py-3 sm:py-4 font-semibold whitespace-nowrap transition-colors border-b-2 {{ $activeTab === 'reviews' ? 'border-primary text-primary' : 'border-transparent text-white/60 hover:text-white' }}">
                Ulasan ({{ $stats['reviews_count'] }})
            </a>
            <a href="{{ route('store.show', $store->store_slug) }}?tab=about" 
               class="px-4 sm:px-6 py-3 sm:py-4 font-semibold whitespace-nowrap transition-colors border-b-2 {{ $activeTab === 'about' ? 'border-primary text-primary' : 'border-transparent text-white/60 hover:text-white' }}">
                Tentang Toko
            </a>
        </div>
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>


