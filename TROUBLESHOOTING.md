# Troubleshooting Guide

## Data Tidak Real-time di Cloud (Cache Issue)

### Masalah
Data tidak update real-time di cloud, padahal di local real-time.

### Penyebab
1. **Cache TTL terlalu lama** - Settings cache 1 jam, Dashboard cache 5-30 menit
2. **Cache tidak di-clear setelah update** - Data baru tidak muncul karena cache lama
3. **Config/Route/View cache** - Cache masih menggunakan versi lama
4. **Queue worker tidak running** - Update via queue tidak diproses

### Solusi

#### 1. Clear Semua Cache
```bash
php artisan cache:clear-all
```

Command ini akan clear:
- Application cache
- Config cache
- Route cache
- View cache
- Event cache
- Admin dashboard cache
- Settings cache

#### 2. Clear Cache Manual
```bash
# Clear semua Laravel cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear service cache
php artisan tinker
>>> app(\App\Services\AdminDashboardService::class)->clearCache();
>>> app(\App\Services\SettingsService::class)->clearCache();
```

#### 3. Pastikan Queue Worker Running
```bash
# Check status
sudo systemctl status ebrystoree-queue

# Restart jika perlu
sudo systemctl restart ebrystoree-queue

# Atau manual
php artisan queue:work
```

#### 4. Setelah Deploy
Script `deploy.sh` sudah diupdate untuk auto-clear cache. Tapi jika manual:
```bash
php artisan cache:clear-all
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Cache TTL Configuration

Cache TTL sudah dioptimasi:
- **Production**: 
  - Settings: 1 jam (3600 detik)
  - Dashboard Stats: 10 menit (600 detik)
  - Dashboard Alerts: 5 menit (300 detik)
- **Development**: 
  - Settings: 1 menit (60 detik)
  - Dashboard Stats: 30 detik
  - Dashboard Alerts: 10 detik

### Tips

1. **Setelah Update Data Penting**: Clear cache manual
   ```bash
   php artisan cache:clear-all
   ```

2. **Development**: Set `APP_ENV=local` untuk cache TTL pendek

3. **Production**: Set `APP_ENV=production` untuk cache TTL panjang (performance)

4. **Monitor Queue**: Pastikan queue worker selalu running

5. **Browser Cache**: Hard refresh (Ctrl+F5) untuk clear browser cache

## Masalah Lainnya

### Database Connection Error
```bash
# Check MySQL running
sudo systemctl status mysql

# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/ebrystoree
sudo chmod -R 755 /var/www/ebrystoree
sudo chmod -R 775 storage bootstrap/cache
```

### File Upload Tidak Bekerja
- Check `storage/app/public` permissions
- Check OSS configuration (jika pakai cloud storage)
- Check `php.ini` upload settings

### Queue Jobs Tidak Diproses
```bash
# Check queue table
php artisan tinker
>>> \App\Models\Job::count();

# Process manually
php artisan queue:work --once
```

