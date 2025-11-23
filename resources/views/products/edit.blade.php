@extends('layouts.app')

@section('title', 'Edit Produk - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-3xl">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">Edit Produk</h1>
    
    <form method="POST" action="{{ route('seller.products.update', $product) }}" enctype="multipart/form-data" 
          x-data="{ imagePreview: '{{ $product->image ? asset('storage/' . $product->image) : null }}', fileSelected: false }">
        @csrf
        @method('PUT')
        
        <div class="glass p-4 sm:p-6 rounded-lg space-y-4 sm:space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium mb-2">Judul Produk *</label>
                <input type="text" 
                       name="title" 
                       value="{{ old('title', $product->title) }}" 
                       required
                       autocomplete="off"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                @error('title')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div>
                <label class="block text-sm font-medium mb-2">Deskripsi *</label>
                <textarea name="description" 
                          rows="5" 
                          required
                          class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('description', $product->description) }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Price & Stock -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Harga (Rp) *</label>
                    <input type="number" 
                           name="price" 
                           value="{{ old('price', $product->price) }}" 
                           min="0" 
                           step="0.01" 
                           required
                           inputmode="numeric"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    @error('price')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stok *</label>
                    <input type="number" 
                           name="stock" 
                           value="{{ old('stock', $product->stock) }}" 
                           min="0" 
                           required
                           inputmode="numeric"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    @error('stock')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Warranty & Delivery Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Garansi (Hari)</label>
                    <input type="number" 
                           name="warranty_days" 
                           value="{{ old('warranty_days', $product->warranty_days) }}" 
                           min="0" 
                           inputmode="numeric"
                           placeholder="0 = tidak ada garansi"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    <p class="text-white/60 text-xs mt-1">Default: 7 hari. Isi 0 untuk tanpa garansi</p>
                    @error('warranty_days')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Estimasi Pengiriman (Hari)</label>
                    <input type="number" 
                           name="delivery_days" 
                           value="{{ old('delivery_days', $product->delivery_days) }}" 
                           min="0" 
                           inputmode="numeric"
                           placeholder="0 = instan"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    <p class="text-white/60 text-xs mt-1">Default: Instan (0 hari) untuk produk digital</p>
                    @error('delivery_days')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Category -->
            <div>
                <label class="block text-sm font-medium mb-2">Kategori *</label>
                <input type="text" name="category" value="{{ old('category', $product->category) }}" required
                       placeholder="Contoh: Template, Plugin, Script, dll"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                @error('category')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium mb-2">Gambar Produk</label>
                @if($product->image)
                <div class="mb-3">
                    <p class="text-sm text-white/60 mb-2">Gambar saat ini:</p>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current" class="w-48 h-48 object-cover rounded-lg">
                </div>
                @endif
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" 
                       @change="imagePreview = URL.createObjectURL($event.target.files[0])"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <p class="text-white/60 text-xs mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                @error('image')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div x-show="imagePreview && imagePreview !== '{{ $product->image ? asset('storage/' . $product->image) : '' }}'" class="mt-4">
                    <p class="text-sm text-white/60 mb-2">Preview baru:</p>
                    <img :src="imagePreview" alt="Preview" class="w-48 h-48 object-cover rounded-lg">
                </div>
            </div>
            
            <!-- File Upload -->
            <div>
                <label class="block text-sm font-medium mb-2">File Digital Produk</label>
                @if($product->file_path)
                <div class="mb-3 text-sm">
                    <p class="text-green-400">✓ File saat ini: {{ basename($product->file_path) }}</p>
                </div>
                @endif
                <input type="file" name="file" 
                       @change="fileSelected = $event.target.files.length > 0"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                <p class="text-white/60 text-xs mt-1">Max 10MB. Kosongkan jika tidak ingin mengubah file</p>
                @error('file')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p x-show="fileSelected" class="text-green-400 text-sm mt-2">✓ File baru dipilih</p>
            </div>
            
            <!-- Active Toggle -->
            <div class="flex items-center space-x-3">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-white/10 bg-white/5 text-primary focus:ring-primary">
                <label class="text-sm">Aktifkan produk (tampilkan di marketplace)</label>
            </div>
            
            <!-- Submit -->
            <div class="flex space-x-3 pt-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                    Update Produk
                </button>
                <a href="{{ route('products.show', $product->slug ?: $product->id) }}" 
                   class="px-6 py-3 glass glass-hover rounded-lg">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

