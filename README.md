# Ebrystoree - Digital Products & Services Marketplace

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## 📋 Deskripsi

**Ebrystoree** adalah platform marketplace modern untuk produk digital dan jasa joki tugas akademik. Platform ini dirancang untuk menghubungkan seller dengan buyer dalam transaksi produk digital (plugin, template, script) dan jasa joki (penulisan essay, makalah, tugas).

## ✨ Fitur Utama

### 🛍️ Produk Digital
- **Katalog Produk**: Template, Plugin, Script, dan produk digital lainnya
- **Sistem Download**: Download otomatis setelah pembayaran terverifikasi
- **Manajemen Stok**: Tracking stok produk
- **Rating & Review**: Sistem rating dan review untuk produk
- **Kategori & Tag**: Organisasi produk dengan kategori dan tag
- **Featured Products**: Produk unggulan dengan custom banner

### 🎓 Jasa Joki
- **Katalog Jasa**: Penulisan essay, makalah, tugas, dll
- **Order Management**: Sistem manajemen order dengan progress tracking
- **Deadline Management**: Auto-set deadline berdasarkan durasi jasa
- **Deliverable Upload**: Upload hasil pekerjaan dengan preview
- **Revision System**: Sistem revisi untuk buyer
- **Auto-completion**: Auto-complete setelah 24 jam jika buyer tidak merespon

### 💰 Sistem Pembayaran
- **Multiple Payment Methods**: 
  - Saldo Wallet (instant)
  - Transfer Bank (manual verification)
  - QRIS (manual verification)
- **Payment Verification**: Admin dapat verifikasi pembayaran
- **Payment Timeout**: Auto-cancel jika tidak dibayar dalam 2 jam
- **Payment Proof Upload**: Upload bukti pembayaran

### 👥 User Management
- **Role-Based Access Control**: Admin, Seller, User
- **Seller Verification**: Sistem verifikasi seller dengan dokumen
- **Profile Management**: Profil lengkap dengan store page
- **Store Page**: Halaman store untuk setiap seller

### 💳 Wallet System
- **Top-up Wallet**: Top-up saldo dengan berbagai metode
- **Withdrawal**: Penarikan saldo untuk seller
- **Transaction History**: Riwayat transaksi lengkap
- **Balance Tracking**: Tracking saldo real-time

### 📊 Admin Dashboard
- **Dashboard Analytics**: Statistik lengkap platform
- **Order Management**: Manajemen semua order
- **Payment Verification**: Verifikasi pembayaran
- **User Management**: Manajemen user dan seller
- **Settings Management**: Pengaturan platform
- **Banner Management**: Manajemen banner homepage

### 🔔 Notifikasi
- **Real-time Notifications**: Notifikasi untuk semua aktivitas penting
- **Email Notifications**: Notifikasi via email (opsional)
- **Notification Center**: Pusat notifikasi untuk user

### 💬 Chat System
- **Direct Messaging**: Chat langsung antara buyer dan seller
- **Order Messages**: Pesan terkait order
- **File Attachments**: Upload file dalam chat

### ⭐ Rating & Review
- **Product Rating**: Rating untuk produk
- **Service Rating**: Rating untuk jasa
- **Review System**: Sistem review dengan komentar

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 12.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0+
- **Queue**: Database Queue (support Redis)
- **Cache**: File/Database Cache (support Redis)
- **Storage**: Local Storage / Alibaba Cloud OSS

### Frontend
- **CSS Framework**: Tailwind CSS 4.0
- **JavaScript**: Alpine.js 3.x
- **Build Tool**: Vite 6.x
- **Icons**: Custom Icon Component

### Security
- **Authentication**: Laravel Sanctum
- **Authorization**: Policy-based
- **File Upload Security**: Custom FileUploadSecurityService
- **Rate Limiting**: Built-in Laravel rate limiting
- **Security Logging**: Custom SecurityLogger

## 📦 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 20.x
- MySQL >= 8.0
- NPM / Yarn

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/ebryany/sggyy_store.git
cd sggyy_store
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebrystoree
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
# Create database (optional, if using MySQL)
php artisan db:create

# Run migrations
php artisan migrate

# Seed demo data (optional)
php artisan db:seed --class=DemoDataSeeder
```

### 6. Generate Slugs (if needed)
```bash
php artisan db:generate-slugs
```

### 7. Build Assets
```bash
npm run build
```

### 8. Create Storage Link
```bash
php artisan storage:link
```

### 9. Run Development Server
```bash
# Run all services (server, queue, logs, vite)
composer run dev

