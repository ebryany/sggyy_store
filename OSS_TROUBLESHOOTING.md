# 🔧 OSS/IaaS Storage Troubleshooting Guide

## 📋 Daftar Masalah Umum

### 1. ❌ OSS tidak bekerja setelah deploy ke VPS

**Gejala:**
- File upload gagal
- Error: "Could not resolve host" atau "Connection timeout"
- File tidak muncul di OSS bucket

**Penyebab:**
1. ✅ **Konfigurasi .env tidak lengkap atau salah**
2. ✅ **Config cache tidak di-refresh setelah update .env**
3. ✅ **AWS SDK tidak terinstall**
4. ✅ **Firewall/Network blocking OSS endpoint**
5. ✅ **OSS credentials salah atau expired**
6. ✅ **Bucket tidak ada atau permission salah**

---

## 🔍 Langkah Troubleshooting

### Step 1: Check Konfigurasi .env

Pastikan semua OSS config ada di `.env`:

```env
FILESYSTEM_DISK=oss

# OSS Configuration (Alibaba Cloud)
OSS_ACCESS_KEY_ID=your_access_key_id
OSS_ACCESS_KEY_SECRET=your_access_key_secret
OSS_BUCKET=your-bucket-name
OSS_ENDPOINT=oss-ap-southeast-1.aliyuncs.com
OSS_REGION=ap-southeast-1
OSS_URL=https://your-bucket.oss-ap-southeast-1.aliyuncs.com
```

**⚠️ PENTING:**
- `OSS_ENDPOINT` harus sesuai dengan region bucket Anda
- `OSS_URL` harus menggunakan format: `https://{bucket}.{endpoint}`
- Jangan ada spasi atau quote di value

### Step 2: Clear Config Cache

Setelah update `.env`, **WAJIB** clear config cache:

```bash
php artisan config:clear
php artisan config:cache
```

**Kenapa?** Laravel cache config di production, jadi perubahan `.env` tidak langsung terdeteksi.

### Step 3: Test Storage Connection

Gunakan command untuk test:

```bash
php artisan storage:test oss
```

Command ini akan:
- ✅ Check semua config
- ✅ Test connection ke OSS
- ✅ Test write/read operation
- ✅ Test URL generation

### Step 4: Check AWS SDK Installation

Pastikan package terinstall:

```bash
composer show league/flysystem-aws-s3-v3
```

Jika tidak ada, install:

```bash
composer require league/flysystem-aws-s3-v3
composer dump-autoload
```

### Step 5: Check Network/Firewall

Test koneksi dari VPS ke OSS endpoint:

```bash
# Test DNS resolution
nslookup oss-ap-southeast-1.aliyuncs.com

# Test HTTP connection
curl -I https://oss-ap-southeast-1.aliyuncs.com

# Test dengan credentials (ganti dengan endpoint Anda)
curl -X GET "https://your-bucket.oss-ap-southeast-1.aliyuncs.com" \
  -H "Authorization: OSS your_access_key_id:signature"
```

**Jika gagal:**
- Check firewall rules di VPS
- Check security group di cloud provider
- Check apakah VPS bisa akses internet

### Step 6: Check OSS Credentials

1. **Login ke Alibaba Cloud Console**
2. **Go to OSS → Access Keys**
3. **Verify Access Key ID dan Secret masih valid**
4. **Check apakah Access Key punya permission untuk bucket**

**Permission yang dibutuhkan:**
- `oss:PutObject` - Upload file
- `oss:GetObject` - Download file
- `oss:DeleteObject` - Delete file
- `oss:ListObjects` - List files

### Step 7: Check Bucket Configuration

1. **Bucket exists?**
   ```bash
   # Check di Alibaba Cloud Console
   # OSS → Buckets → Your Bucket Name
   ```

2. **Bucket region match?**
   - Pastikan `OSS_REGION` sesuai dengan bucket region
   - Pastikan `OSS_ENDPOINT` sesuai dengan region

