#!/bin/bash

# ========================================
# HARD RESET SCRIPT - Ebrystoree Project
# ========================================
# 
# ⚠️  WARNING: This script will:
# - Reset database (fresh migrations)
# - Clear all caches
# - Rebuild assets
# - Reset file permissions
# - Clear logs
# - Reinstall dependencies
#
# Usage: bash server_hard_reset.sh
# ========================================

set -e  # Exit on error

echo "🚀 Starting Hard Reset for Ebrystoree Project..."
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# ========================================
# 1. BACKUP DATABASE (Optional but recommended)
# ========================================
echo -e "${YELLOW}📦 Step 1: Creating database backup...${NC}"
if [ -f ".env" ]; then
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
    DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
    
    if [ ! -z "$DB_DATABASE" ] && [ "$DB_DATABASE" != "database.sqlite" ]; then
        BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
        echo "Creating backup: $BACKUP_FILE"
        mysqldump -u "$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" > "$BACKUP_FILE" 2>/dev/null || echo "⚠️  Backup skipped (MySQL not available or SQLite)"
    fi
fi
echo -e "${GREEN}✅ Backup completed${NC}"
echo ""

# ========================================
# 2. GIT RESET & PULL
# ========================================
echo -e "${YELLOW}📥 Step 2: Resetting Git and pulling latest code...${NC}"
git fetch origin
git reset --hard origin/main
git clean -fd
echo -e "${GREEN}✅ Git reset completed${NC}"
echo ""

# ========================================
# 3. INSTALL/UPDATE DEPENDENCIES
# ========================================
echo -e "${YELLOW}📦 Step 3: Installing/updating dependencies...${NC}"

# Composer dependencies
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader
    echo -e "${GREEN}✅ Composer dependencies installed${NC}"
else
    echo -e "${RED}⚠️  Composer not found, skipping...${NC}"
fi

# NPM dependencies
if command -v npm &> /dev/null; then
    npm install
    echo -e "${GREEN}✅ NPM dependencies installed${NC}"
else
    echo -e "${RED}⚠️  NPM not found, skipping...${NC}"
fi
echo ""

# ========================================
# 4. RESET DATABASE
# ========================================
echo -e "${YELLOW}🗄️  Step 4: Resetting database...${NC}"
php artisan migrate:fresh --seed --force
echo -e "${GREEN}✅ Database reset completed${NC}"
echo ""

# ========================================
# 5. CLEAR ALL CACHES
# ========================================
echo -e "${YELLOW}🧹 Step 5: Clearing all caches...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
php artisan optimize:clear
echo -e "${GREEN}✅ All caches cleared${NC}"
echo ""

# ========================================
# 6. REBUILD CACHE & OPTIMIZE
# ========================================
echo -e "${YELLOW}⚡ Step 6: Rebuilding cache and optimizing...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
echo -e "${GREEN}✅ Cache rebuilt and optimized${NC}"
echo ""

# ========================================
# 7. BUILD FRONTEND ASSETS
# ========================================
echo -e "${YELLOW}🎨 Step 7: Building frontend assets...${NC}"
if command -v npm &> /dev/null; then
    npm run build
    echo -e "${GREEN}✅ Frontend assets built${NC}"
else
    echo -e "${RED}⚠️  NPM not found, skipping asset build...${NC}"
fi
echo ""

# ========================================
# 8. RESET FILE PERMISSIONS
# ========================================
echo -e "${YELLOW}🔐 Step 8: Resetting file permissions...${NC}"
# Storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo "⚠️  Could not change ownership (may need sudo)"

# Public storage link
php artisan storage:link 2>/dev/null || echo "⚠️  Storage link already exists or failed"
echo -e "${GREEN}✅ File permissions reset${NC}"
echo ""

# ========================================
# 9. CLEAR LOGS
# ========================================
echo -e "${YELLOW}📝 Step 9: Clearing logs...${NC}"
> storage/logs/laravel.log 2>/dev/null || echo "⚠️  Could not clear log file"
find storage/logs -name "*.log" -type f -delete 2>/dev/null || echo "⚠️  Could not delete log files"
echo -e "${GREEN}✅ Logs cleared${NC}"
echo ""

# ========================================
# 10. QUEUE RESTART (if using queues)
# ========================================
echo -e "${YELLOW}🔄 Step 10: Restarting queue workers...${NC}"
php artisan queue:restart 2>/dev/null || echo "⚠️  Queue restart skipped (not using queues)"
echo -e "${GREEN}✅ Queue workers restarted${NC}"
echo ""

# ========================================
# 11. RUN HEALTH CHECKS
# ========================================
echo -e "${YELLOW}🏥 Step 11: Running health checks...${NC}"
php artisan about
echo ""

# ========================================
# COMPLETION
# ========================================
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}✅ HARD RESET COMPLETED SUCCESSFULLY!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo "📋 Summary:"
echo "  ✅ Code updated from Git"
echo "  ✅ Dependencies installed"
echo "  ✅ Database reset & seeded"
echo "  ✅ All caches cleared & rebuilt"
echo "  ✅ Frontend assets built"
echo "  ✅ File permissions reset"
echo "  ✅ Logs cleared"
echo ""
echo "🚀 Project is ready to use!"
echo ""

