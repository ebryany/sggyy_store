@extends('layouts.app')

@section('title', 'Pengaturan - Ebrystoree')

@section('content')
<div class="container mx-auto px-4 py-8 sm:py-12 max-w-7xl" x-data="{ activeTab: 'platform' }">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-2 flex items-center gap-3">
            <x-icon name="settings" class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 text-primary" />
            Pengaturan Sistem
        </h1>
        <p class="text-white/60 text-sm sm:text-base">Kelola semua pengaturan platform dari sini</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 overflow-x-auto">
        <div class="flex gap-2 sm:gap-3 border-b border-white/10 pb-2 min-w-max">
            <button @click="activeTab = 'platform'" 
                    :class="activeTab === 'platform' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="building" class="w-4 h-4" />
                Platform
            </button>
            <button @click="activeTab = 'home'" 
                    :class="activeTab === 'home' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="home" class="w-4 h-4" />
                Home Page
            </button>
            <button @click="activeTab = 'banner'" 
                    :class="activeTab === 'banner' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="image" class="w-4 h-4" />
                Banner
            </button>
            <button @click="activeTab = 'contact'" 
                    :class="activeTab === 'contact' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="phone" class="w-4 h-4" />
                Kontak
            </button>
            <button @click="activeTab = 'bank'" 
                    :class="activeTab === 'bank' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="bank" class="w-4 h-4" />
                Bank
            </button>
            <button @click="activeTab = 'commission'" 
                    :class="activeTab === 'commission' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="dollar" class="w-4 h-4" />
                Komisi
            </button>
            <button @click="activeTab = 'limits'" 
                    :class="activeTab === 'limits' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="chart" class="w-4 h-4" />
                Limit
            </button>
            <button @click="activeTab = 'email'" 
                    :class="activeTab === 'email' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="mail" class="w-4 h-4" />
                Email
            </button>
            <button @click="activeTab = 'seo'" 
                    :class="activeTab === 'seo' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="search" class="w-4 h-4" />
                SEO
            </button>
            <button @click="activeTab = 'features'" 
                    :class="activeTab === 'features' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="lightning" class="w-4 h-4" />
                Fitur
            </button>
            <button @click="activeTab = 'hours'" 
                    :class="activeTab === 'hours' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="clock" class="w-4 h-4" />
                Jam Operasional
            </button>
            <button @click="activeTab = 'owner'" 
                    :class="activeTab === 'owner' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="user" class="w-4 h-4" />
                Owner & Founder
            </button>
            <button @click="activeTab = 'featured'" 
                    :class="activeTab === 'featured' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="award" class="w-4 h-4" />
                Featured Promosi
            </button>
            <button @click="activeTab = 'api'" 
                    :class="activeTab === 'api' ? 'bg-primary/20 text-primary border-primary' : 'text-white/60 hover:text-white border-transparent'"
                    class="px-4 sm:px-6 py-2.5 sm:py-3 rounded-t-lg border-b-2 transition-all font-semibold text-sm sm:text-base whitespace-nowrap flex items-center gap-2">
                <x-icon name="link" class="w-4 h-4" />
                API Settings
            </button>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="space-y-6">
        <!-- Platform Settings Tab -->
        <div x-show="activeTab === 'platform'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="building" class="w-6 h-6 text-primary" />
                Pengaturan Platform
            </h2>
            <form method="POST" action="{{ route('admin.settings.platform') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Platform</label>
                        <input type="text" name="site_name" value="{{ $platformSettings['site_name'] ?? 'Ebrystoree' }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Nama ini akan muncul di navbar dan title halaman</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Tagline</label>
                        <input type="text" name="tagline" value="{{ $platformSettings['tagline'] ?? '' }}"
                               placeholder="Marketplace terpercaya untuk produk digital dan jasa joki tugas"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <!-- Logo & Favicon Upload -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                         x-data="{
                             logoPreview: null,
                             faviconPreview: null,
                             logoUrl: '{{ $platformSettings['logo'] ?? '' }}',
                             faviconUrl: '{{ $platformSettings['favicon'] ?? '' }}',
                             showLogoUrlInput: false,
                             showFaviconUrlInput: false,
                             init() {
                                 @if(!empty($platformSettings['logo_url']))
                                 this.logoPreview = '{{ $platformSettings['logo_url'] }}';
                                 this.logoUrl = '{{ $platformSettings['logo'] ?? '' }}';
                                 @endif
                                 @if(!empty($platformSettings['favicon_url']))
                                 this.faviconPreview = '{{ $platformSettings['favicon_url'] }}';
                                 @endif
                             }
                         }">
                        <!-- Logo Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Logo</label>
                            <div class="space-y-3">
                                <!-- File Upload -->
                                <div class="relative">
                                    <input type="file" 
                                           name="logo_file" 
                                           id="logo_file"
                                           accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   logoPreview = URL.createObjectURL(file);
                                                   showLogoUrlInput = false;
                                                   document.getElementById('logo_url_input').value = '';
                                               }
                                           "
                                           class="hidden">
                                    <label for="logo_file" 
                                           class="flex items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                        <div class="text-center px-4">
                                            <x-icon name="image" class="w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors" />
                                            <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                                <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-white/50 mt-1">PNG, JPG, SVG, WebP (Max 2MB)</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Preview & Current Logo -->
                                <div class="glass p-4 rounded-lg border border-white/10">
                                    <p class="text-xs text-white/60 mb-3 font-medium">Preview Logo:</p>
                                    <div class="space-y-3">
                                        <!-- Preview Box (Larger) -->
                                        <div class="w-full min-h-[120px] sm:min-h-[150px] rounded-lg bg-white/5 border border-white/10 overflow-hidden flex items-center justify-center p-4">
                                            <template x-if="logoPreview">
                                                <img :src="logoPreview" alt="Logo Preview" class="max-w-full max-h-[120px] sm:max-h-[150px] object-contain">
                                            </template>
                                            <template x-if="!logoPreview">
                                                <div class="text-center">
                                                    <x-icon name="image" class="w-12 h-12 text-white/40 mx-auto mb-2" />
                                                    <p class="text-xs text-white/50">Belum ada logo</p>
                                                </div>
                                            </template>
                                        </div>
                                        <!-- Info & Actions -->
                                        <div class="flex items-center justify-between">
                                            <p class="text-xs text-white/60" x-text="logoPreview ? 'Logo siap digunakan' : 'Upload logo untuk melihat preview'"></p>
                                            <button type="button" 
                                                    @click="logoPreview = null; document.getElementById('logo_file').value = '';"
                                                    x-show="logoPreview"
                                                    class="text-xs text-red-400 hover:text-red-300 transition-colors px-2 py-1 rounded hover:bg-red-500/10">
                                                Hapus Preview
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- URL Input (Alternative) -->
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="showLogoUrlInput = !showLogoUrlInput"
                                            class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                        <x-icon name="link" class="w-3 h-3" />
                                        <span x-text="showLogoUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                    </button>
                                </div>
                                <div x-show="showLogoUrlInput" x-cloak class="space-y-2">
                                    <input type="text" 
                                           name="logo" 
                                           id="logo_url_input"
                                           :value="logoUrl"
                                   placeholder="/images/logo.png atau URL lengkap"
                                           @input="logoUrl = $event.target.value; if (logoUrl) logoPreview = logoUrl;"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                        </div>
                            </div>
                        </div>
                        
                        <!-- Favicon Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Favicon</label>
                            <div class="space-y-3">
                                <!-- File Upload -->
                                <div class="relative">
                                    <input type="file" 
                                           name="favicon_file" 
                                           id="favicon_file"
                                           accept="image/png,image/x-icon,image/svg+xml,image/jpeg,image/jpg"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   faviconPreview = URL.createObjectURL(file);
                                                   showFaviconUrlInput = false;
                                                   document.getElementById('favicon_url_input').value = '';
                                               }
                                           "
                                           class="hidden">
                                    <label for="favicon_file" 
                                           class="flex items-center justify-center w-full h-32 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                        <div class="text-center px-4">
                                            <x-icon name="image" class="w-8 h-8 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors" />
                                            <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                                <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                            </p>
                                            <p class="text-xs text-white/50 mt-1">ICO, PNG, SVG (Max 1MB)</p>
                                        </div>
                                    </label>
                                </div>
                                
                                <!-- Preview & Current Favicon -->
                                <div class="glass p-3 rounded-lg border border-white/10">
                                    <p class="text-xs text-white/60 mb-2">Preview:</p>
                                    <div class="flex items-center gap-3">
                                        <div class="w-16 h-16 rounded-lg bg-white/5 border border-white/10 overflow-hidden flex items-center justify-center flex-shrink-0">
                                            <template x-if="faviconPreview">
                                                <img :src="faviconPreview" alt="Favicon Preview" class="w-full h-full object-contain">
                                            </template>
                                            <template x-if="!faviconPreview">
                                                <x-icon name="image" class="w-6 h-6 text-white/40" />
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs text-white/60 mb-1" x-text="faviconPreview ? 'Favicon dipilih' : 'Belum ada favicon'"></p>
                                            <button type="button" 
                                                    @click="faviconPreview = null; document.getElementById('favicon_file').value = '';"
                                                    x-show="faviconPreview"
                                                    class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- URL Input (Alternative) -->
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            @click="showFaviconUrlInput = !showFaviconUrlInput"
                                            class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                        <x-icon name="link" class="w-3 h-3" />
                                        <span x-text="showFaviconUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                    </button>
                                </div>
                                <div x-show="showFaviconUrlInput" x-cloak class="space-y-2">
                                    <input type="text" 
                                           name="favicon" 
                                           id="favicon_url_input"
                                           :value="faviconUrl"
                                   placeholder="/images/favicon.ico atau URL lengkap"
                                           @input="faviconUrl = $event.target.value; if (faviconUrl) faviconPreview = faviconUrl;"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Currency</label>
                            <input type="text" name="currency" value="{{ $platformSettings['currency'] ?? 'IDR' }}"
                                   placeholder="IDR, USD, etc"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Timezone</label>
                            <input type="text" name="timezone" value="{{ $platformSettings['timezone'] ?? 'Asia/Jakarta' }}"
                                   placeholder="Asia/Jakarta"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                            <x-icon name="bell" class="w-4 h-4 text-primary" />
                            Informasi Sistem (System Announcement)
                        </label>
                        <input type="text" 
                               name="system_announcement" 
                               value="{{ $platformSettings['system_announcement'] ?? '' }}"
                               placeholder="Contoh: Sabarr yah lagi ada pembaruan"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1.5 flex items-center gap-1.5">
                            <x-icon name="info" class="w-3 h-3" />
                            Informasi ini akan ditampilkan sebagai banner bergerak di bagian atas halaman
                        </p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Pengaturan Platform
                </button>
            </form>
        </div>

        <!-- Home Settings Tab -->
        <div x-show="activeTab === 'home'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="home" class="w-6 h-6 text-primary" />
                Pengaturan Halaman Home
            </h2>
            <form method="POST" action="{{ route('admin.settings.home') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Title</label>
                        <input type="text" name="hero_title" value="{{ $homeSettings['hero_title'] ?? 'Selamat Datang di' }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Judul utama di hero section</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle" value="{{ $homeSettings['hero_subtitle'] ?? 'Ebrystoree' }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Subtitle yang akan ditampilkan dengan highlight</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Description</label>
                        <textarea name="hero_description" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $homeSettings['hero_description'] ?? 'Marketplace terpercaya untuk produk digital dan jasa joki tugas berkualitas tinggi' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Hero Badge</label>
                        <input type="text" name="hero_badge" value="{{ $homeSettings['hero_badge'] ?? '✨ Platform Terpercaya #1 di Indonesia' }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Background Color</label>
                            <input type="text" name="home_background_color" value="{{ $homeSettings['home_background_color'] ?? '' }}"
                                   placeholder="#1a1a1a atau rgba(26, 26, 26, 1)"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Background Image URL</label>
                            <input type="text" name="home_background_image" value="{{ $homeSettings['home_background_image'] ?? '' }}"
                                   placeholder="/images/background.jpg atau URL lengkap"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Pengaturan Home
                </button>
            </form>
        </div>

        <!-- Banner Settings Tab -->
        <div x-show="activeTab === 'banner'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10" 
             x-data="{ 
                 bannerImagePreview: null,
                 showBannerUrlInput: false,
                 bannerImageUrl: '{{ $bannerSettings['banner_image'] ?? '' }}',
                 bannerEnabled: {{ $bannerSettings['banner_enabled'] ?? true ? 'true' : 'false' }},
                 init() {
                     @if(!empty($bannerSettings['banner_image_url']))
                     this.bannerImagePreview = '{{ $bannerSettings['banner_image_url'] }}';
                     @endif
                 }
             }">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="image" class="w-6 h-6 text-primary" />
                Pengaturan Banner Selamat Datang
            </h2>
            <form method="POST" action="{{ route('admin.settings.banner') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- Enable/Disable Banner -->
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Aktifkan Banner</label>
                            <p class="text-xs text-white/60">Tampilkan banner di halaman beranda</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="banner_enabled" value="1" 
                                   x-model="bannerEnabled"
                                   {{ ($bannerSettings['banner_enabled'] ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>

                    <!-- Banner Image Upload -->
                    <div>
                        <label class="block text-sm font-medium mb-2">Gambar Banner</label>
                        <div class="space-y-3">
                            <!-- File Upload -->
                            <div class="relative">
                                <input type="file" 
                                       name="banner_image_file" 
                                       id="banner_image_file"
                                       accept="image/jpeg,image/jpg,image/png,image/webp"
                                       @change="
                                           const file = $event.target.files[0];
                                           if (file) {
                                               bannerImagePreview = URL.createObjectURL(file);
                                               showBannerUrlInput = false;
                                               document.getElementById('banner_url_input').value = '';
                                           }
                                       "
                                       class="hidden">
                                <label for="banner_image_file" 
                                       class="flex items-center justify-center w-full h-40 sm:h-48 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                                    <div class="text-center px-4">
                                        <x-icon name="image" class="w-10 h-10 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors" />
                                        <p class="text-sm font-medium text-white/80 group-hover:text-white">
                                            <span class="text-primary">Klik untuk upload</span> atau drag & drop
                                        </p>
                                        <p class="text-xs text-white/50 mt-1">JPEG, PNG, WebP (Max 5MB)</p>
                                    </div>
                                </label>
                            </div>
                            
                            <!-- Preview & Current Banner -->
                            <div class="glass p-4 rounded-lg border border-white/10">
                                <p class="text-xs text-white/60 mb-3">Preview:</p>
                                <div class="relative w-full h-48 sm:h-64 rounded-lg overflow-hidden bg-white/5 border border-white/10">
                                    <template x-if="bannerImagePreview">
                                        <img :src="bannerImagePreview" alt="Banner Preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!bannerImagePreview">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <x-icon name="image" class="w-16 h-16 text-white/40" />
                                        </div>
                                    </template>
                                </div>
                                <div class="mt-3 flex items-center justify-between">
                                    <p class="text-xs text-white/60" x-text="bannerImagePreview ? 'Banner dipilih' : 'Belum ada banner'"></p>
                                    <button type="button" 
                                            @click="bannerImagePreview = null; document.getElementById('banner_image_file').value = '';"
                                            x-show="bannerImagePreview"
                                            class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                        Hapus Preview
                                    </button>
                                </div>
                            </div>
                            
                            <!-- URL Input (Alternative) -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="showBannerUrlInput = !showBannerUrlInput"
                                        class="text-xs text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                                    <x-icon name="link" class="w-3 h-3" />
                                    <span x-text="showBannerUrlInput ? 'Sembunyikan URL' : 'Atau masukkan URL'"></span>
                                </button>
                            </div>
                            <div x-show="showBannerUrlInput" x-cloak class="space-y-2">
                                <input type="text" 
                                       name="banner_image" 
                                       id="banner_url_input"
                                       :value="bannerImageUrl"
                                       placeholder="https://example.com/banner.jpg atau URL lengkap"
                                       @input="bannerImageUrl = $event.target.value; if (bannerImageUrl) bannerImagePreview = bannerImageUrl;"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 focus:outline-none focus:border-primary text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Banner Content -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Judul Banner</label>
                            <input type="text" name="banner_title" value="{{ $bannerSettings['banner_title'] ?? 'Selamat Datang di Ebrystoree' }}"
                                   placeholder="Selamat Datang di Ebrystoree"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Teks Tombol</label>
                            <input type="text" name="banner_button_text" value="{{ $bannerSettings['banner_button_text'] ?? 'Mulai Belanja' }}"
                                   placeholder="Mulai Belanja"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Subtitle/Deskripsi</label>
                        <textarea name="banner_subtitle" rows="2"
                                  placeholder="Marketplace terpercaya untuk produk digital dan jasa joki tugas"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $bannerSettings['banner_subtitle'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Link Tombol</label>
                        <input type="text" name="banner_button_link" value="{{ $bannerSettings['banner_button_link'] ?? route('products.index') }}"
                               placeholder="{{ route('products.index') }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">URL tujuan saat tombol diklik</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Opacity Overlay</label>
                        <input type="number" name="banner_overlay_opacity" 
                               value="{{ $bannerSettings['banner_overlay_opacity'] ?? 0.4 }}"
                               min="0" max="1" step="0.1"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Tingkat kegelapan overlay (0 = transparan, 1 = gelap penuh)</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Pengaturan Banner
                </button>
            </form>
        </div>

        <!-- Contact Info Tab -->
        <div x-show="activeTab === 'contact'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="phone" class="w-6 h-6 text-primary" />
                Informasi Kontak
            </h2>
            <form method="POST" action="{{ route('admin.settings.contact') }}">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ $contactInfo['email'] ?? '' }}"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Telepon</label>
                            <input type="text" name="phone" value="{{ $contactInfo['phone'] ?? '' }}"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ $contactInfo['whatsapp'] ?? '' }}"
                               placeholder="6281234567890 (tanpa +)"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Alamat</label>
                        <textarea name="address" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $contactInfo['address'] ?? '' }}</textarea>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Kontak
                </button>
            </form>
        </div>

        <!-- Bank Account Tab -->
        <div x-show="activeTab === 'bank'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="bank" class="w-6 h-6 text-primary" />
                Informasi Rekening Bank
            </h2>
            <form method="POST" action="{{ route('admin.settings.bankAccount') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" value="{{ $bankAccountInfo['bank_name'] ?? '' }}"
                               placeholder="Bank BCA, Bank Mandiri, dll"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Nomor Rekening</label>
                            <input type="text" name="bank_account_number" value="{{ $bankAccountInfo['bank_account_number'] ?? '' }}"
                                   placeholder="1234567890"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Atas Nama</label>
                            <input type="text" name="bank_account_name" value="{{ $bankAccountInfo['bank_account_name'] ?? '' }}"
                                   placeholder="PT Ebrystoree"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">QRIS Code (URL atau Base64)</label>
                        <textarea name="qris_code" rows="3" placeholder="URL QRIS atau Base64 string"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $bankAccountInfo['qris_code'] ?? '' }}</textarea>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Rekening Bank
                </button>
            </form>
        </div>

        <!-- Commission Settings Tab -->
        <div x-show="activeTab === 'commission'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="dollar" class="w-6 h-6 text-primary" />
                Pengaturan Komisi
            </h2>
            <form method="POST" action="{{ route('admin.settings.commission') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="glass p-6 rounded-lg border border-white/10">
                        <label class="block text-sm font-medium mb-2">Komisi Produk (%)</label>
                        <input type="number" name="commission_product" value="{{ $commissionSettings['commission_product'] ?? 10 }}"
                               min="0" max="100" step="0.1" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-2">Persentase komisi untuk setiap penjualan produk digital</p>
                    </div>
                    <div class="glass p-6 rounded-lg border border-white/10">
                        <label class="block text-sm font-medium mb-2">Komisi Jasa (%)</label>
                        <input type="number" name="commission_service" value="{{ $commissionSettings['commission_service'] ?? 15 }}"
                               min="0" max="100" step="0.1" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-2">Persentase komisi untuk setiap penjualan jasa joki tugas</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Komisi
                </button>
            </form>
        </div>

        <!-- Limits Tab -->
        <div x-show="activeTab === 'limits'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="chart" class="w-6 h-6 text-primary" />
                Pengaturan Limit
            </h2>
            <form method="POST" action="{{ route('admin.settings.limits') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Top-up (Rp)</label>
                        <input type="number" name="min_topup_amount" value="{{ $limits['min_topup_amount'] ?? 10000 }}"
                               min="1000" step="1000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Max Top-up (Rp)</label>
                        <input type="number" name="max_topup_amount" value="{{ $limits['max_topup_amount'] ?? 10000000 }}"
                               min="1000" step="100000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Order (Rp)</label>
                        <input type="number" name="min_order_amount" value="{{ $limits['min_order_amount'] ?? 1000 }}"
                               min="0" step="100" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Min Withdrawal (Rp)</label>
                        <input type="number" name="min_withdrawal_amount" value="{{ $limits['min_withdrawal_amount'] ?? 50000 }}"
                               min="0" step="10000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Max Withdrawal (Rp)</label>
                        <input type="number" name="max_withdrawal_amount" value="{{ $limits['max_withdrawal_amount'] ?? 50000000 }}"
                               min="0" step="1000000" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Limit
                </button>
            </form>
        </div>

        <!-- Email Settings Tab -->
        <div x-show="activeTab === 'email'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="mail" class="w-6 h-6 text-primary" />
                Pengaturan Email
            </h2>
            <form method="POST" action="{{ route('admin.settings.email') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Admin Email</label>
                        <input type="email" name="admin_email" value="{{ $emailSettings['admin_email'] ?? '' }}"
                               placeholder="admin@ebrystoree.com"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">From Name</label>
                            <input type="text" name="email_from_name" value="{{ $emailSettings['email_from_name'] ?? 'Ebrystoree' }}"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">From Address</label>
                            <input type="email" name="email_from_address" value="{{ $emailSettings['email_from_address'] ?? 'noreply@ebrystoree.com' }}"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Email
                </button>
            </form>
        </div>

        <!-- SEO Settings Tab -->
        <div x-show="activeTab === 'seo'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="search" class="w-6 h-6 text-primary" />
                Pengaturan SEO
            </h2>
            <form method="POST" action="{{ route('admin.settings.seo') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $seoSettings['meta_title'] ?? '' }}"
                               placeholder="Ebrystoree - Marketplace Produk Digital & Jasa Joki Tugas"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                                  placeholder="Deskripsi untuk SEO (150-160 karakter)"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $seoSettings['meta_description'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ $seoSettings['meta_keywords'] ?? '' }}"
                               placeholder="keyword1, keyword2, keyword3"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan SEO
                </button>
            </form>
        </div>

        <!-- Feature Flags Tab -->
        <div x-show="activeTab === 'features'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="lightning" class="w-6 h-6 text-primary" />
                Pengaturan Fitur
            </h2>
            <form method="POST" action="{{ route('admin.settings.features') }}">
                @csrf
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Wallet</label>
                            <p class="text-xs text-white/60">Aktifkan sistem wallet untuk top-up dan pembayaran</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_wallet" value="1" 
                                   {{ ($featureFlags['enable_wallet'] ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Bank Transfer</label>
                            <p class="text-xs text-white/60">Aktifkan metode pembayaran transfer bank</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_bank_transfer" value="1" 
                                   {{ ($featureFlags['enable_bank_transfer'] ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable QRIS</label>
                            <p class="text-xs text-white/60">Aktifkan metode pembayaran QRIS</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_qris" value="1" 
                                   {{ ($featureFlags['enable_qris'] ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border border-white/10">
                        <div>
                            <label class="font-semibold">Enable Seller Registration</label>
                            <p class="text-xs text-white/60">Aktifkan pendaftaran seller baru</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="enable_seller_registration" value="1" 
                                   {{ ($featureFlags['enable_seller_registration'] ?? true) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 glass rounded-lg border-2 border-yellow-500/30 bg-yellow-500/5">
                        <div>
                            <label class="font-semibold text-yellow-400">Maintenance Mode</label>
                            <p class="text-xs text-white/60">Mode maintenance akan menampilkan banner dan disable checkout</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                   {{ ($featureFlags['maintenance_mode'] ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-white/20 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                        </label>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Fitur
                </button>
            </form>
        </div>

        <!-- Business Hours Tab -->
        <div x-show="activeTab === 'hours'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="clock" class="w-6 h-6 text-primary" />
                Jam Operasional
            </h2>
            <form method="POST" action="{{ route('admin.settings.businessHours') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Jam Buka</label>
                        <input type="time" name="open" value="{{ $businessHours['open'] ?? '09:00' }}" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jam Tutup</label>
                        <input type="time" name="close" value="{{ $businessHours['close'] ?? '21:00' }}" required
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Jam Operasional
                </button>
            </form>
        </div>

        <!-- Owner Settings Tab -->
        <div x-show="activeTab === 'owner'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="user" class="w-6 h-6 text-primary" />
                Owner & Founder Settings
            </h2>
            <form method="POST" action="{{ route('admin.settings.owner') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Owner</label>
                        <input type="text" name="owner_name" value="{{ $ownerSettings['owner_name'] ?? 'Febryanus Tambing' }}"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Nama lengkap owner/founder</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Jabatan/Title</label>
                        <input type="text" name="owner_title" value="{{ $ownerSettings['owner_title'] ?? 'Owner & Founder' }}"
                               placeholder="Owner & Founder"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        <p class="text-xs text-white/60 mt-1">Jabatan yang akan ditampilkan (contoh: Owner & Founder)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Deskripsi</label>
                        <textarea name="owner_description" rows="4"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">{{ $ownerSettings['owner_description'] ?? '' }}</textarea>
                        <p class="text-xs text-white/60 mt-1">Deskripsi tentang owner (akan ditampilkan di halaman About)</p>
                    </div>

                    <div x-data="{ 
                        photoPreview: '',
                        showPreview: false,
                        currentPhoto: @js($ownerSettings['owner_photo'] ?? ''),
                        hasCurrentPhoto: @js(!empty($ownerSettings['owner_photo'])),
                        handleFileSelect(event) {
                            const file = event.target.files[0];
                            if (file) {
                                if (!file.type.startsWith('image/')) {
                                    window.dispatchEvent(new CustomEvent('toast', { 
                                        detail: { 
                                            message: 'File harus berupa gambar!', 
                                            type: 'error' 
                                        } 
                                    }));
                                    event.target.value = '';
                                    return;
                                }
                                if (file.size > 5 * 1024 * 1024) {
                                    window.dispatchEvent(new CustomEvent('toast', { 
                                        detail: { 
                                            message: 'Ukuran file maksimal 5MB!', 
                                            type: 'error' 
                                        } 
                                    }));
                                    event.target.value = '';
                                    return;
                                }
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.photoPreview = e.target.result;
                                    this.showPreview = true;
                                };
                                reader.readAsDataURL(file);
                            }
                        },
                        removePreview() {
                            this.photoPreview = '';
                            this.showPreview = false;
                            document.getElementById('owner_photo_file').value = '';
                        }
                    }">
                        <label class="block text-sm font-medium mb-2">Foto Owner</label>
                        
                        <!-- File Input -->
                        <div class="mb-3">
                            <label for="owner_photo_file" class="cursor-pointer">
                                <div class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 hover:bg-white/10 hover:border-primary/40 transition-all duration-300 flex items-center justify-center gap-3">
                                    <x-icon name="camera" class="w-5 h-5 text-primary" />
                                    <span class="text-sm font-medium text-white/90">
                                        <span x-show="!showPreview">Pilih Foto</span>
                                        <span x-show="showPreview">Ganti Foto</span>
                                    </span>
                                </div>
                            </label>
                            <input type="file" 
                                   id="owner_photo_file" 
                                   name="owner_photo_file" 
                                   accept="image/*"
                                   @change="handleFileSelect($event)"
                                   class="hidden">
                            <p class="text-xs text-white/60 mt-2">Format: JPG, PNG, atau GIF. Maksimal 5MB</p>
                        </div>

                        <!-- Preview (New File Selected) -->
                        <div x-show="showPreview" class="mt-4">
                            <p class="text-xs text-white/60 mb-2">Preview foto baru:</p>
                            <div class="relative inline-block">
                                <img :src="photoPreview" 
                                     alt="Owner Photo Preview" 
                                     class="w-40 h-40 rounded-full object-cover border-4 border-primary/30 shadow-lg">
                                <button type="button" 
                                        @click="removePreview()"
                                        class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors shadow-lg">
                                    <x-icon name="x" class="w-4 h-4 text-white" />
                                </button>
                            </div>
                        </div>

                        <!-- Current Photo (if exists and no new file selected) -->
                        <div x-show="!showPreview && hasCurrentPhoto" class="mt-4">
                            <p class="text-xs text-white/60 mb-2">Foto saat ini:</p>
                            <div class="relative inline-block">
                                <img :src="currentPhoto" 
                                     alt="Current Owner Photo" 
                                     class="w-40 h-40 rounded-full object-cover border-4 border-primary/30 shadow-lg">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Badges (JSON Array)</label>
                        <textarea name="owner_badges" rows="3"
                                  class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm">{{ json_encode($ownerSettings['owner_badges'] ?? ['Visionary Leader', 'Innovation Driven', 'Customer Focused'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</textarea>
                        <p class="text-xs text-white/60 mt-1">Array JSON untuk badges (contoh: ["Visionary Leader","Innovation Driven","Customer Focused"])</p>
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                    <x-icon name="save" class="w-5 h-5" />
                    Simpan Owner Settings
                </button>
            </form>
        </div>

        <!-- Featured Promosi Tab -->
        <div x-show="activeTab === 'featured'" x-cloak class="glass p-6 sm:p-8 rounded-xl border border-white/10">
            <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                <x-icon name="award" class="w-6 h-6 text-primary" />
                Pengaturan Featured Promosi
            </h2>
            <p class="text-white/60 mb-6 text-sm">Kelola produk/jasa yang ditampilkan sebagai featured di halaman home untuk promosi berbayar.</p>
            
            @php
                $featuredItems = \App\Models\FeaturedItem::with(['product', 'service'])->ordered()->get();
                $products = \App\Models\Product::where('is_active', true)->where('is_draft', false)->orderBy('title')->get();
                $services = \App\Models\Service::where('status', 'active')->orderBy('title')->get();
            @endphp

            <!-- List Featured Items -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Featured Items Aktif</h3>
                @if($featuredItems->count() > 0)
                <div class="space-y-4">
                    @foreach($featuredItems as $item)
                    <div class="glass p-4 rounded-lg border border-white/10">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-2 py-1 bg-primary/20 text-primary rounded text-xs font-semibold">{{ strtoupper($item->type) }}</span>
                                    <span class="text-white/90 font-semibold">{{ $item->display_title }}</span>
                                    @if($item->is_active)
                                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">Aktif</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded text-xs">Nonaktif</span>
                                    @endif
    </div>
                                <p class="text-sm text-white/60">{{ $item->title ?? 'Tidak ada custom title' }}</p>
</div>
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('admin.settings.featured.delete', $item->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Hapus featured item ini?')"
                                            class="px-3 py-1.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-sm transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-white/60 text-sm">Belum ada featured items.</p>
                @endif
            </div>

            <!-- Add New Featured Item -->
            <div class="border-t border-white/10 pt-6">
                <h3 class="text-lg font-semibold mb-4">Tambah Featured Item Baru</h3>
                <form method="POST" action="{{ route('admin.settings.featured') }}" x-data="{ type: 'product', itemId: '' }">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Tipe</label>
                                <select name="type" 
                                        x-model="type"
                                            @change="itemId = ''"
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="product">Produk</option>
                                    <option value="service">Jasa</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Pilih Item</label>
                                <!-- Products Dropdown -->
                                <select name="item_id" 
                                        x-model="itemId"
                                        x-show="type === 'product'"
                                        required
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>
                                <!-- Services Dropdown -->
                                <select name="item_id" 
                                        x-model="itemId"
                                        x-show="type === 'service'"
                                        required
                                        class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                                    <option value="">-- Pilih Jasa --</option>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Custom Title/Banner Text (Opsional)</label>
                            <input type="text" 
                                   name="title" 
                                   placeholder="Contoh: HQ Aged Domain (Premium Backlinks)"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                            <p class="text-xs text-white/60 mt-1">Jika kosong, akan menggunakan title dari produk/jasa</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description (Opsional)</label>
                            <textarea name="description" 
                                      rows="2"
                                      placeholder="Deskripsi singkat untuk featured item"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Header BG Color</label>
                                <input type="color" 
                                       name="header_bg_color" 
                                       value="#8B4513"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Banner BG Color</label>
                                <input type="color" 
                                       name="banner_bg_color" 
                                       value="#DC2626"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Main BG Color</label>
                                <input type="color" 
                                       name="main_bg_color" 
                                       value="#000000"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Main Text Color</label>
                                <input type="color" 
                                       name="main_text_color" 
                                       value="#FFFFFF"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Accent Color</label>
                                <input type="color" 
                                       name="accent_color" 
                                       value="#FCD34D"
                                       class="w-full h-10 rounded-lg border border-white/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Features (JSON Array - Opsional)</label>
                            <textarea name="features" 
                                      rows="3"
                                      placeholder='["High DR/DA/PA | Low Spam Score", "Age 10+ Years | Bebas Nawala", "HQ Backlink"]'
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm"></textarea>
                            <p class="text-xs text-white/60 mt-1">Array JSON untuk features list (contoh: ["Feature 1", "Feature 2"])</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Footer Text (Opsional)</label>
                            <input type="text" 
                                   name="footer_text" 
                                   placeholder="Contoh: DR+ DA+ PA+"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Sort Order</label>
                                <input type="number" 
                                       name="sort_order" 
                                       value="0"
                                       min="0"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           checked
                                           class="rounded border-white/20">
                                    <span>Aktif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="mt-6 px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                        <x-icon name="save" class="w-5 h-5" />
                        Tambah Featured Item
                    </button>
                </form>
            </div>
        </div>

        <!-- API Settings Tab -->
        <div x-show="activeTab === 'api'" x-cloak class="space-y-6">
            <!-- Khfy Store API Settings -->
            <div class="glass p-6 sm:p-8 rounded-xl border border-white/10">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <x-icon name="link" class="w-6 h-6 text-primary" />
                    API Settings - Khfy Store
                </h2>
                <form method="POST" action="{{ route('admin.settings.api') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2 flex items-center gap-2">
                                <x-icon name="lock" class="w-4 h-4 text-primary" />
                                <span>API Key Khfy Store *</span>
                            </label>
                            <input type="text" 
                                   name="khfy_api_key" 
                                   value="{{ session('updated_api_key') ?? $settings['khfy_api_key'] ?? '' }}"
                                   placeholder="Masukkan API key dari panel.khfy-store.com"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 bg-white/5 focus:outline-none focus:border-primary font-mono text-sm"
                                   required>
                            <p class="text-xs text-white/60 mt-2">
                                API key ini digunakan untuk integrasi dengan Khfy Store (pembelian kuota XL).
                                Dapatkan API key di <strong>Profile → Pengaturan</strong> di panel.khfy-store.com
                            </p>
                        </div>

                        <div class="glass p-4 rounded-lg border border-yellow-500/30 bg-yellow-500/10">
                            <div class="flex items-start gap-3">
                                <x-icon name="info" class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" />
                                <div class="flex-1">
                                    <h3 class="font-semibold text-yellow-400 mb-2">Webhook URL</h3>
                                    <p class="text-sm text-white/80 mb-2">
                                        Salin URL berikut dan pasang di <strong>Profile → Pengaturan → Webhook</strong> di panel.khfy-store.com:
                                    </p>
                                    <div class="flex items-center gap-2">
                                        <input type="text" 
                                               readonly
                                               value="{{ route('quota.webhook') }}"
                                               class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 font-mono text-xs sm:text-sm"
                                               id="webhook-url">
                                        <button type="button" 
                                                onclick="navigator.clipboard.writeText('{{ route('quota.webhook') }}').then(() => window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Webhook URL berhasil disalin!', type: 'success'}})))"
                                                class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold flex items-center gap-2 touch-target">
                                            <x-icon name="copy" class="w-4 h-4" />
                                            <span>Salin</span>
                                        </button>
                                    </div>
                                    <p class="text-xs text-white/60 mt-2">
                                        Webhook ini akan menerima update status transaksi secara real-time dari Khfy Store.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 justify-end">
                            <button type="button" 
                                    onclick="syncProducts()"
                                    class="px-6 py-3 glass glass-hover rounded-lg transition-colors font-semibold flex items-center gap-2 text-white"
                                    id="sync-products-btn">
                                <x-icon name="refresh" class="w-5 h-5" />
                                <span id="sync-products-text">Add Produk Otomatis</span>
                            </button>
                            <button type="submit" 
                                    class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold flex items-center gap-2">
                                <x-icon name="save" class="w-5 h-5" />
                                Simpan Khfy Store Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Xendit & Escrow Settings -->
            <div class="glass p-6 sm:p-8 rounded-xl border border-white/10">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
                        <x-icon name="credit-card" class="w-6 h-6 text-primary" />
                        Xendit & Escrow Configuration
                    </h2>
                    <p class="text-white/60 text-sm">Kelola integrasi payment gateway Xendit dan sistem escrow/rekber</p>
                </div>

                <form action="{{ route('admin.settings.xendit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Xendit Configuration -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <x-icon name="credit-card" class="w-5 h-5 text-primary" />
                            Xendit API Configuration
                        </h3>

                        <!-- Secret Key -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white">
                                Secret Key <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   name="xendit_secret_key" 
                                   value="{{ $xenditSettings['secret_key'] ?? '' }}"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed @error('xendit_secret_key') border-red-500/50 @else border-white/10 @enderror"
                                   placeholder="xnd_development_..."
                                   required>
                            @error('xendit_secret_key')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-white/60">Dapatkan dari Xendit Dashboard > Settings > API Keys</p>
                        </div>

                        <!-- Public Key (Optional) -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white">
                                Public Key (Optional)
                            </label>
                            <input type="text" 
                                   name="xendit_public_key" 
                                   value="{{ $xenditSettings['public_key'] ?? '' }}"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed @error('xendit_public_key') border-red-500/50 @else border-white/10 @enderror"
                                   placeholder="xnd_public_...">
                            @error('xendit_public_key')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Webhook Token -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white">
                                Webhook Token <span class="text-red-400">*</span>
                            </label>
                            <input type="password" 
                                   name="xendit_webhook_token" 
                                   value="{{ $xenditSettings['webhook_token'] ?? '' }}"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed @error('xendit_webhook_token') border-red-500/50 @else border-white/10 @enderror"
                                   placeholder="xnd_webhook_..."
                                   required>
                            @error('xendit_webhook_token')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-white/60">Dapatkan dari Xendit Dashboard > Settings > Webhooks</p>
                        </div>

                        <!-- API URL -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white">
                                API URL
                            </label>
                            <input type="url" 
                                   name="xendit_api_url" 
                                   value="{{ $xenditSettings['api_url'] ?? 'https://api.xendit.co' }}"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed @error('xendit_api_url') border-red-500/50 @else border-white/10 @enderror"
                                   placeholder="https://api.xendit.co">
                            @error('xendit_api_url')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-white/60">Default: https://api.xendit.co (untuk production) atau https://api.xendit.co (untuk sandbox)</p>
                        </div>

                        <!-- Production Mode -->
                        <div class="flex items-center gap-3">
                            <input type="checkbox" 
                                   name="xendit_production" 
                                   value="1"
                                   {{ ($xenditSettings['production'] ?? false) ? 'checked' : '' }}
                                   id="xendit_production"
                                   class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary focus:ring-2">
                            <label for="xendit_production" class="text-sm font-medium text-white cursor-pointer">
                                Production Mode
                            </label>
                        </div>
                        <p class="text-xs text-white/60 ml-7">Centang jika menggunakan production keys (untuk transaksi real)</p>
                    </div>

                    <!-- Escrow Configuration -->
                    <div class="space-y-4 pt-6 border-t border-white/10">
                        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                            <x-icon name="shield" class="w-5 h-5 text-primary" />
                            Escrow / Rekber Configuration
                        </h3>

                        <!-- Hold Period -->
                        <div>
                            <label class="block text-sm font-medium mb-2 text-white">
                                Hold Period (Hari) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" 
                                   name="escrow_hold_period_days" 
                                   value="{{ $xenditSettings['escrow_hold_period_days'] ?? 7 }}"
                                   min="1"
                                   max="30"
                                   class="w-full border rounded-lg px-4 py-2.5 bg-white/5 focus:outline-none focus:border-primary disabled:opacity-50 disabled:cursor-not-allowed @error('escrow_hold_period_days') border-red-500/50 @else border-white/10 @enderror"
                                   required>
                            @error('escrow_hold_period_days')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-white/60">Lama waktu dana ditahan di escrow sebelum auto-release (1-30 hari). Default: 7 hari</p>
                        </div>

                        <!-- Info Box -->
                        <div class="p-4 rounded-lg bg-blue-500/10 border border-blue-500/30">
                            <div class="flex items-start gap-3">
                                <x-icon name="info" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" />
                                <div class="text-sm text-white/90">
                                    <p class="font-semibold mb-1">Cara Kerja Escrow:</p>
                                    <ul class="list-disc list-inside space-y-1 text-white/70">
                                        <li>Dana ditahan di escrow setelah payment verified</li>
                                        <li>Early release: Buyer confirm completion → release immediately</li>
                                        <li>Auto release: Release otomatis setelah hold period selesai</li>
                                        <li>Dispute: Freeze escrow sampai admin resolve</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Webhook URL Info -->
                    <div class="p-4 rounded-lg bg-green-500/10 border border-green-500/30">
                        <div class="flex items-start gap-3">
                            <x-icon name="info" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <h3 class="font-semibold text-green-400 mb-2">Xendit Webhook URL</h3>
                                <p class="text-sm text-white/80 mb-2">
                                    Salin URL berikut dan pasang di <strong>Xendit Dashboard > Settings > Webhooks</strong>:
                                </p>
                                <div class="flex items-center gap-2">
                                    <input type="text" 
                                           readonly
                                           value="{{ route('xendit.webhook') }}"
                                           class="flex-1 glass border border-white/10 rounded-lg px-4 py-2 bg-white/5 font-mono text-xs sm:text-sm"
                                           id="xendit-webhook-url">
                                    <button type="button" 
                                            onclick="navigator.clipboard.writeText('{{ route('xendit.webhook') }}').then(() => window.dispatchEvent(new CustomEvent('toast', {detail: {message: 'Webhook URL berhasil disalin!', type: 'success'}})))"
                                            class="px-4 py-2 glass glass-hover rounded-lg text-sm font-semibold flex items-center gap-2 touch-target">
                                        <x-icon name="copy" class="w-4 h-4" />
                                        <span>Salin</span>
                                    </button>
                                </div>
                                <p class="text-xs text-white/60 mt-2">
                                    Webhook ini akan menerima update status pembayaran secara real-time dari Xendit.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-primary hover:bg-primary-dark rounded-lg font-semibold transition-all hover:scale-105 shadow-lg shadow-primary/20">
                            Simpan Xendit & Escrow Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function syncProducts() {
    const btn = document.getElementById('sync-products-btn');
    const text = document.getElementById('sync-products-text');
    const originalText = text.textContent;
    
    // Disable button dan show loading
    btn.disabled = true;
    text.textContent = 'Memproses...';
    
    fetch('{{ route('admin.settings.syncProducts') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: data.message || `Berhasil menambahkan ${data.count || 0} produk`,
                    type: 'success'
                }
            }));
            
            // Optional: Redirect to quota page after successful sync
            if (data.redirect && data.count > 0) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        } else {
            window.dispatchEvent(new CustomEvent('toast', {
                detail: {
                    message: data.message || 'Gagal menambahkan produk',
                    type: 'error'
                }
            }));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.dispatchEvent(new CustomEvent('toast', {
            detail: {
                message: 'Terjadi kesalahan saat menambahkan produk',
                type: 'error'
            }
        }));
    })
    .finally(() => {
        btn.disabled = false;
        text.textContent = originalText;
    });
}
</script>
@endsection
