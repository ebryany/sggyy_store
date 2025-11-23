<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding demo data...');
        
        // ===== CREATE ADMIN USER =====
        $this->command->info('👤 Creating admin user...');
        
        $admin = User::firstOrCreate(
            ['email' => 'admin@ebrystoree.com'],
            [
                'name' => 'Admin Ebrystoree',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'wallet_balance' => 1000000,
                'phone' => '081234567890',
                'address' => 'Jakarta, Indonesia',
            ]
        );
        
        $this->command->info("✅ Admin: {$admin->email} / password");
        
        // ===== CREATE SELLER USER =====
        $this->command->info('👨‍💼 Creating seller user...');
        
        $seller = User::firstOrCreate(
            ['email' => 'seller@ebrystoree.com'],
            [
                'name' => 'Seller Pro',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'seller',
                'wallet_balance' => 500000,
                'phone' => '081234567891',
                'address' => 'Bandung, Indonesia',
                'store_name' => 'Pro Digital Store',
                'store_description' => 'Professional seller specializing in digital products and academic services. 5+ years experience helping students with assignments.',
            ]
        );
        
        $this->command->info("✅ Seller: {$seller->email} / password");
        
        // ===== CREATE 10 PRODUCTS =====
        $this->command->info('📦 Creating 10 products...');
        
        $products = [
            [
                'title' => 'Template PPT Presentasi Profesional',
                'description' => 'Template PowerPoint modern dan profesional dengan 50+ slide designs. Cocok untuk presentasi bisnis, akademik, dan project. Mudah diedit dan customizable. Include master slides, infographic elements, charts, dan icons. Format: PPTX.',
                'price' => 75000,
                'category' => 'Template',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'E-Book: Panduan Lengkap Laravel 12',
                'description' => 'E-book komprehensif tentang Laravel 12 dengan 300+ halaman. Mulai dari basic hingga advanced topics. Include: Authentication, Database, API, Testing, Deployment. Bonus source code projects. Format: PDF + Source Code.',
                'price' => 150000,
                'category' => 'E-Book',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'Plugin WordPress Premium - SEO Optimizer',
                'description' => 'Plugin WordPress untuk optimasi SEO website Anda. Features: Auto meta tags, sitemap generator, keyword analyzer, broken link checker, social media integration. Lifetime updates. Compatible dengan WP 6.0+.',
                'price' => 250000,
                'category' => 'Plugin',
                'stock' => 50,
                'is_active' => true,
            ],
            [
                'title' => 'Source Code Website E-Commerce',
                'description' => 'Full source code website e-commerce built dengan Laravel + Vue.js. Features: Product management, shopping cart, payment gateway, order tracking, admin dashboard. Include database, documentation, dan video tutorial setup.',
                'price' => 500000,
                'category' => 'Source Code',
                'stock' => 25,
                'is_active' => true,
            ],
            [
                'title' => 'Template Excel Dashboard Analytics',
                'description' => 'Template Excel untuk business analytics dan reporting. Include: Sales dashboard, financial report, inventory tracker, customer analytics. Auto-calculation dengan formula dan macros. Compatible Excel 2016+.',
                'price' => 100000,
                'category' => 'Template',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'Kumpulan Icon Pack 10,000+ Icons',
                'description' => 'Massive icon pack dengan 10,000+ vector icons dalam berbagai kategori. Format: SVG, PNG, AI. Fully editable dan scalable. Include: Business, Education, Technology, Social Media, E-commerce icons. Commercial license included.',
                'price' => 200000,
                'category' => 'Design Assets',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'Template CV & Resume Kreatif',
                'description' => 'Set template CV/Resume modern dan eye-catching. 15 design variants dalam format Word, PSD, dan PDF. ATS-friendly format. Include cover letter templates. Perfect untuk fresh graduate dan profesional.',
                'price' => 50000,
                'category' => 'Template',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'Video Tutorial: Cara Membuat Website',
                'description' => '20 jam video tutorial step-by-step cara membuat website dari nol. Topics: HTML, CSS, JavaScript, PHP, MySQL, Laravel. Include project files dan quiz. Subtitle Bahasa Indonesia. Lifetime access.',
                'price' => 350000,
                'category' => 'Video Course',
                'stock' => 999,
                'is_active' => true,
            ],
            [
                'title' => 'Template Landing Page Bisnis',
                'description' => 'Template landing page responsive dengan 20+ sections. Built with Bootstrap 5 dan modern JavaScript. Include: Hero, Features, Pricing, Testimonial, Contact Form. SEO optimized. Full documentation.',
                'price' => 125000,
                'category' => 'Template',
                'stock' => 100,
                'is_active' => true,
            ],
            [
                'title' => 'Font Pack Premium - 50 Fonts',
                'description' => 'Koleksi 50 premium fonts untuk design project. Include: Sans-serif, Serif, Script, Display fonts. Format: OTF, TTF, WOFF. Commercial license untuk unlimited projects. Perfect untuk branding, logo, dan web design.',
                'price' => 175000,
                'category' => 'Design Assets',
                'stock' => 999,
                'is_active' => true,
            ],
        ];
        
        foreach ($products as $index => $productData) {
            $product = Product::create([
                'user_id' => $seller->id,
                'title' => $productData['title'],
                'description' => $productData['description'],
                'price' => $productData['price'],
                'category' => $productData['category'],
                'stock' => $productData['stock'],
                'sold_count' => rand(5, 50), // Random sales count for demo
                'is_active' => $productData['is_active'],
            ]);
            
            $this->command->info("  ✅ Product " . ($index + 1) . ": {$product->title}");
        }
        
        // ===== CREATE 5 SERVICES =====
        $this->command->info('🛍️ Creating 5 services...');
        
        $services = [
            [
                'title' => 'Jasa Pengerjaan Tugas Matematika',
                'description' => 'Layanan pengerjaan tugas matematika tingkat SMP, SMA, dan Kuliah. Dikerjakan oleh tutor berpengalaman. Include: Aljabar, Kalkulus, Statistika, Trigonometri. Garansi revisi unlimited. Hasil dijamin akurat dengan pembahasan lengkap.',
                'price' => 50000,
                'duration_hours' => 48,
                'status' => 'active',
            ],
            [
                'title' => 'Jasa Pembuatan PPT Presentasi',
                'description' => 'Jasa pembuatan slide PowerPoint profesional dan menarik. Cocok untuk presentasi kuliah, bisnis, dan proposal. Include: Design custom, animation, infographic. Revisi 3x. Format editable. Deadline 1-3 hari tergantung kompleksitas.',
                'price' => 100000,
                'duration_hours' => 72,
                'status' => 'active',
            ],
            [
                'title' => 'Jasa Penulisan Essay & Makalah',
                'description' => 'Layanan penulisan essay, makalah, dan paper akademik berkualitas tinggi. Dikerjakan oleh penulis profesional. Anti-plagiarisme guaranteed. Include: Research, citation, proofreading. Format sesuai panduan (APA, MLA, Chicago).',
                'price' => 150000,
                'duration_hours' => 120,
                'status' => 'active',
            ],
            [
                'title' => 'Jasa Coding & Programming',
                'description' => 'Jasa pengerjaan tugas programming untuk berbagai bahasa: Python, Java, C++, PHP, JavaScript. Include: Debug code, dokumentasi, penjelasan algoritma. Cocok untuk tugas kuliah dan project. Fast response & komunikatif.',
                'price' => 200000,
                'duration_hours' => 96,
                'status' => 'active',
            ],
            [
                'title' => 'Jasa Translate Dokumen (EN-ID)',
                'description' => 'Layanan translate dokumen profesional dari English ke Indonesia atau sebaliknya. Dikerjakan oleh translator bersertifikat. Maintain format dan context. Cocok untuk: Jurnal, paper, artikel, buku, legal document. Per 1000 kata.',
                'price' => 75000,
                'duration_hours' => 48,
                'status' => 'active',
            ],
        ];
        
        foreach ($services as $index => $serviceData) {
            $service = Service::create([
                'user_id' => $seller->id,
                'title' => $serviceData['title'],
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'duration_hours' => $serviceData['duration_hours'],
                'status' => $serviceData['status'],
                'completed_count' => rand(5, 30), // Random completed count for demo
            ]);
            
            $this->command->info("  ✅ Service " . ($index + 1) . ": {$service->title}");
        }
        
        // ===== SUMMARY =====
        $this->command->newLine();
        $this->command->info('✨ Demo data seeding completed!');
        $this->command->newLine();
        $this->command->table(
            ['Account Type', 'Email', 'Password', 'Role'],
            [
                ['Admin', 'admin@ebrystoree.com', 'password', 'Admin'],
                ['Seller', 'seller@ebrystoree.com', 'password', 'Seller'],
            ]
        );
        $this->command->newLine();
        $this->command->info("📦 Created 10 products");
        $this->command->info("🛍️ Created 5 services");
        $this->command->newLine();
        $this->command->warn("⚠️  Default password untuk semua akun: password");
        $this->command->warn("⚠️  Jangan lupa ganti password di production!");
    }
}
