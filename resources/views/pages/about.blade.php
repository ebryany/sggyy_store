@extends('layouts.app')

@section('title', 'Tentang Kami - ' . $siteName)

@section('content')
<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="relative overflow-hidden py-12 sm:py-16 lg:py-24">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-transparent"></div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block mb-3 sm:mb-4">
                    <span class="px-3 py-1.5 sm:px-4 sm:py-2 bg-primary/20 text-primary rounded-full text-xs sm:text-sm font-semibold border border-primary/30">
                        Tentang Kami
                    </span>
                </div>
                <h1 class="text-2xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 sm:mb-6 text-white leading-tight px-2">
                    Platform Terpercaya untuk
                    <span class="text-primary block sm:inline">Produk Digital & Jasa</span>
                </h1>
                <p class="text-base sm:text-lg lg:text-xl text-white/70 max-w-2xl mx-auto leading-relaxed px-2">
                    {{ $siteName }} adalah platform terpercaya yang menghubungkan kebutuhan digital dan akademik masyarakat Indonesia dengan komitmen memberikan layanan terbaik.
                </p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16 items-center">
                    <!-- Content -->
                    <div class="order-2 lg:order-1">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6 text-white">
                            Siapa Kami?
                        </h2>
                        <div class="space-y-3 sm:space-y-4 text-white/80 leading-relaxed text-sm sm:text-base">
                            <p>
                                {{ $siteName }} didirikan dengan visi untuk menjadi platform terdepan dalam menyediakan produk digital berkualitas dan jasa profesional untuk kebutuhan akademik dan bisnis.
                            </p>
                            <p>
                                Kami memahami bahwa setiap pelanggan memiliki kebutuhan yang unik. Oleh karena itu, kami berkomitmen untuk memberikan solusi yang tepat, cepat, dan terpercaya dengan standar kualitas tinggi.
                            </p>
                            <p>
                                Dengan tim yang berpengalaman dan dedikasi tinggi, kami terus berinovasi untuk memberikan pengalaman terbaik bagi setiap pengguna platform kami.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Visual/Stats -->
                    <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:gap-6 order-1 lg:order-2">
                        <div class="p-4 sm:p-5 lg:p-6 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary mb-1 sm:mb-2">100%</div>
                            <div class="text-white/70 text-xs sm:text-sm">Terpercaya</div>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary mb-1 sm:mb-2">24/7</div>
                            <div class="text-white/70 text-xs sm:text-sm">Support</div>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary mb-1 sm:mb-2">1000+</div>
                            <div class="text-white/70 text-xs sm:text-sm">Pelanggan</div>
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6 rounded-xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                            <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-primary mb-1 sm:mb-2">5★</div>
                            <div class="text-white/70 text-xs sm:text-sm">Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white/5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="grid md:grid-cols-2 gap-6 sm:gap-8 lg:gap-12">
                    <!-- Mission -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-primary/20 flex items-center justify-center mb-4 sm:mb-6 border border-primary/30">
                            <x-icon name="target" class="w-6 h-6 sm:w-7 sm:h-7 text-primary" />
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4 text-white">Misi Kami</h3>
                        <p class="text-white/80 leading-relaxed text-sm sm:text-base">
                            Menyediakan platform terpercaya yang menghubungkan kebutuhan digital dan akademik dengan layanan berkualitas tinggi, transparan, dan mudah diakses oleh semua kalangan.
                        </p>
                    </div>
                    
                    <!-- Vision -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-primary/20 flex items-center justify-center mb-4 sm:mb-6 border border-primary/30">
                            <x-icon name="eye" class="w-6 h-6 sm:w-7 sm:h-7 text-primary" />
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4 text-white">Visi Kami</h3>
                        <p class="text-white/80 leading-relaxed text-sm sm:text-base">
                            Menjadi platform terdepan di Indonesia dalam menyediakan produk digital dan jasa profesional yang dapat diandalkan, dengan fokus pada kepuasan pelanggan dan inovasi berkelanjutan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-8 sm:mb-12">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4 text-white">Nilai-Nilai Kami</h2>
                    <p class="text-white/70 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto px-2">
                        Prinsip-prinsip yang menjadi fondasi dalam setiap layanan yang kami berikan
                    </p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @php
                        $values = [
                            ['icon' => 'shield', 'title' => 'Terpercaya', 'desc' => 'Keamanan dan kepercayaan adalah prioritas utama kami'],
                            ['icon' => 'star', 'title' => 'Berkualitas', 'desc' => 'Setiap produk dan layanan melalui proses quality control ketat'],
                            ['icon' => 'clock', 'title' => 'Tepat Waktu', 'desc' => 'Komitmen untuk menyelesaikan setiap order sesuai deadline'],
                            ['icon' => 'users', 'title' => 'Pelayanan Prima', 'desc' => 'Tim support siap membantu 24/7 untuk kebutuhan Anda'],
                            ['icon' => 'refresh', 'title' => 'Inovatif', 'desc' => 'Terus berinovasi untuk memberikan solusi terbaik'],
                            ['icon' => 'heart', 'title' => 'Berdedikasi', 'desc' => 'Komitmen penuh untuk kepuasan setiap pelanggan'],
                        ];
                    @endphp
                    
                    @foreach($values as $value)
                    <div class="p-4 sm:p-6 rounded-xl bg-white/5 border border-white/10 hover:border-primary/30 transition-all group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-primary/20 flex items-center justify-center mb-3 sm:mb-4 border border-primary/30 group-hover:bg-primary/30 transition-colors">
                            <x-icon name="{{ $value['icon'] }}" class="w-5 h-5 sm:w-6 sm:h-6 text-primary" />
                        </div>
                        <h4 class="text-lg sm:text-xl font-semibold mb-2 text-white">{{ $value['title'] }}</h4>
                        <p class="text-white/70 text-xs sm:text-sm leading-relaxed">{{ $value['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Founder Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white/5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-8 sm:mb-12">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4 text-white">Pendiri</h2>
                    <p class="text-white/70 text-sm sm:text-base lg:text-lg max-w-2xl mx-auto px-2">
                        Kenali sosok di balik {{ $siteName }}
                    </p>
                </div>
                
                <div class="flex flex-col lg:flex-row items-center gap-6 sm:gap-8 lg:gap-12 max-w-4xl mx-auto">
                    <!-- Photo -->
                    <div class="flex-shrink-0 w-full sm:w-auto">
                        <div class="relative mx-auto" style="max-width: 200px; width: 100%;">
                            @if(!empty($ownerSettings['owner_photo']))
                                <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 p-1.5 border border-primary/30">
                                    <div class="rounded-xl overflow-hidden">
                                        <img src="{{ $ownerSettings['owner_photo'] }}" 
                                             alt="{{ $ownerSettings['owner_name'] ?? 'Owner' }}" 
                                             class="w-full h-auto block"
                                             style="max-width: 100%; max-height: 400px; object-fit: contain;"
                                             loading="lazy">
                                    </div>
                                </div>
                            @else
                                <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 p-1.5 border border-primary/30">
                                    <div class="w-full aspect-[3/4] rounded-xl bg-gradient-to-br from-dark via-dark/95 to-dark/90 flex items-center justify-center">
                                        <x-icon name="user" class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-primary" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center lg:text-left w-full">
                        <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3 text-white">
                            {{ $ownerSettings['owner_name'] ?? 'Febryanus Tambing' }}
                        </h3>
                        <p class="text-lg sm:text-xl lg:text-2xl font-semibold text-primary mb-4 sm:mb-6">
                            {{ $ownerSettings['owner_title'] ?? 'Owner & Founder' }}
                        </p>
                        
                        <div class="mb-4 sm:mb-6">
                            <p class="text-white/80 leading-relaxed text-sm sm:text-base">
                                @if(!empty($ownerSettings['owner_description']))
                                    @php
                                        $description = str_replace('{site_name}', $siteName, $ownerSettings['owner_description']);
                                        $description = str_replace('{{site_name}}', $siteName, $description);
                                    @endphp
                                    {{ $description }}
                                @else
                                    Dengan dedikasi dan visi yang kuat, saya membangun {{ $siteName }} sebagai platform terpercaya yang menghubungkan kebutuhan digital dan akademik masyarakat Indonesia. Komitmen kami adalah memberikan layanan terbaik dengan kualitas premium dan pelayanan yang memuaskan.
                                @endif
                            </p>
                        </div>
                        
                        <!-- Badges -->
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 sm:gap-3">
                            @foreach($ownerSettings['owner_badges'] ?? ['Visionary Leader', 'Innovation Driven', 'Customer Focused'] as $badge)
                            <span class="px-3 py-1.5 sm:px-4 sm:py-2 bg-white/5 border border-white/10 rounded-lg text-xs sm:text-sm font-medium text-white/90">
                                {{ $badge }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA Section -->
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="text-center p-6 sm:p-8 lg:p-12 rounded-2xl bg-gradient-to-br from-primary/20 via-primary/10 to-primary/5 border border-primary/30">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 sm:mb-4 text-white px-2">
                        Tertarik Bekerja Sama?
                    </h2>
                    <p class="text-white/70 text-sm sm:text-base lg:text-lg mb-6 sm:mb-8 max-w-2xl mx-auto px-2">
                        Hubungi kami untuk informasi lebih lanjut tentang produk dan layanan kami
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                        <a href="{{ route('home') }}" 
                           class="px-5 py-2.5 sm:px-6 sm:py-3 bg-primary hover:bg-primary-dark rounded-lg transition-colors font-semibold text-white inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                            <x-icon name="home" class="w-4 h-4 sm:w-5 sm:h-5" />
                            Kembali ke Beranda
                        </a>
                        @if(!empty($contactInfo['whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactInfo['whatsapp']) }}" 
                           target="_blank"
                           class="px-5 py-2.5 sm:px-6 sm:py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg transition-colors font-semibold text-white inline-flex items-center justify-center gap-2 text-sm sm:text-base">
                            <x-icon name="phone" class="w-4 h-4 sm:w-5 sm:h-5" />
                            Hubungi Kami
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
