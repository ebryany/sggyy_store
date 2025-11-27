# 🚀 Deployment OSS ke Server VPS

## ❌ Error yang Terjadi

```
Class "OSS\OssClient" not found
```

## 🔍 Penyebab

Setelah push ke GitHub, package `aliyuncs/oss-sdk-php` belum terinstall di server VPS karena:
1. `composer.json` sudah di-update dengan dependency baru
2. Tapi `composer install` belum dijalankan di server
3. Package belum terdownload ke `vendor/` directory

## ✅ Solusi

### Step 1: SSH ke Server VPS

```bash
ssh user@your-server-ip
cd /path/to/your/project
```

### Step 2: Pull Latest Changes dari GitHub

```bash
git pull origin main
```

### Step 3: Install Dependencies dengan Composer

```bash
composer install --no-dev --optimize-autoloader
```

**Penjelasan flags:**
- `--no-dev`: Skip development dependencies (untuk production)
- `--optimize-autoloader`: Optimize autoloader untuk performa lebih baik

### Step 4: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 5: Test OSS Connection

```bash
php test_oss_simple.php
```

## 🔧 Jika Masih Error

### Check 1: Pastikan Composer Berjalan dengan Benar

```bash
composer --version
composer install --verbose
```

### Check 2: Pastikan Package Terinstall

```bash
composer show aliyuncs/oss-sdk-php
```

Jika tidak muncul, berarti package belum terinstall.

### Check 3: Check composer.json

```bash
cat composer.json | grep aliyuncs
```

Harus muncul:
```json
"aliyuncs/oss-sdk-php": "^2.7"
```

### Check 4: Check vendor Directory

```bash
ls -la vendor/aliyuncs/
```

Harus ada folder `oss-sdk-php`.

## 📝 Full Deployment Script

Buat file `deploy_oss.sh` di server:

```bash
#!/bin/bash

echo "🚀 Deploying OSS Implementation..."

# Pull latest changes
echo "📥 Pulling latest changes..."
git pull origin main

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear cache
echo "🧹 Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test OSS
echo "🧪 Testing OSS..."
php test_oss_simple.php

echo "✅ Deployment complete!"
```

Jalankan:
```bash
chmod +x deploy_oss.sh
./deploy_oss.sh
```

## ⚠️ Catatan Penting

1. **Pastikan `.env` sudah dikonfigurasi dengan benar:**
   ```
   OSS_ACCESS_KEY_ID=your_key
   OSS_ACCESS_KEY_SECRET=your_secret
   OSS_BUCKET=your_bucket
   OSS_ENDPOINT=oss-ap-southeast-1.aliyuncs.com
   OSS_URL=https://your-bucket.oss-ap-southeast-1.aliyuncs.com
   ```

2. **Pastikan `bootstrap/providers.php` sudah ter-update:**
   ```php
   return [
       App\Providers\AppServiceProvider::class,
       App\Providers\FilesystemServiceProvider::class,
   ];
   ```

3. **Network di server VPS biasanya tidak ada masalah firewall seperti di Windows lokal**

## 🎯 Quick Fix Command

Jika ingin cepat, jalankan ini di server:

```bash
cd /path/to/project && \
git pull origin main && \
composer install --no-dev --optimize-autoloader && \
php artisan config:clear && \
php test_oss_simple.php
```

