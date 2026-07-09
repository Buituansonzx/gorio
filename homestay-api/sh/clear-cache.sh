#!/bin/bash
# Script để clear tất cả cache mà không restart server

echo "🧹 Clearing all Laravel caches..."
docker exec honestay_php_fpm bash -c "cd /var/www/html/homestay-api && php artisan optimize:clear"

echo "🔄 Clearing PHP OpCode cache..."
docker exec honestay_php_fpm php -r "if (function_exists('opcache_reset')) opcache_reset(); echo 'OpCode cache cleared';"

echo "🔄 Clearing APCu cache..."
docker exec honestay_php_fpm php -r "if (function_exists('apcu_clear_cache')) apcu_clear_cache(); echo 'APCu cache cleared';"

echo "🔄 Clearing Realpath cache..."
docker exec honestay_php_fpm php -r "clearstatcache(); echo 'Realpath cache cleared';"

echo "♻️  Gracefully restarting PHP-FPM..."
docker exec honestay_php_fpm bash -c "kill -USR2 1"

echo "� Rebuilding autoloader..."
docker exec honestay_php_fpm bash -c "cd /var/www/html/homestay-api && composer dump-autoload --optimize"

echo "✅ All caches cleared! No server restart needed."
