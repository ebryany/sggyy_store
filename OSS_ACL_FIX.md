# Fix OSS File ACL (Access Denied Error)

## Masalah

File yang diupload ke OSS tidak bisa diakses karena error:
```
<Error>
<Code>AccessDenied</Code>
<Message>You have no right to access this object because of bucket acl.</Message>
</Error>
```

## Penyebab

File diupload ke OSS tanpa ACL `public-read`, sehingga tidak bisa diakses secara publik.

## Solusi

### 1. File Baru (Sudah Diperbaiki)

File baru yang diupload akan otomatis memiliki ACL `public-read` karena:
- `OssAdapter::write()` dan `writeStream()` sekarang set ACL `public-read` saat upload
- Default visibility di `config/filesystems.php` untuk OSS disk adalah `public`

### 2. File yang Sudah Ada (Perlu Fix Manual)

Untuk file yang sudah diupload sebelumnya, jalankan command:

```bash
# Fix semua file di folder settings (favicon, logo, banners)
php artisan oss:fix-acl

# Fix file spesifik
php artisan oss:fix-acl "settings/favicon/AxFx0rua6gseV4P4SVfxwFQkvQYLQqCnN2dWECyI.png"

# Fix semua file dengan prefix tertentu
php artisan oss:fix-acl --prefix="settings/favicon"
```

### 3. Verifikasi

Setelah fix, coba akses URL file di browser:
```
https://ebrystoree-assets.oss-ap-southeast-1.aliyuncs.com/settings/favicon/AxFx0rua6gseV4P4SVfxwFQkvQYLQqCnN2dWECyI.png
```

File seharusnya bisa diakses tanpa error.

## Perubahan yang Dilakukan

1. **`app/Filesystem/OssAdapter.php`**:
   - Method `write()` dan `writeStream()` sekarang set ACL `public-read` saat upload
   - Default visibility adalah `public` jika tidak ditentukan

2. **`config/filesystems.php`**:
   - Menambahkan `'visibility' => 'public'` untuk OSS disk

3. **`app/Console/Commands/FixOssFileAcl.php`**:
   - Command baru untuk fix ACL file yang sudah ada

## Catatan

- File baru yang diupload akan otomatis memiliki ACL `public-read`
- File yang sudah ada perlu di-fix manual menggunakan command `oss:fix-acl`
- Pastikan credentials OSS memiliki permission untuk set ACL

