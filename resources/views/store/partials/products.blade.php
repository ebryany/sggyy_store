{{-- Products Tab --}}
<div class="py-6">
    {{-- Filter & Sort --}}
    <div class="glass p-4 rounded-lg mb-6">
        <form method="GET" action="{{ route('store.show', $store->store_slug) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="hidden" name="tab" value="products">
            
            {{-- Search --}}
            <input type="text" 
                   name="product_search" 
                   value="{{ request('product_search') }}" 
                   placeholder="Cari produk..."
                   class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
            
            {{-- Category --}}
            <select name="product_category" class="glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary focus:bg-white/10">
                <option value="" class="bg-dark text-white">Semua Kategori</option>
                @foreach($productCategories as $category)
                <option value="{{ $category }}" {{ request('product_category') === $category ? 'selected' : '' }} class="bg-dark text-white">
                    {{ $category }}
                </option>
                @endforeach
            </select>
            
            {{-- Sort --}}
            <div class="flex gap-2">
                <select name="product_sort" class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="newest" class="bg-dark text-white">Terbaru</option>
                    <option value="popular" {{ request('product_sort') === 'popular' ? 'selected' : '' }} class="bg-dark text-white">Terpopuler</option>
                    <option value="price_low" {{ request('product_sort') === 'price_low' ? 'selected' : '' }} class="bg-dark text-white">Harga Terendah</option>
                    <option value="price_high" {{ request('product_sort') === 'price_high' ? 'selected' : '' }} class="bg-dark text-white">Harga Tertinggi</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
    
    {{-- Products Grid --}}
    @if($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="glass p-12 rounded-lg text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <h3 class="text-xl font-semibold mb-2">Belum Ada Produk</h3>
            <p class="text-white/60">Toko ini belum memiliki produk yang sesuai dengan filter Anda.</p>
        </div>
    @endif
</div>


