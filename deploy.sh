#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Pull latest code
echo "📥 Pulling latest code..."
git pull origin main

# Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction
npm install
npm run build

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Clear and cache
echo "🧹 Clearing and caching..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Optimize
echo "⚡ Optimizing..."
composer dump-autoload --optimize

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data /var/www/ebrystoree
chmod -R 755 /var/www/ebrystoree
chmod -R 775 storage bootstrap/cache

# Clear all caches (including service caches)
echo "🧹 Clearing all application caches..."
php artisan cache:clear-all

# Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
sudo systemctl restart ebrystoree-queue

echo "✅ Deployment completed successfully!"