# Or run separately
php artisan serve
php artisan queue:work
npm run dev
```

## 📁 Project Structure

```
ebrystoree/
├── app/
│   ├── Console/Commands/      # Artisan commands
│   ├── Http/
│   │   ├── Controllers/       # Controllers
│   │   ├── Middleware/        # Custom middleware
│   │   └── Requests/           # Form requests
│   ├── Models/                # Eloquent models
│   ├── Policies/              # Authorization policies
│   ├── Providers/             # Service providers
│   └── Services/              # Business logic services
├── config/                     # Configuration files
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── public/                     # Public assets
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # CSS files
│   └── js/                    # JavaScript files
├── routes/                     # Route definitions
└── storage/                    # Storage files
```

## 🔧 Configuration

### Environment Variables

#### Application
```env
APP_NAME="Ebrystoree"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ebrystoree
DB_USERNAME=root
DB_PASSWORD=
```

#### Storage (OSS - Alibaba Cloud)
```env
FILESYSTEM_DISK=oss
OSS_ACCESS_KEY_ID=your_access_key
OSS_ACCESS_KEY_SECRET=your_secret_key
OSS_BUCKET=your-bucket-name
OSS_ENDPOINT=oss-ap-southeast-1.aliyuncs.com
OSS_REGION=ap-southeast-1
OSS_URL=https://your-bucket.oss-ap-southeast-1.aliyuncs.com
```

#### Queue
```env
QUEUE_CONNECTION=database
# Or use Redis: QUEUE_CONNECTION=redis
```

#### Cache
```env
CACHE_STORE=file
# Or use Redis: CACHE_STORE=redis
```

## 🎯 Key Features Detail

### Order Management
- **Status Flow**: 
  - `pending` → `paid` → `processing` → `waiting_confirmation` → `completed`
  - Support `needs_revision` dan `cancelled`
- **Progress Tracking**: Seller dapat update progress 0-100%
- **Deadline Management**: Auto-set deadline berdasarkan durasi jasa
- **Auto-completion**: Auto-complete setelah 24 jam jika buyer tidak merespon

### Payment System
- **Wallet Payment**: Instant verification
- **Bank Transfer/QRIS**: Manual verification by admin
- **Payment Timeout**: 2 hours untuk bank transfer/QRIS
- **Payment Proof**: Upload bukti pembayaran

### File Management
- **Secure Upload**: FileUploadSecurityService untuk validasi
- **Cloud Storage**: Support Alibaba Cloud OSS
- **Download Tracking**: Tracking download untuk produk digital
- **Signed URLs**: Secure download dengan signed URLs

## 📝 Artisan Commands

```bash
# Create MySQL database
php artisan db:create

# Migrate to MySQL
php artisan db:migrate-to-mysql

# Generate slugs for products/services
php artisan db:generate-slugs

# Process payment timeouts
php artisan payments:process-timeouts

# Process auto-completions
php artisan orders:process-auto-completions

# Check order deadlines
php artisan orders:check-deadlines
```

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=ExampleTest
```

## 📦 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure OSS storage
- [ ] Setup queue worker
- [ ] Configure cron jobs
- [ ] Setup SSL certificate
- [ ] Configure email settings
- [ ] Setup backup strategy

### Deployment Script
```bash
bash deploy.sh
```

Lihat [DEPLOYMENT.md](DEPLOYMENT.md) untuk detail lengkap deployment ke Alibaba Cloud.

## 🔐 Security Features

- **File Upload Security**: MIME type validation, file size limits, secure filename generation
- **Path Traversal Protection**: SecureFileService untuk prevent path traversal
- **Rate Limiting**: Built-in rate limiting untuk API dan routes
- **Security Logging**: Log semua aktivitas mencurigakan
- **Policy-based Authorization**: Fine-grained access control
- **CSRF Protection**: Built-in CSRF protection
- **SQL Injection Prevention**: Eloquent ORM dengan parameter binding

## 📊 Database Schema

### Main Tables
- `users` - User accounts (admin, seller, user)
- `products` - Digital products
- `services` - Jasa joki
- `orders` - Orders (products & services)
- `payments` - Payment records
- `ratings` - Product/service ratings
- `wallet_transactions` - Wallet transactions
- `seller_withdrawals` - Seller withdrawal requests
- `seller_verifications` - Seller verification requests
- `notifications` - User notifications
- `chats` & `chat_messages` - Chat system

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Febryanus Tambing**
- GitHub: [@ebryany](https://github.com/ebryany)
- Repository: [sggyy_store](https://github.com/ebryany/sggyy_store)

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- All contributors and users

## 📞 Support

Untuk pertanyaan atau dukungan, silakan buka [Issue](https://github.com/ebryany/sggyy_store/issues) di GitHub.

---

**Made with ❤️ using Laravel**
