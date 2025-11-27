@extends('layouts.user')

@section('title', 'Edit Profile - Ebrystoree')

@section('content')
<div class="space-y-4 sm:space-y-6 max-w-4xl mx-auto">
    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">Edit Profile</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Profile Info -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Informasi Profile</h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">Nama *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required
                                   autocomplete="name"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Email *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required
                                   autocomplete="email"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium mb-2">Nomor HP</label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   autocomplete="tel"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('phone')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="address" class="block text-sm font-medium mb-2">Alamat</label>
                            <textarea name="address" 
                                      id="address" 
                                      rows="3"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button type="submit" class="w-full px-4 sm:px-6 py-3 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold touch-target text-base">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            
            @if($user->role === 'seller' || $user->role === 'admin')
            <!-- Store Settings (Seller Only) -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="store" class="w-5 h-5" />
                    <span>Pengaturan Toko</span>
                </h2>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label for="store_name" class="block text-sm font-medium mb-2">Nama Toko</label>
                            <input type="text" 
                                   name="store_name" 
                                   id="store_name" 
                                   value="{{ old('store_name', $user->store_name) }}"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('store_name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="store_description" class="block text-sm font-medium mb-2">Deskripsi Toko</label>
                            <textarea name="store_description" 
                                      id="store_description" 
                                      rows="4"
                                      class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">{{ old('store_description', $user->store_description) }}</textarea>
                            @error('store_description')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="social_instagram" class="block text-sm font-medium mb-2">Instagram</label>
                                <input type="text" 
                                       name="social_instagram" 
                                       id="social_instagram" 
                                       value="{{ old('social_instagram', $user->social_instagram) }}"
                                       placeholder="@username"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            </div>
                            
                            <div>
                                <label for="social_twitter" class="block text-sm font-medium mb-2">Twitter</label>
                                <input type="text" 
                                       name="social_twitter" 
                                       id="social_twitter" 
                                       value="{{ old('social_twitter', $user->social_twitter) }}"
                                       placeholder="@username"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            </div>
                            
                            <div>
                                <label for="social_facebook" class="block text-sm font-medium mb-2">Facebook</label>
                                <input type="text" 
                                       name="social_facebook" 
                                       id="social_facebook" 
                                       value="{{ old('social_facebook', $user->social_facebook) }}"
                                       placeholder="Username"
                                       class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            </div>
                        </div>
                        
                        <div class="border-t border-white/10 pt-4">
                            <h3 class="text-base font-semibold mb-3">Informasi Bank</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label for="bank_name" class="block text-sm font-medium mb-2">Nama Bank</label>
                                    <input type="text" 
                                           name="bank_name" 
                                           id="bank_name" 
                                           value="{{ old('bank_name', $user->bank_name) }}"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                                </div>
                                
                                <div>
                                    <label for="bank_account_number" class="block text-sm font-medium mb-2">Nomor Rekening</label>
                                    <input type="text" 
                                           name="bank_account_number" 
                                           id="bank_account_number" 
                                           value="{{ old('bank_account_number', $user->bank_account_number) }}"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                                </div>
                                
                                <div>
                                    <label for="bank_account_name" class="block text-sm font-medium mb-2">Nama Pemilik Rekening</label>
                                    <input type="text" 
                                           name="bank_account_name" 
                                           id="bank_account_name" 
                                           value="{{ old('bank_account_name', $user->bank_account_name) }}"
                                           class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full px-4 sm:px-6 py-3 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold touch-target text-base">
                            Simpan Pengaturan Toko
                        </button>
                    </div>
                </form>
            </div>
            @endif
            
            <!-- Change Password -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Ubah Password</h2>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium mb-2">Password Saat Ini *</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   required
                                   autocomplete="current-password"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('current_password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="new_password" class="block text-sm font-medium mb-2">Password Baru *</label>
                            <input type="password" 
                                   name="new_password" 
                                   id="new_password" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                            @error('new_password')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium mb-2">Konfirmasi Password Baru *</label>
                            <input type="password" 
                                   name="new_password_confirmation" 
                                   id="new_password_confirmation" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password"
                                   class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        </div>
                        
                        <button type="submit" class="w-full px-4 sm:px-6 py-3 sm:py-4 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold touch-target text-base">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Avatar & Store Media Section -->
        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
            <!-- Avatar Section -->
            <div class="glass p-4 sm:p-6 rounded-lg lg:sticky lg:top-20">
                <h2 class="text-lg sm:text-xl font-semibold mb-4">Avatar</h2>
                <div class="text-center mb-4">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="{{ $user->name }}" 
                         class="w-24 h-24 sm:w-32 sm:h-32 rounded-full border-2 border-primary mx-auto mb-4">
                </div>
                
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" x-data="{ preview: null }">
                    @csrf
                    <div class="mb-4">
                        <input type="file" 
                               name="avatar" 
                               id="avatar" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               @change="preview = URL.createObjectURL($event.target.files[0])"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Format: JPEG, PNG, JPG, WEBP (Max 2MB)</p>
                    </div>
                    <template x-if="preview">
                        <img :src="preview" alt="Preview" class="w-full rounded-lg mb-4">
                    </template>
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors mb-2 touch-target text-sm sm:text-base">
                        Upload Avatar
                    </button>
                </form>
                
                @if($user->avatar)
                <form method="POST" action="{{ route('profile.avatar.remove') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 glass glass-hover rounded-lg text-red-400 touch-target text-sm sm:text-base">
                        Hapus Avatar
                    </button>
                </form>
                @endif
            </div>
            
            @if($user->role === 'seller' || $user->role === 'admin')
            <!-- Store Banner Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="image" class="w-5 h-5" />
                    <span>Banner Toko</span>
                </h2>
                <div class="mb-4">
                    @if($user->store_banner)
                        <img src="{{ asset('storage/' . $user->store_banner) }}" 
                             alt="Store Banner" 
                             class="w-full rounded-lg border border-white/10">
                    @else
                        <div class="w-full h-32 rounded-lg border border-white/10 bg-gradient-to-br from-primary/20 to-purple-900/20 flex items-center justify-center">
                            <x-icon name="image" class="w-12 h-12 text-white/30" />
                        </div>
                    @endif
                </div>
                
                <form method="POST" action="{{ route('profile.store.banner') }}" enctype="multipart/form-data" x-data="{ preview: null }">
                    @csrf
                    <div class="mb-4">
                        <input type="file" 
                               name="store_banner" 
                               id="store_banner" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               @change="preview = URL.createObjectURL($event.target.files[0])"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Format: JPEG, PNG, JPG, WEBP (Max 5MB)</p>
                        <p class="text-white/40 text-xs mt-1">Rekomendasi: 1200x400px</p>
                    </div>
                    <template x-if="preview">
                        <img :src="preview" alt="Preview" class="w-full rounded-lg mb-4">
                    </template>
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors mb-2 touch-target text-sm sm:text-base">
                        Upload Banner
                    </button>
                </form>
                
                @if($user->store_banner)
                <form method="POST" action="{{ route('profile.store.banner.remove') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 glass glass-hover rounded-lg text-red-400 touch-target text-sm sm:text-base">
                        Hapus Banner
                    </button>
                </form>
                @endif
            </div>
            
            <!-- Store Logo Section -->
            <div class="glass p-4 sm:p-6 rounded-lg">
                <h2 class="text-lg sm:text-xl font-semibold mb-4 flex items-center gap-2">
                    <x-icon name="award" class="w-5 h-5" />
                    <span>Logo Toko</span>
                </h2>
                <div class="mb-4 text-center">
                    @if($user->store_logo)
                        <img src="{{ asset('storage/' . $user->store_logo) }}" 
                             alt="Store Logo" 
                             class="w-32 h-32 rounded-full border-2 border-primary mx-auto">
                    @else
                        <div class="w-32 h-32 rounded-full border-2 border-white/10 bg-gradient-to-br from-primary/20 to-purple-900/20 flex items-center justify-center mx-auto">
                            <x-icon name="award" class="w-16 h-16 text-white/30" />
                        </div>
                    @endif
                </div>
                
                <form method="POST" action="{{ route('profile.store.logo') }}" enctype="multipart/form-data" x-data="{ preview: null }">
                    @csrf
                    <div class="mb-4">
                        <input type="file" 
                               name="store_logo" 
                               id="store_logo" 
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               @change="preview = URL.createObjectURL($event.target.files[0])"
                               class="w-full glass border border-white/10 rounded-lg px-4 py-3 sm:py-2 bg-white/5 focus:outline-none focus:border-primary text-base touch-target">
                        <p class="text-white/60 text-xs mt-1">Format: JPEG, PNG, JPG, WEBP (Max 2MB)</p>
                        <p class="text-white/40 text-xs mt-1">Rekomendasi: 400x400px (Square)</p>
                    </div>
                    <template x-if="preview">
                        <img :src="preview" alt="Preview" class="w-32 h-32 rounded-full border-2 border-primary mx-auto mb-4">
                    </template>
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 bg-primary hover:bg-primary-dark rounded-lg transition-colors mb-2 touch-target text-sm sm:text-base">
                        Upload Logo
                    </button>
                </form>
                
                @if($user->store_logo)
                <form method="POST" action="{{ route('profile.store.logo.remove') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 sm:py-2 glass glass-hover rounded-lg text-red-400 touch-target text-sm sm:text-base">
                        Hapus Logo
                    </button>
                </form>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection





