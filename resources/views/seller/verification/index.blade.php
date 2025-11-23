@extends('layouts.app')

@section('title', 'Seller Verification - Ebrystoree')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8 lg:py-12">
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2">Verifikasi Seller</h1>
            <p class="text-white/60 text-sm sm:text-base">Lengkapi informasi untuk menjadi seller di Ebrystoree</p>
        </div>
    
    @if($verification)
        @if($verification->status === 'pending')
        <!-- Pending Status -->
        <div class="glass p-6 rounded-lg border-yellow-500/30 border-2">
            <div class="flex items-center space-x-3 mb-4">
                <span class="text-4xl">⏳</span>
                <div>
                    <h2 class="text-2xl font-semibold text-yellow-400">Menunggu Verifikasi</h2>
                    <p class="text-white/60">Permintaan verifikasi Anda sedang dalam review oleh admin.</p>
                </div>
            </div>
            <p class="text-sm text-white/70">Dikirim pada: {{ $verification->created_at->format('d M Y, H:i') }}</p>
        </div>
        @elseif($verification->status === 'reviewing')
        <!-- Reviewing Status -->
        <div class="glass p-6 rounded-lg border-blue-500/30 border-2">
            <div class="flex items-center space-x-3 mb-4">
                <span class="text-4xl">🔍</span>
                <div>
                    <h2 class="text-2xl font-semibold text-blue-400">Sedang Direview</h2>
                    <p class="text-white/60">Dokumen Anda sedang diperiksa oleh tim admin.</p>
                </div>
            </div>
        </div>
        @elseif($verification->status === 'verified')
        <!-- Verified Status -->
        <div class="glass p-6 rounded-lg border-green-500/30 border-2">
            <div class="flex items-center space-x-3 mb-4">
                <span class="text-4xl">✅</span>
                <div>
                    <h2 class="text-2xl font-semibold text-green-400">Terverifikasi</h2>
                    <p class="text-white/60">Selamat! Akun seller Anda telah terverifikasi.</p>
                </div>
            </div>
            <p class="text-sm text-white/70">
                Diverifikasi pada: {{ $verification->verified_at->format('d M Y, H:i') }}
                @if($verification->verifier)
                oleh {{ $verification->verifier->name }}
                @endif
            </p>
        </div>
        @elseif($verification->status === 'rejected')
        <!-- Rejected Status -->
        <div class="glass p-6 rounded-lg border-red-500/30 border-2">
            <div class="flex items-center space-x-3 mb-4">
                <span class="text-4xl">❌</span>
                <div>
                    <h2 class="text-2xl font-semibold text-red-400">Ditolak</h2>
                    <p class="text-white/60">Verifikasi Anda ditolak. Silakan perbaiki dan kirim ulang.</p>
                </div>
            </div>
            @if($verification->rejection_reason)
            <div class="mt-4 p-4 bg-red-500/20 rounded-lg">
                <h3 class="font-semibold mb-2">Alasan Penolakan:</h3>
                <p class="text-white/80">{{ $verification->rejection_reason }}</p>
            </div>
            @endif
        </div>
        @endif
    @endif
    
    <!-- Verification Form -->
    @if(!$verification || $verification->status === 'rejected')
    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2">Form Verifikasi Seller</h2>
            <p class="text-white/70 text-sm sm:text-base">Lengkapi dokumen berikut untuk menjadi seller terverifikasi.</p>
        </div>
        
        <form method="POST" action="{{ route('seller.verification.store') }}" enctype="multipart/form-data"
              x-data="{ 
                  ktpPreview: null, 
                  photoPreview: null,
                  ktpFile: null,
                  photoFile: null
              }">
            @csrf
            
            <div class="space-y-6 sm:space-y-8">
                <!-- KTP Upload -->
                <div>
                    <label class="block text-sm font-semibold mb-3 text-white">
                        Foto KTP/ID Card <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" 
                               name="ktp_path" 
                               id="ktp_path"
                               accept="image/jpeg,image/png,image/jpg"
                               @change="
                                   ktpFile = $event.target.files[0];
                                   if (ktpFile) {
                                       ktpPreview = URL.createObjectURL(ktpFile);
                                   }
                               "
                               required
                               class="hidden">
                        <label for="ktp_path" 
                               class="flex items-center justify-center w-full h-32 sm:h-40 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                            <div class="text-center px-4">
                                <x-icon name="camera" class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors" />
                                <p class="text-sm sm:text-base font-medium text-white/80 group-hover:text-white">
                                    <span class="text-primary">Klik untuk memilih file</span> atau drag & drop
                                </p>
                                <p class="text-xs sm:text-sm text-white/50 mt-1">Format: JPEG, PNG, JPG (Max 2MB)</p>
                            </div>
                        </label>
                    </div>
                    @error('ktp_path')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                        <x-icon name="alert" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                    <div x-show="ktpPreview" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mt-4">
                        <div class="relative inline-block">
                            <img :src="ktpPreview" alt="KTP Preview" class="max-w-full sm:max-w-md rounded-xl border-2 border-white/20 shadow-lg">
                            <button type="button" 
                                    @click="ktpPreview = null; ktpFile = null; document.getElementById('ktp_path').value = ''"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                                <x-icon name="x" class="w-4 h-4" />
                            </button>
                        </div>
                        <p class="text-xs text-white/60 mt-2" x-text="ktpFile ? 'File: ' + ktpFile.name : ''"></p>
                    </div>
                </div>
                
                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-semibold mb-3 text-white">
                        Foto Diri <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" 
                               name="photo_path" 
                               id="photo_path"
                               accept="image/jpeg,image/png,image/jpg"
                               @change="
                                   photoFile = $event.target.files[0];
                                   if (photoFile) {
                                       photoPreview = URL.createObjectURL(photoFile);
                                   }
                               "
                               required
                               class="hidden">
                        <label for="photo_path" 
                               class="flex items-center justify-center w-full h-32 sm:h-40 border-2 border-dashed border-white/20 rounded-xl bg-white/5 hover:bg-white/10 hover:border-primary/50 transition-all duration-300 cursor-pointer group">
                            <div class="text-center px-4">
                                <x-icon name="camera" class="w-8 h-8 sm:w-10 sm:h-10 mx-auto mb-2 text-white/60 group-hover:text-primary transition-colors" />
                                <p class="text-sm sm:text-base font-medium text-white/80 group-hover:text-white">
                                    <span class="text-primary">Klik untuk memilih file</span> atau drag & drop
                                </p>
                                <p class="text-xs sm:text-sm text-white/50 mt-1">Format: JPEG, PNG, JPG (Max 2MB)</p>
                            </div>
                        </label>
                    </div>
                    @error('photo_path')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                        <x-icon name="alert" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                    <div x-show="photoPreview" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="mt-4">
                        <div class="relative inline-block">
                            <img :src="photoPreview" alt="Photo Preview" class="max-w-full sm:max-w-md rounded-xl border-2 border-white/20 shadow-lg">
                            <button type="button" 
                                    @click="photoPreview = null; photoFile = null; document.getElementById('photo_path').value = ''"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                                <x-icon name="x" class="w-4 h-4" />
                            </button>
                        </div>
                        <p class="text-xs text-white/60 mt-2" x-text="photoFile ? 'File: ' + photoFile.name : ''"></p>
                    </div>
                </div>
                
                <!-- Store Information Section -->
                <div class="border-t border-white/10 pt-6 mt-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                        <x-icon name="store" class="w-5 h-5 text-primary" />
                        <span>Informasi Toko</span>
                        <span class="text-white/50 text-xs font-normal">(Opsional)</span>
                    </h3>
                    <p class="text-white/60 text-sm mb-4">Lengkapi informasi toko Anda. Data ini akan ditampilkan di halaman profil toko.</p>
                    
                    <div class="space-y-4">
                        <!-- Store Name -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-white">
                                Nama Toko
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <x-icon name="store" class="w-5 h-5 text-white/40" />
                                </div>
                                <input type="text" 
                                       name="store_name" 
                                       value="{{ old('store_name', auth()->user()->store_name ?? '') }}"
                                       placeholder="Contoh: Toko Digital Pro"
                                       class="w-full pl-12 pr-4 py-3 border border-white/10 rounded-xl bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>
                            @error('store_name')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Store Description -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-white">
                                Tentang Toko
                            </label>
                            <textarea name="store_description" 
                                      rows="4"
                                      placeholder="Deskripsikan toko Anda, pengalaman, dan layanan yang ditawarkan..."
                                      class="w-full px-4 py-3 border border-white/10 rounded-xl bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none">{{ old('store_description', auth()->user()->store_description ?? '') }}</textarea>
                            <p class="text-xs text-white/50 mt-1">Maksimal 1000 karakter</p>
                            @error('store_description')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-white">
                                Nomor HP
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <x-icon name="mobile" class="w-5 h-5 text-white/40" />
                                </div>
                                <input type="tel" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       placeholder="081234567890"
                                       class="w-full pl-12 pr-4 py-3 border border-white/10 rounded-xl bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            </div>
                            @error('phone')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                        
                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-white">
                                Alamat
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-3">
                                    <x-icon name="map-pin" class="w-5 h-5 text-white/40" />
                                </div>
                                <textarea name="address" 
                                          rows="2"
                                          placeholder="Kota, Provinsi, Indonesia"
                                          class="w-full pl-12 pr-4 py-3 border border-white/10 rounded-xl bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all resize-none">{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            </div>
                            @error('address')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <x-icon name="alert" class="w-4 h-4" />
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Social Account -->
                <div>
                    <label class="block text-sm font-semibold mb-3 text-white">
                        Akun Sosial Media <span class="text-white/50 text-xs font-normal">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <x-icon name="link" class="w-5 h-5 text-white/40" />
                        </div>
                        <input type="text" 
                               name="social_account" 
                               value="{{ old('social_account', $verification->social_account ?? '') }}"
                               placeholder="Instagram: @username atau Twitter: @username"
                               class="w-full pl-12 pr-4 py-3 border border-white/10 rounded-xl bg-white/5 text-white placeholder:text-white/40 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                    @error('social_account')
                    <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                        <x-icon name="alert" class="w-4 h-4" />
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                
                <!-- Info Box -->
                <div class="bg-gradient-to-br from-blue-500/20 via-blue-500/10 to-blue-500/20 border border-blue-500/30 rounded-xl p-5 sm:p-6 backdrop-blur-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-500/30 flex items-center justify-center">
                            <x-icon name="info" class="w-5 h-5 text-blue-400" />
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-lg mb-3 text-blue-300">Informasi Penting</h4>
                            <ul class="space-y-2.5 text-sm sm:text-base text-white/90">
                                <li class="flex items-start gap-2.5">
                                    <x-icon name="check" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" />
                                    <span>Pastikan foto KTP jelas dan dapat dibaca</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <x-icon name="check" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" />
                                    <span>Foto diri harus menunjukkan wajah dengan jelas</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <x-icon name="check" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" />
                                    <span>Verifikasi biasanya memakan waktu 1-3 hari kerja</span>
                                </li>
                                <li class="flex items-start gap-2.5">
                                    <x-icon name="check" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" />
                                    <span>Setelah terverifikasi, Anda dapat mulai menjual produk dan jasa</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit" 
                        class="w-full px-6 py-4 bg-gradient-to-r from-primary via-pink-600 to-primary hover:from-primary-dark hover:via-pink-700 hover:to-primary-dark rounded-xl transition-all duration-300 font-bold text-white text-base sm:text-lg shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                    <x-icon name="shield" class="w-5 h-5" />
                    <span>Kirim Permintaan Verifikasi</span>
                </button>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection





