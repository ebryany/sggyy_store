<footer class="glass border-t-2 border-white/20 mt-12 sm:mt-16 lg:mt-20 pb-safe shadow-2xl" style="background: rgba(14, 14, 16, 0.85);">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
            <!-- Deskripsi -->
            <div class="sm:col-span-2 lg:col-span-1">
                <h3 class="text-xl lg:text-2xl font-bold mb-4 text-primary glow-sm">Ebrystoree</h3>
                <p class="text-white/80 text-sm lg:text-base leading-relaxed">
                    Marketplace terpercaya untuk produk digital dan jasa joki tugas. 
                    Solusi lengkap untuk kebutuhan digital dan akademik Anda.
                </p>
            </div>
            
            <!-- Navigasi -->
            <div>
                <h4 class="font-semibold mb-4 text-base lg:text-lg text-white flex items-center gap-2">
                    <x-icon name="globe" class="w-5 h-5 text-primary" />
                    Navigasi
                </h4>
                <ul class="space-y-3 text-sm lg:text-base text-white/70">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-all duration-300 hover:translate-x-1 inline-flex items-center gap-2"><x-icon name="home" class="w-4 h-4" /> Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-primary transition-all duration-300 hover:translate-x-1 inline-flex items-center gap-2"><x-icon name="package" class="w-4 h-4" /> Produk Digital</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-primary transition-all duration-300 hover:translate-x-1 inline-flex items-center gap-2"><x-icon name="shopping-bag" class="w-4 h-4" /> Jasa Joki Tugas</a></li>
                    <li><a href="#" class="hover:text-primary transition-all duration-300 hover:translate-x-1 inline-flex items-center gap-2"><x-icon name="info" class="w-4 h-4" /> Tentang Kami</a></li>
                </ul>
            </div>
            
            <!-- Kontak -->
            <div>
                <h4 class="font-semibold mb-4 text-base lg:text-lg text-white flex items-center gap-2">
                    <x-icon name="phone" class="w-5 h-5 text-primary" />
                    Kontak
                </h4>
                <ul class="space-y-3 text-sm lg:text-base text-white/70">
                    @php
                        $settingsService = app(\App\Services\SettingsService::class);
                        $contactInfo = $settingsService->getContactInfo();
                        $businessHours = $settingsService->getBusinessHours();
                    @endphp
                    <li class="flex items-center space-x-2">
                        <x-icon name="mail" class="w-4 h-4 text-primary flex-shrink-0" />
                        <span>{{ $contactInfo['email'] ?: 'support@ebrystoree.com' }}</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <x-icon name="phone" class="w-4 h-4 text-primary flex-shrink-0" />
                        <span>{{ $contactInfo['phone'] ?: '+62 812-3456-7890' }}</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <x-icon name="clock" class="w-4 h-4 text-primary flex-shrink-0" />
                        <span>{{ $businessHours['open'] ?? '09:00' }} - {{ $businessHours['close'] ?? '21:00' }} WIB</span>
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div class="sm:col-span-2 lg:col-span-1">
                <h4 class="font-semibold mb-4 text-base lg:text-lg text-white flex items-center gap-2">
                    <x-icon name="mail" class="w-5 h-5 text-primary" />
                    Newsletter
                </h4>
                <p class="text-white/70 text-sm lg:text-base mb-4 leading-relaxed">Dapatkan update produk dan promo terbaru</p>
                <form class="flex flex-col space-y-3" 
                      x-data="{ email: '', loading: false, message: '' }"
                      @submit.prevent="
                          loading = true;
                          setTimeout(() => {
                              message = 'Terima kasih! Email Anda telah terdaftar.';
                              email = '';
                              loading = false;
                              setTimeout(() => message = '', 3000);
                          }, 500);
                      ">
                    <input type="email" 
                           x-model="email"
                           placeholder="Email Anda" 
                           required
                           autocomplete="email"
                           class="glass border-2 border-white/20 rounded-lg px-4 py-3 sm:py-2.5 bg-white/5 focus:outline-none focus:border-primary focus:bg-white/10 text-base sm:text-sm text-white placeholder-white/50 transition-all duration-300">
                    <button type="submit" 
                            :disabled="loading"
                            class="bg-primary hover:bg-primary-dark rounded-lg px-4 py-3 sm:py-2.5 transition-all duration-300 hover:scale-105 touch-target text-sm sm:text-base font-semibold disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Subscribe</span>
                        <span x-show="loading">Memproses...</span>
                    </button>
                    <p x-show="message" x-text="message" class="text-green-400 text-xs sm:text-sm"></p>
                </form>
            </div>
        </div>
        
        <div class="border-t border-white/10 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-xs sm:text-sm text-white/60">
            <p>&copy; 2025 Ebrystoree — All rights reserved.</p>
        </div>
    </div>
</footer>

