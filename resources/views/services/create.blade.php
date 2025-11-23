@extends('layouts.app')

@section('title', 'Tambah Jasa - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-3xl">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">Tambah Jasa Baru</h1>
    
    <form method="POST" action="{{ route('seller.services.store') }}" enctype="multipart/form-data"
          x-data="{ imagePreview: null }">
        @csrf
        
        <div class="glass p-4 sm:p-6 rounded-lg space-y-4 sm:space-y-6">
            <!-- Title -->
            <div>
                <label class="block text-sm font-medium mb-2">Judul Jasa *</label>
                <input type="text" 
                       name="title" 
                       value="{{ old('title') }}" 
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
                          class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('description') }}</textarea>
                @error('description')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Price & Duration -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Harga (Rp) *</label>
                    <input type="number" 
                           name="price" 
                           value="{{ old('price') }}" 
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
                    <label class="block text-sm font-medium mb-2">Durasi (Jam) *</label>
                    <input type="number" 
                           name="duration_hours" 
                           value="{{ old('duration_hours', 1) }}" 
                           min="1" 
                           required
                           inputmode="numeric"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    @error('duration_hours')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium mb-2">Gambar Jasa</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg" 
                       @change="imagePreview = URL.createObjectURL($event.target.files[0])"
                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                @error('image')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div x-show="imagePreview" class="mt-4">
                    <img :src="imagePreview" alt="Preview" class="w-48 h-48 object-cover rounded-lg">
                </div>
            </div>
            
            <!-- Submit -->
            <div class="flex space-x-3 pt-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold">
                    Simpan Jasa
                </button>
                <a href="{{ route('seller.services.index') }}" 
                   class="px-6 py-3 glass glass-hover rounded-lg">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection





