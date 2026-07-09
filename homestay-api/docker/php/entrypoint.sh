#!/bin/sh
set -e

# optimize cache - chạy với user www-data
echo "Clearing and caching configurations..."
su -s /bin/sh www-data -c "php artisan optimize:clear"
su -s /bin/sh www-data -c "php artisan config:cache"
su -s /bin/sh www-data -c "php artisan route:cache"

# Cuối cùng, chạy command được truyền vào (supervisord)
exec "$@"
