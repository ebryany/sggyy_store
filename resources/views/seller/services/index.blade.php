@extends('seller.layouts.dashboard')

@section('title', 'Jasa Saya - Seller Dashboard - Ebrystoree')

@section('content')
<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Jasa Saya</h1>
            <p class="text-white/60 text-sm sm:text-base">Kelola semua jasa yang Anda tawarkan</p>
        </div>
        <a href="{{ route('seller.services.create') }}" 
           class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-sm sm:text-base text-center touch-target flex items-center justify-center gap-2">
            <x-icon name="target" class="w-5 h-5" />
            <span>+ Tambah Jasa</span>
        </a>
    </div>
    
    <!-- Search & Filter -->
    <div class="glass p-3 sm:p-4 rounded-lg">
        <form method="GET" action="{{ route('seller.services.index') }}" class="space-y-4">
            <!-- Row 1: Search -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari jasa..." 
                       class="flex-1 glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                
                <select name="status" class="glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                    <option value="" class="bg-dark text-white">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-dark text-white">Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }} class="bg-dark text-white">Tidak Aktif</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }} class="bg-dark text-white">Draft</option>
                </select>
            </div>

            <!-- Row 2: Price Range & Sort -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs text-white/60 mb-1">Harga Min</label>
                    <input type="number" 
                           name="min_price" 
                           value="{{ request('min_price') }}" 
                           placeholder="0" 
                           min="0"
                           step="1000"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>
                
                <div>
                    <label class="block text-xs text-white/60 mb-1">Harga Max</label>
                    <input type="number" 
                           name="max_price" 
                           value="{{ request('max_price') }}" 
                           placeholder="Tidak terbatas" 
                           min="0"
                           step="1000"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-base sm:text-sm touch-target">
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Urutkan</label>
                    <select name="sort" class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 text-white text-base sm:text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }} class="bg-dark text-white">Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }} class="bg-dark text-white">Terlama</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Rendah ke Tinggi</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }} class="bg-dark text-white">Harga: Tinggi ke Rendah</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }} class="bg-dark text-white">Rating Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }} class="bg-dark text-white">Paling Populer</option>
                        <option value="completed" {{ request('sort') == 'completed' ? 'selected' : '' }} class="bg-dark text-white">Paling Banyak Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-white/60 mb-1">Per Halaman</label>
                    <select name="per_page" onchange="this.form.submit()" class="w-full glass border border-white/10 rounded-lg px-3 py-2 bg-white/5 text-white text-sm touch-target focus:outline-none focus:border-primary focus:bg-white/10">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }} class="bg-dark text-white">12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }} class="bg-dark text-white">24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }} class="bg-dark text-white">48</option>
                        <option value="96" {{ request('per_page') == 96 ? 'selected' : '' }} class="bg-dark text-white">96</option>
                    </select>
                </div>
            </div>

            <!-- Row 3: Actions -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="px-4 sm:px-6 py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors text-base sm:text-sm touch-target">
                    Terapkan Filter
                </button>
                
                @if(request()->anyFilled(['search', 'status', 'min_price', 'max_price', 'sort', 'per_page']))
                <a href="{{ route('seller.services.index') }}" class="px-4 sm:px-6 py-2 glass glass-hover rounded-lg text-center text-base sm:text-sm touch-target">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
        @forelse($services as $service)
            @include('components.service-card', ['service' => $service])
        @empty
            <div class="col-span-full text-center py-12">
                <x-icon name="target" class="w-16 h-16 text-white/20 mx-auto mb-4" />
                <p class="text-white/60 text-lg mb-4">Belum ada jasa yang Anda buat.</p>
                <a href="{{ route('seller.services.create') }}" 
                   class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors flex items-center gap-2 mx-auto">
                    <x-icon name="target" class="w-5 h-5" />
                    <span>Tambah Jasa Pertama</span>
                </a>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($services->hasPages())
    <div class="mt-8">
        {{ $services->links() }}
    </div>
    @endif
</div>
@endsection

