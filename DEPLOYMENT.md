# Deployment Guide - Alibaba Cloud

Panduan lengkap untuk deployment Ebrystoree ke Alibaba Cloud.

## 📋 Prerequisites

- Akun Alibaba Cloud (Free Tier available)
- Domain name (optional)
- Git repository access

## 🚀 Quick Start

### 1. Setup Infrastructure

#### ECS Instance
- Instance Type: `t5-lc1m1.small` (Free Tier) atau lebih besar
- OS: Ubuntu 22.04 LTS
- Storage: 40GB+ SSD
- Security Group: Allow 22, 80, 443

#### RDS MySQL (Optional - bisa pakai MySQL di ECS)
- Version: MySQL 8.0
- Instance Type: Basic (untuk free tier)
- Storage: 100GB+

#### OSS Bucket
- Region: ap-southeast-1 (Singapore) atau terdekat
- Storage Class: Standard
- ACL: Private

### 2. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2-fpm php8.2-cli php8.2-common php8.2-mysql \
    php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml \
    php8.2-bcmath php8.2-intl

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install Nginx
sudo apt install -y nginx

# Install MySQL (jika tidak pakai RDS)
sudo apt install -y mysql-server
```

### 3. Deploy Application

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/ebryany/sggyy_store.git ebrystoree
cd ebrystoree

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Setup environment
cp .env.example .env
nano .env  # Edit dengan konfigurasi production

# Generate key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Set permissions
sudo chown -R www-data:www-data /var/www/ebrystoree
sudo chmod -R 755 /var/www/ebrystoree
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Configure Nginx

Create `/etc/nginx/sites-available/ebrystoree`:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/ebrystoree/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/ebrystoree /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. Setup SSL

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

### 6. Setup Queue Worker

Create `/etc/systemd/system/ebrystoree-queue.service`:

```ini
[Unit]
Description=Ebrystoree Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/ebrystoree/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

Enable:
```bash
sudo systemctl enable ebrystoree-queue
sudo systemctl start ebrystoree-queue
```

### 7. Setup Cron Jobs

```bash
sudo crontab -e -u www-data
```

Add:
```
* * * * * cd /var/www/ebrystoree && php artisan schedule:run >> /dev/null 2>&1
```

## 🔧 Environment Configuration

### Production .env

```env
APP_NAME="Ebrystoree"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-rds-endpoint.rds.aliyuncs.com
DB_PORT=3306
DB_DATABASE=ebrystoree_production
DB_USERNAME=your_db_user
DB_PASSWORD=your_strong_password

FILESYSTEM_DISK=oss
OSS_ACCESS_KEY_ID=your_access_key
OSS_ACCESS_KEY_SECRET=your_secret_key
OSS_BUCKET=your-bucket-name
OSS_ENDPOINT=oss-ap-southeast-1.aliyuncs.com
OSS_REGION=ap-southeast-1
OSS_URL=https://your-bucket.oss-ap-southeast-1.aliyuncs.com

QUEUE_CONNECTION=database
CACHE_STORE=file
SESSION_DRIVER=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.aliyun.com
MAIL_PORT=465
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

## 📦 Update Deployment

Gunakan script `deploy.sh`:

```bash
cd /var/www/ebrystoree
bash deploy.sh
```

Atau manual:
```bash
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.2-fpm
sudo systemctl restart ebrystoree-queue
```

## 🔍 Monitoring

### Check Services
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status ebrystoree-queue
sudo systemctl status mysql
```

### Check Logs
```bash
tail -f /var/www/ebrystoree/storage/logs/laravel.log
tail -f /var/log/nginx/error.log
```

## 🆘 Troubleshooting

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/ebrystoree
sudo chmod -R 755 /var/www/ebrystoree
sudo chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Queue Not Working
```bash
sudo systemctl restart ebrystoree-queue
php artisan queue:restart
```

## 📚 Additional Resources

- [Alibaba Cloud Documentation](https://www.alibabacloud.com/help)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Nginx Configuration](https://nginx.org/en/docs/)