3. **Bucket permission?**
   - Public Read: Untuk file yang bisa diakses public
   - Private: Untuk file yang butuh signed URL

4. **CORS Configuration?**
   - Jika upload dari browser, pastikan CORS sudah dikonfigurasi

---

## 🛠️ Fix Common Issues

### Issue 1: "Could not resolve host"

**Fix:**
```bash
# Check DNS
cat /etc/resolv.conf

# Test DNS
nslookup oss-ap-southeast-1.aliyuncs.com

# Jika gagal, update DNS server
echo "nameserver 8.8.8.8" | sudo tee -a /etc/resolv.conf
echo "nameserver 8.8.4.4" | sudo tee -a /etc/resolv.conf
```

### Issue 2: "Connection timeout"

**Fix:**
```bash
# Check firewall
sudo ufw status
sudo iptables -L

# Allow outbound HTTPS
sudo ufw allow out 443/tcp

# Test connection
curl -v https://oss-ap-southeast-1.aliyuncs.com
```

### Issue 3: "Access Denied" atau "403 Forbidden"

**Fix:**
1. Check OSS credentials di `.env`
2. Check bucket permission di Alibaba Cloud Console
3. Check Access Key permissions
4. Regenerate Access Key jika perlu

### Issue 4: Config tidak ter-update setelah ubah .env

**Fix:**
```bash
# Clear semua cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

### Issue 5: File upload berhasil tapi URL tidak bisa diakses

**Fix:**
1. Check `OSS_URL` di `.env` - harus match dengan bucket URL
2. Check bucket permission - harus Public Read untuk public access
3. Check CORS configuration di bucket
4. Untuk private file, gunakan `temporaryUrl()` method

---

## 📝 Checklist Deployment

Sebelum deploy ke VPS, pastikan:

- [ ] ✅ `.env` sudah dikonfigurasi dengan benar
- [ ] ✅ OSS credentials valid dan punya permission
- [ ] ✅ Bucket sudah dibuat dan region match
- [ ] ✅ `composer install` sudah jalan (AWS SDK terinstall)
- [ ] ✅ `php artisan config:clear` sudah dijalankan
- [ ] ✅ `php artisan storage:test oss` berhasil
- [ ] ✅ VPS bisa akses internet dan OSS endpoint
- [ ] ✅ Firewall tidak block outbound HTTPS

---

## 🔄 Fallback ke Local Storage

Jika OSS tidak bisa digunakan, aplikasi akan otomatis fallback ke `public` disk.

**Tapi untuk production, sebaiknya:**
1. Fix OSS configuration
2. Atau gunakan local storage dengan symlink:
   ```bash
   php artisan storage:link
   ```

---

## 📞 Support

Jika masih bermasalah:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Enable debug mode (temporary):**
   ```env
   APP_DEBUG=true
   ```
   Jangan lupa disable lagi setelah troubleshooting!

3. **Test dengan StorageService:**
   ```php
   use App\Services\StorageService;
   
   $storage = app(StorageService::class);
   $isConfigured = $storage->isCloudStorageConfigured('oss');
   ```

---

## 🎯 Best Practices

1. **Jangan hardcode disk name** - Gunakan `config('filesystems.default')`
2. **Gunakan StorageService** - Centralized storage management dengan fallback
3. **Test setelah deploy** - Selalu test dengan `php artisan storage:test`
4. **Monitor logs** - Check `storage/logs/laravel.log` untuk error
5. **Use environment-specific config** - Development vs Production

---

## 📚 Resources

- [Alibaba Cloud OSS Documentation](https://www.alibabacloud.com/help/en/object-storage-service)
- [Laravel Filesystem Documentation](https://laravel.com/docs/filesystem)
- [AWS S3 SDK for PHP](https://docs.aws.amazon.com/sdk-for-php/)

