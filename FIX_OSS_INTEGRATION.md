# 🔧 Fix OSS Integration untuk Icon/Logo/Favicon

## ❌ Masalah

Icon/logo/favicon yang diupload di server tidak menggunakan OSS, masih tersimpan di local storage.

## 🔍 Penyebab

1. **SettingsService** menggunakan `config('filesystems.default')` langsung
2. Di server, `.env` mungkin belum set `FILESYSTEM_DISK=oss`
3. URL generation tidak konsisten antara local dan OSS

## ✅ Solusi yang Diterapkan

### 1. Update SettingsService untuk menggunakan StorageService

**File:** `app/Services/SettingsService.php`

- `uploadLogo()` - Sekarang menggunakan `StorageService::store()`
- `uploadFavicon()` - Sekarang menggunakan `StorageService::store()`
- `getStorageUrl()` - Improved untuk handle OSS URL dengan benar
- Delete operations - Menggunakan `StorageService::delete()`

### 2. Update SettingController

**File:** `app/Http/Controllers/SettingController.php`

- Owner photo upload - Menggunakan `StorageService::store()`

### 3. Konfigurasi Server

Pastikan di server `.env` ada:

```env
FILESYSTEM_DISK=oss

OSS_ACCESS_KEY_ID=your_key
OSS_ACCESS_KEY_SECRET=your_secret
OSS_BUCKET=your_bucket
OSS_ENDPOINT=oss-ap-southeast-1.aliyuncs.com
OSS_URL=https://your-bucket.oss-ap-southeast-1.aliyuncs.com
```

## 🚀 Deployment Steps

### Di Server VPS:

```bash
# 1. Pull latest changes
git pull origin main

# 2. Install dependencies (jika belum)
composer install --no-dev --optimize-autoloader

# 3. Update .env
nano .env
# Pastikan FILESYSTEM_DISK=oss dan OSS credentials sudah benar

# 4. Clear cache
php artisan config:clear
php artisan cache:clear

# 5. Test OSS
php test_oss_simple.php
```

## ✅ Verifikasi

Setelah deploy, test upload icon:

1. Login ke admin panel
2. Go to Settings
3. Upload new logo/favicon
4. Check URL yang dihasilkan - harus OSS URL (https://bucket.oss-endpoint.com/...)
5. Check di OSS bucket - file harus ada di `settings/logo/` atau `settings/favicon/`

## 📝 Catatan

- `StorageService` sudah punya fallback logic ke `public` disk jika OSS gagal
- URL generation sekarang konsisten untuk local dan OSS
- File yang sudah terupload di local storage tetap bisa diakses (backward compatible)

