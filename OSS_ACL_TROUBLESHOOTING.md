# OSS ACL Troubleshooting Guide

## Masalah: AccessDenied Error

Jika Anda masih mendapatkan error `AccessDenied` setelah upload file, ikuti langkah-langkah berikut:

### 1. Fix File yang Sudah Ada

File yang diupload **sebelum** perbaikan ACL perlu di-fix manual:

```bash
# Fix semua file di folder settings (favicon, logo, banners)
php artisan oss:fix-acl

# Fix file spesifik
php artisan oss:fix-acl "settings/favicon/AxFx0rua6gseV4P4SVfxwFQkvQYLQqCnN2dWECyI.png"

# Fix semua file dengan prefix tertentu
php artisan oss:fix-acl --prefix="settings/favicon"
php artisan oss:fix-acl --prefix="avatars"
php artisan oss:fix-acl --prefix="owner/photos"
```

### 2. Verifikasi ACL File

Setelah fix, verifikasi dengan mengakses URL file di browser. File seharusnya bisa diakses tanpa error.

### 3. File Baru

File baru yang diupload **setelah** perbaikan akan otomatis memiliki ACL `public-read` karena:
- `OssAdapter` sekarang set ACL saat upload
- ACL juga di-set setelah upload sebagai double-check
- Default visibility di config adalah `public`

### 4. Profile Picture

Profile picture sekarang akan diupload ke OSS jika dikonfigurasi. Pastikan:
- OSS credentials sudah benar di `.env`
- Default filesystem disk adalah `oss` di `config/filesystems.php`
- Jalankan `php artisan config:clear` setelah mengubah config

### 5. Jika Masih Error

Jika masih mendapatkan error setelah fix:

1. **Cek Bucket ACL**: Pastikan bucket mengizinkan ACL object-level
   - Login ke Alibaba Cloud Console
   - Buka OSS bucket settings
   - Pastikan "Object ACL" enabled

2. **Cek IAM Permissions**: Pastikan Access Key memiliki permission:
   - `oss:PutObjectAcl`
   - `oss:GetObjectAcl`
   - `oss:PutObject`

3. **Test Manual**: Test set ACL manual menggunakan OSS console atau SDK

4. **Check Logs**: Cek Laravel logs untuk error detail:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Perubahan yang Dilakukan

1. **`app/Filesystem/OssAdapter.php`**:
   - Set ACL di header saat upload (`x-oss-object-acl`)
   - Double-check: Set ACL setelah upload menggunakan `putObjectAcl()`
   - Default visibility adalah `public`

2. **`app/Services/ProfileService.php`**:
   - Update `updateAvatar()`, `updateStoreBanner()`, `updateStoreLogo()` untuk menggunakan `StorageService`
   - File sekarang akan diupload ke OSS jika dikonfigurasi

3. **`config/filesystems.php`**:
   - Default visibility untuk OSS disk adalah `public`

4. **`app/Console/Commands/FixOssFileAcl.php`**:
   - Command untuk fix ACL file yang sudah ada

## Catatan Penting

- **File baru**: Otomatis memiliki ACL `public-read`
- **File lama**: Perlu di-fix manual dengan `php artisan oss:fix-acl`
- **Profile picture**: Sekarang diupload ke OSS (bukan lagi hardcoded ke `public` disk)
- **Cache**: Setelah upload, clear cache jika file tidak muncul: `php artisan cache:clear`

