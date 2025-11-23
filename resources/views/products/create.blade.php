@extends('layouts.app')

@section('title', 'Tambah Produk - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12 max-w-6xl">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Tambah Produk Baru</h1>
        <div class="flex items-center gap-2 text-sm text-white/60">
            <x-icon name="info" class="w-4 h-4" />
            <span>Lengkapi semua informasi untuk produk terbaik</span>
        </div>
    </div>
    
    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data" 
          x-data="productForm()">
        @csrf
        
        <!-- Tab Navigation -->
        <div class="glass p-2 sm:p-3 rounded-lg mb-6 sticky top-4 z-10 backdrop-blur-xl bg-gray-900/80">
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                <button type="button"
                        @click="activeTab = 'basic'"
                        :class="activeTab === 'basic' ? 'bg-primary/20 text-primary border-primary' : 'glass border-white/10 text-white/60 hover:text-white'"
                        class="flex-shrink-0 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg border transition-all font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <x-icon name="info" class="w-4 h-4" />
                    <span class="hidden sm:inline">Informasi Dasar</span>
                    <span class="sm:hidden">Dasar</span>
                </button>
                <button type="button"
                        @click="activeTab = 'pricing'"
                        :class="activeTab === 'pricing' ? 'bg-primary/20 text-primary border-primary' : 'glass border-white/10 text-white/60 hover:text-white'"
                        class="flex-shrink-0 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg border transition-all font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <x-icon name="dollar" class="w-4 h-4" />
                    <span class="hidden sm:inline">Harga & Media</span>
                    <span class="sm:hidden">Harga</span>
                </button>
                <button type="button"
                        @click="activeTab = 'file'"
                        :class="activeTab === 'file' ? 'bg-primary/20 text-primary border-primary' : 'glass border-white/10 text-white/60 hover:text-white'"
                        class="flex-shrink-0 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg border transition-all font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <x-icon name="download" class="w-4 h-4" />
                    <span class="hidden sm:inline">File & Fitur</span>
                    <span class="sm:hidden">File</span>
                </button>
                <button type="button"
                        @click="activeTab = 'details'"
                        :class="activeTab === 'details' ? 'bg-primary/20 text-primary border-primary' : 'glass border-white/10 text-white/60 hover:text-white'"
                        class="flex-shrink-0 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg border transition-all font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <x-icon name="settings" class="w-4 h-4" />
                    <span class="hidden sm:inline">Detail & SEO</span>
                    <span class="sm:hidden">Detail</span>
                </button>
                <button type="button"
                        @click="activeTab = 'status'"
                        :class="activeTab === 'status' ? 'bg-primary/20 text-primary border-primary' : 'glass border-white/10 text-white/60 hover:text-white'"
                        class="flex-shrink-0 px-4 sm:px-6 py-2.5 sm:py-3 rounded-lg border transition-all font-semibold text-xs sm:text-sm flex items-center gap-2">
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Status</span>
                </button>
            </div>
        </div>
        
        <!-- Tab 1: Basic Information -->
        <div x-show="activeTab === 'basic'" x-cloak class="space-y-6">
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="info" class="w-5 h-5 text-primary" />
                    <span>Informasi Dasar Produk</span>
                </h2>
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Judul Produk *</label>
                        <input type="text" 
                               name="title" 
                               value="{{ old('title') }}" 
                               required
                               maxlength="255"
                               x-model="title"
                               @input="updateMetaTitle()"
                               autocomplete="off"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1"><span x-text="title.length"></span>/255 karakter</p>
                        @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Short Description -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Deskripsi Singkat</label>
                        <textarea name="short_description" 
                                  rows="2"
                                  maxlength="500"
                                  x-model="shortDescription"
                                  placeholder="Ringkasan produk dalam 1-2 kalimat (untuk preview card)"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('short_description') }}</textarea>
                        <p class="text-white/60 text-xs mt-1"><span x-text="shortDescription.length"></span>/500 karakter</p>
                        @error('short_description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Deskripsi Lengkap *</label>
                        <textarea name="description" 
                                  rows="8" 
                                  required
                                  x-model="description"
                                  @input="updateMetaDescription()"
                                  placeholder="Jelaskan produk secara detail, fitur-fitur, cara penggunaan, dll"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('description') }}</textarea>
                        <p class="text-white/60 text-xs mt-1">Gunakan format HTML sederhana jika diperlukan</p>
                        @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- SKU & Product Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">SKU / Kode Produk</label>
                            <input type="text" 
                                   name="sku" 
                                   value="{{ old('sku') }}"
                                   maxlength="100"
                                   placeholder="AUTO-GENERATED jika kosong"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            <p class="text-white/60 text-xs mt-1">Kode unik produk (otomatis jika kosong)</p>
                            @error('sku')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Tipe Produk</label>
                            <select name="product_type" 
                                    class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                                <option value="">Pilih Tipe Produk</option>
                                <option value="Template" {{ old('product_type') == 'Template' ? 'selected' : '' }}>Template</option>
                                <option value="Plugin" {{ old('product_type') == 'Plugin' ? 'selected' : '' }}>Plugin</option>
                                <option value="Script" {{ old('product_type') == 'Script' ? 'selected' : '' }}>Script</option>
                                <option value="Theme" {{ old('product_type') == 'Theme' ? 'selected' : '' }}>Theme</option>
                                <option value="Add-on" {{ old('product_type') == 'Add-on' ? 'selected' : '' }}>Add-on</option>
                                <option value="Extension" {{ old('product_type') == 'Extension' ? 'selected' : '' }}>Extension</option>
                                <option value="Module" {{ old('product_type') == 'Module' ? 'selected' : '' }}>Module</option>
                                <option value="Lainnya" {{ old('product_type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('product_type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Kategori *</label>
                        <select name="category" required
                                class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            <option value="">Pilih Kategori</option>
                            <option value="Template" {{ old('category') == 'Template' ? 'selected' : '' }}>Template</option>
                            <option value="Plugin" {{ old('category') == 'Plugin' ? 'selected' : '' }}>Plugin</option>
                            <option value="Script" {{ old('category') == 'Script' ? 'selected' : '' }}>Script</option>
                            <option value="Theme" {{ old('category') == 'Theme' ? 'selected' : '' }}>Theme</option>
                            <option value="Add-on" {{ old('category') == 'Add-on' ? 'selected' : '' }}>Add-on</option>
                            <option value="Extension" {{ old('category') == 'Extension' ? 'selected' : '' }}>Extension</option>
                            <option value="Module" {{ old('category') == 'Module' ? 'selected' : '' }}>Module</option>
                            <option value="Asset" {{ old('category') == 'Asset' ? 'selected' : '' }}>Asset</option>
                            <option value="Tool" {{ old('category') == 'Tool' ? 'selected' : '' }}>Tool</option>
                            <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab 2: Pricing & Media -->
        <div x-show="activeTab === 'pricing'" x-cloak class="space-y-6">
            <!-- Pricing Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="dollar" class="w-5 h-5 text-primary" />
                    <span>Harga & Stok</span>
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Harga Regular (Rp) *</label>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price') }}" 
                               min="0" 
                               step="0.01" 
                               required
                               inputmode="numeric"
                               x-model.number="price"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        @error('price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Harga Sale (Rp)</label>
                        <input type="number" 
                               name="sale_price" 
                               value="{{ old('sale_price') }}" 
                               min="0" 
                               step="0.01"
                               inputmode="numeric"
                               x-model.number="salePrice"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1" x-show="salePrice && salePrice < price">
                            Diskon: <span x-text="Math.round(((price - salePrice) / price) * 100)"></span>%
                        </p>
                        @error('sale_price')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Stok *</label>
                        <input type="number" 
                               name="stock" 
                               value="{{ old('stock', 0) }}" 
                               min="0" 
                               required
                               inputmode="numeric"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        @error('stock')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Media Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="image" class="w-5 h-5 text-primary" />
                    <span>Media & Preview</span>
                </h2>
                
                <div class="space-y-4 sm:space-y-6">
                    <!-- Multiple Image Upload -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Gambar Produk (Gallery)</label>
                        <div class="border-2 border-dashed border-white/20 rounded-lg p-4">
                            <input type="file" 
                                   name="images[]" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   multiple
                                   @change="handleImageUpload($event)"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                            <p class="text-white/60 text-xs mt-2">Max 10 gambar. Format: JPEG, PNG, JPG, WEBP (Max 2MB per gambar)</p>
                        </div>
                        @error('images')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Image Preview Gallery -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4" x-show="imagePreviews.length > 0">
                            <template x-for="(preview, index) in imagePreviews" :key="index">
                                <div class="relative group">
                                    <img :src="preview.url" :alt="'Preview ' + (index + 1)" 
                                         class="w-full h-32 object-cover rounded-lg border border-white/10">
                                    <button type="button" 
                                            @click="removeImage(index)"
                                            class="absolute top-2 right-2 bg-red-500/80 hover:bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                        ×
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Main Image -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Gambar Utama (Opsional)</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" 
                               @change="mainImagePreview = URL.createObjectURL($event.target.files[0])"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-white/60 text-xs mt-1">Gunakan jika ingin gambar utama terpisah dari gallery</p>
                        <div x-show="mainImagePreview" class="mt-4">
                            <img :src="mainImagePreview" alt="Preview" class="w-48 h-48 object-cover rounded-lg">
                        </div>
                        @error('image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Video Preview -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Video Preview (YouTube/Vimeo URL)</label>
                        <input type="url" 
                               name="video_preview" 
                               value="{{ old('video_preview') }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Link video demo/preview produk</p>
                        @error('video_preview')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Demo Link -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Demo / Preview Link</label>
                        <input type="url" 
                               name="demo_link" 
                               value="{{ old('demo_link') }}"
                               placeholder="https://demo.example.com"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Link ke live demo produk</p>
                        @error('demo_link')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab 3: File & Features -->
        <div x-show="activeTab === 'file'" x-cloak class="space-y-6">
            <!-- File Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="download" class="w-5 h-5 text-primary" />
                    <span>File Digital</span>
                </h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">File Digital Produk *</label>
                        <input type="file" name="file" 
                               @change="fileSelected = $event.target.files.length > 0; if(fileSelected) { fileSize = formatFileSize($event.target.files[0].size) }"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-white/60 text-xs mt-1">Max 10MB. File ini akan diberikan kepada pembeli setelah pembayaran.</p>
                        <p x-show="fileSelected" class="text-green-400 text-sm mt-2">✓ File dipilih <span x-show="fileSize">(<span x-text="fileSize"></span>)</span></p>
                        @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Batas Download</label>
                        <input type="number" 
                               name="download_limit" 
                               value="{{ old('download_limit') }}"
                               min="1"
                               placeholder="Kosongkan = unlimited"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Berapa kali pembeli bisa download (kosongkan = unlimited)</p>
                        @error('download_limit')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="check" class="w-5 h-5 text-primary" />
                    <span>Fitur Produk</span>
                </h2>
                
                <div class="space-y-2">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="flex gap-2">
                            <input type="text" 
                                   :name="'features[' + index + ']'"
                                   x-model="features[index]"
                                   placeholder="Masukkan fitur produk"
                                   class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary">
                            <button type="button" 
                                    @click="removeFeature(index)"
                                    class="px-4 py-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-colors">
                                <x-icon name="x" class="w-4 h-4" />
                            </button>
                        </div>
                    </template>
                    <button type="button" 
                            @click="addFeature()"
                            class="w-full px-4 py-2 glass glass-hover rounded-lg text-primary border border-primary/20 hover:border-primary/40 transition-colors">
                        + Tambah Fitur
                    </button>
                </div>
            </div>
            
            <!-- Tags Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="tag" class="w-5 h-5 text-primary" />
                    <span>Tags / Keywords</span>
                </h2>
                
                <div>
                    <input type="text" 
                           x-model="tagInput"
                           @keydown.enter.prevent="addTag()"
                           placeholder="Masukkan tag dan tekan Enter (max 20 tags)"
                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                    <p class="text-white/60 text-xs mt-1">Pisahkan dengan Enter. Max 20 tags</p>
                </div>
                
                <!-- Tags Display -->
                <div class="flex flex-wrap gap-2 mt-4" x-show="tags.length > 0">
                    <template x-for="(tag, index) in tags" :key="index">
                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/20 text-primary rounded-full text-sm">
                            <span x-text="tag"></span>
                            <button type="button" 
                                    @click="removeTag(index)"
                                    class="hover:text-red-400">
                                ×
                            </button>
                        </span>
                    </template>
                </div>
                
                <!-- Hidden inputs for tags -->
                <template x-for="(tag, index) in tags" :key="index">
                    <input type="hidden" :name="'tags[' + index + ']'" :value="tag">
                </template>
            </div>
        </div>
        
        <!-- Tab 4: Details & SEO -->
        <div x-show="activeTab === 'details'" x-cloak class="space-y-6">
            <!-- Product Details -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="settings" class="w-5 h-5 text-primary" />
                    <span>Detail Produk</span>
                </h2>
                
                <div class="space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Versi</label>
                            <input type="text" 
                                   name="version" 
                                   value="{{ old('version') }}"
                                   placeholder="1.0.0"
                                   maxlength="50"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('version')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Tipe Lisensi</label>
                            <select name="license_type" 
                                    class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                                <option value="">Pilih Lisensi</option>
                                <option value="GPL" {{ old('license_type') == 'GPL' ? 'selected' : '' }}>GPL (General Public License)</option>
                                <option value="Commercial" {{ old('license_type') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="Personal" {{ old('license_type') == 'Personal' ? 'selected' : '' }}>Personal</option>
                                <option value="Extended" {{ old('license_type') == 'Extended' ? 'selected' : '' }}>Extended</option>
                                <option value="Regular" {{ old('license_type') == 'Regular' ? 'selected' : '' }}>Regular</option>
                            </select>
                            @error('license_type')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">System Requirements</label>
                        <textarea name="system_requirements" 
                                  rows="3"
                                  maxlength="1000"
                                  placeholder="Contoh: PHP 7.4+, WordPress 5.0+, MySQL 5.6+"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('system_requirements') }}</textarea>
                        @error('system_requirements')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Informasi Support</label>
                        <textarea name="support_info" 
                                  rows="2"
                                  maxlength="500"
                                  placeholder="Contoh: Support 6 bulan, Update lifetime, Documentation included"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('support_info') }}</textarea>
                        @error('support_info')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Warranty & Delivery -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Garansi (Hari)</label>
                            <input type="number" 
                                   name="warranty_days" 
                                   value="{{ old('warranty_days', 7) }}" 
                                   min="0" 
                                   inputmode="numeric"
                                   placeholder="0 = tidak ada garansi"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            <p class="text-white/60 text-xs mt-1">Default: 7 hari</p>
                            @error('warranty_days')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Estimasi Pengiriman (Hari)</label>
                            <input type="number" 
                                   name="delivery_days" 
                                   value="{{ old('delivery_days', 0) }}" 
                                   min="0" 
                                   inputmode="numeric"
                                   placeholder="0 = instan"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            <p class="text-white/60 text-xs mt-1">Default: Instan (0 hari)</p>
                            @error('delivery_days')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SEO Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="search" class="w-5 h-5 text-primary" />
                    <span>SEO (Search Engine Optimization)</span>
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Title</label>
                        <input type="text" 
                               name="meta_title" 
                               value="{{ old('meta_title') }}"
                               maxlength="255"
                               x-model="metaTitle"
                               placeholder="Otomatis dari judul jika kosong"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1"><span x-text="metaTitle.length"></span>/255 karakter</p>
                        @error('meta_title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Description</label>
                        <textarea name="meta_description" 
                                  rows="3"
                                  maxlength="500"
                                  x-model="metaDescription"
                                  placeholder="Otomatis dari deskripsi jika kosong"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('meta_description') }}</textarea>
                        <p class="text-white/60 text-xs mt-1"><span x-text="metaDescription.length"></span>/500 karakter</p>
                        @error('meta_description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab 5: Status -->
        <div x-show="activeTab === 'status'" x-cloak class="space-y-6">
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                    <x-icon name="check" class="w-5 h-5 text-primary" />
                    <span>Status & Publikasi</span>
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="is_draft" value="1" x-model="isDraft"
                               class="w-5 h-5 rounded border-white/10 bg-white/5 text-primary focus:ring-primary">
                        <label class="text-sm">Simpan sebagai Draft (tidak ditampilkan di marketplace)</label>
                    </div>
                    
                    <div class="flex items-center space-x-3" x-show="!isDraft">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-white/10 bg-white/5 text-primary focus:ring-primary">
                        <label class="text-sm">Aktifkan produk (tampilkan di marketplace)</label>
                    </div>
                    
                    <div x-show="!isDraft">
                        <label class="block text-sm font-medium mb-2">Jadwalkan Publikasi (Opsional)</label>
                        <input type="datetime-local" 
                               name="published_at" 
                               value="{{ old('published_at') }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Kosongkan untuk publish sekarang</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation & Submit Buttons (Fixed Bottom) -->
        <div class="sticky bottom-0 left-0 right-0 z-20 mt-8 pt-4 sm:pt-6 pb-safe bg-gray-900/95 backdrop-blur-xl border-t border-white/10">
            <div class="container mx-auto px-3 sm:px-4">
                <!-- Action Buttons -->
                <div class="flex gap-2 sm:gap-3">
                    <!-- Mobile: Compact Navigation Buttons -->
                    <div class="flex gap-2 sm:hidden">
                        <button type="button" 
                                @click="activeTab = activeTab === 'status' ? 'details' : activeTab === 'details' ? 'file' : activeTab === 'file' ? 'pricing' : activeTab === 'pricing' ? 'basic' : 'status'"
                                x-show="activeTab !== 'basic'"
                                class="px-3 py-2.5 glass glass-hover rounded-lg font-semibold">
                            <x-icon name="arrow-left" class="w-4 h-4" />
                        </button>
                        <button type="button" 
                                @click="activeTab = activeTab === 'basic' ? 'pricing' : activeTab === 'pricing' ? 'file' : activeTab === 'file' ? 'details' : activeTab === 'details' ? 'status' : 'basic'"
                                x-show="activeTab !== 'status'"
                                class="px-3 py-2.5 glass glass-hover rounded-lg font-semibold">
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </button>
                    </div>
                    
                    <!-- Desktop: Full Navigation Buttons -->
                    <button type="button" 
                            @click="activeTab = activeTab === 'status' ? 'details' : activeTab === 'details' ? 'file' : activeTab === 'file' ? 'pricing' : activeTab === 'pricing' ? 'basic' : 'status'"
                            x-show="activeTab !== 'basic'"
                            class="hidden sm:flex items-center gap-2 px-4 sm:px-6 py-3 glass glass-hover rounded-lg font-semibold whitespace-nowrap">
                        <x-icon name="arrow-left" class="w-4 h-4" />
                        <span>Kembali</span>
                    </button>
                    <button type="button" 
                            @click="activeTab = activeTab === 'basic' ? 'pricing' : activeTab === 'pricing' ? 'file' : activeTab === 'file' ? 'details' : activeTab === 'details' ? 'status' : 'basic'"
                            x-show="activeTab !== 'status'"
                            class="hidden sm:flex items-center gap-2 px-4 sm:px-6 py-3 glass glass-hover rounded-lg font-semibold whitespace-nowrap">
                        <span>Lanjutkan</span>
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </button>
                    
                    <!-- Submit Button (Primary Action) -->
                    <button type="submit" 
                            class="flex-1 sm:flex-none sm:px-8 px-4 py-2.5 sm:py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold text-sm sm:text-base">
                        <!-- Desktop -->
                        <span class="hidden sm:inline" x-text="!isDraft ? 'Simpan & Publish' : 'Simpan Draft'">Simpan & Publish</span>
                        <!-- Mobile -->
                        <span class="sm:hidden" x-text="!isDraft ? 'Publish' : 'Draft'">Publish</span>
                    </button>
                    
                    <!-- Cancel Button (Hidden on Mobile) -->
                    <a href="{{ route('seller.products.index') }}" 
                       class="hidden sm:flex items-center justify-center px-4 sm:px-6 py-3 glass glass-hover rounded-lg font-semibold whitespace-nowrap">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
[x-cloak] {
    display: none !important;
}
</style>

<script>
function productForm() {
    return {
        activeTab: 'basic',
        title: '{{ old('title', '') }}',
        shortDescription: '{{ old('short_description', '') }}',
        description: '{{ old('description', '') }}',
        price: {{ old('price', 0) }},
        salePrice: {{ old('sale_price', 0) }},
        metaTitle: '{{ old('meta_title', '') }}',
        metaDescription: '{{ old('meta_description', '') }}',
        isDraft: {{ old('is_draft', false) ? 'true' : 'false' }},
        
        init() {
            // Ensure isDraft is properly initialized
            if (typeof this.isDraft === 'undefined') {
                this.isDraft = false;
            }
        },
        imagePreviews: [],
        mainImagePreview: null,
        fileSelected: false,
        fileSize: '',
        features: @json(old('features', [''])),
        tags: @json(old('tags', [])),
        tagInput: '',
        
        updateMetaTitle() {
            if (!this.metaTitle && this.title) {
                this.metaTitle = this.title;
            }
        },
        
        updateMetaDescription() {
            if (!this.metaDescription && this.description) {
                this.metaDescription = this.description.substring(0, 500);
            }
        },
        
        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (this.imagePreviews.length < 10) {
                    this.imagePreviews.push({
                        url: URL.createObjectURL(file),
                        file: file
                    });
                }
            });
        },
        
        removeImage(index) {
            URL.revokeObjectURL(this.imagePreviews[index].url);
            this.imagePreviews.splice(index, 1);
        },
        
        addFeature() {
            if (this.features.length < 50) {
                this.features.push('');
            }
        },
        
        removeFeature(index) {
            this.features.splice(index, 1);
        },
        
        addTag() {
            const tag = this.tagInput.trim();
            if (tag && this.tags.length < 20 && !this.tags.includes(tag)) {
                this.tags.push(tag);
                this.tagInput = '';
            }
        },
        
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        
        formatFileSize(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>
@endsection
