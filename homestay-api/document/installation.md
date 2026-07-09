# Hướng dẫn Cài đặt Homestay API

## Yêu cầu hệ thống

### Phần mềm cần thiết
- Docker & Docker Compose
- Git
- Composer (nếu chạy local)
- PHP 8.1+ (nếu chạy local)

### Phần cứng khuyến nghị
- RAM: 4GB+
- Storage: 10GB+
- CPU: 2 cores+

## Bước 1: Clone Repository

```bash
git clone <repository-url>
cd homestay-api
```

## Bước 2: Cấu hình Environment

1. Copy file environment mẫu:
```bash
cp .env.example .env
```

2. Cấu hình database trong `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=honestay
DB_USERNAME=admin
DB_PASSWORD=Quang1998@@
```

3. Cấu hình ứng dụng:
```env
APP_NAME=Apiato
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080
```

## Bước 3: Khởi động Docker

1. Build và start containers:
```bash
docker-compose up -d --build
```

2. Kiểm tra containers đang chạy:
```bash
docker ps
```

Bạn sẽ thấy 4 containers:
- `honestay_nginx` (port 8080)
- `honestay_php_fpm` 
- `honestay_mysql` (port 3306)
- `honestay_redis` (port 6379)

## Bước 4: Cài đặt Dependencies

```bash
# Truy cập container PHP-FPM
docker exec -it honestay_php_fpm bash

# Cài đặt dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate

# Clear cache
php artisan config:clear
php artisan cache:clear
```

## Bước 5: Database Migration

```bash
# Trong container PHP-FPM
php artisan migrate

# (Tùy chọn) Seed data mẫu
php artisan db:seed
```

## Bước 6: Cấu hình Laravel Passport

```bash
# Tạo encryption keys cho Passport
php artisan passport:keys

# Tạo client credentials (nếu cần)
php artisan passport:client --personal
```

## Bước 7: Kiểm tra cài đặt

1. **Test API endpoint:**
```bash
curl http://localhost:8080/api/health
```

2. **Test database connection:**
```bash
docker exec -it honestay_php_fpm php artisan tinker --execute="DB::connection()->getPdo(); echo 'Connection successful!';"
```

3. **Truy cập web interface:**
Mở browser và truy cập: `http://localhost:8080`

## Cấu hình bổ sung

### File Permissions (Linux/Mac)
```bash
# Set permissions cho storage và cache (Development)
sudo chown -R $USER:$USER storage/
sudo chown -R $USER:$USER bootstrap/cache/
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Set permissions cho production server
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Nếu gặp lỗi permission denied, sử dụng sudo
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Kiểm tra ownership
ls -la storage/
ls -la bootstrap/cache/
```

### Production Server Permissions với Error Handling
```bash
# Phương pháp 1: Thực hiện cùng lúc (nhanh nhất)
sudo chown -R www-data:www-data storage bootstrap/cache && echo "Chown successful" || echo "Chown failed - trying individual commands"

# Phương pháp 2: Thực hiện từng bước (nếu phương pháp 1 thất bại)
sudo chown -R www-data:www-data storage/
if [ $? -eq 0 ]; then
    echo "✓ Storage ownership changed successfully"
else
    echo "✗ Failed to change storage ownership"
fi

sudo chown -R www-data:www-data bootstrap/cache/
if [ $? -eq 0 ]; then
    echo "✓ Bootstrap/cache ownership changed successfully"
else
    echo "✗ Failed to change bootstrap/cache ownership"
fi

# Set permissions
sudo chmod -R 775 storage/ bootstrap/cache/
if [ $? -eq 0 ]; then
    echo "✓ Permissions set successfully"
else
    echo "✗ Failed to set permissions"
fi

# Verify final permissions
echo "=== Final permissions check ==="
ls -la storage/ | head -5
ls -la bootstrap/cache/
```

### Supervisor (Production)
```bash
# Cấu hình queue worker
php artisan queue:work --daemon
```

## Troubleshooting

### Lỗi thường gặp:

1. **Container không start được:**
```bash
docker-compose down
docker-compose up -d --build
```

2. **Database connection failed:**
- Kiểm tra AWS RDS security groups
- Verify credentials trong `.env`

3. **Permission denied:**
```bash
docker exec -it honestay_php_fpm chown -R www-data:www-data /var/www/html
```

4. **Composer memory limit:**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

5. **array_merge() error trên Production:**
Lỗi này xảy ra khi file config `integration.php` rỗng hoặc không đúng format:

```bash
# Fix trên server production
cd /var/www/html/homestay-api

# Backup trước khi fix
cp -r app/Containers/AppSection/Integration app/Containers/AppSection/Integration_backup_$(date +%Y%m%d_%H%M%S)

# Tạo thư mục nếu chưa có
mkdir -p app/Containers/AppSection/Integration/Configs
mkdir -p app/Containers/AppSection/Integration/Providers

# Tạo file config integration
cat > app/Containers/AppSection/Integration/Configs/integration.php << 'EOF'
<?php

return [
    'payment' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', false),
            'public_key' => env('STRIPE_PUBLIC_KEY'),
            'secret_key' => env('STRIPE_SECRET_KEY'),
        ],
        'vnpay' => [
            'enabled' => env('VNPAY_ENABLED', false),
            'merchant_id' => env('VNPAY_MERCHANT_ID'),
            'secret_key' => env('VNPAY_SECRET_KEY'),
        ],
    ],
    'notification' => [
        'email' => ['enabled' => true],
        'sms' => ['enabled' => false],
    ],
];
EOF

# Tạo ServiceProvider nếu cần
cat > app/Containers/AppSection/Integration/Providers/IntegrationServiceProvider.php << 'EOF'
<?php

namespace App\Containers\AppSection\Integration\Providers;

use App\Ship\Parents\Providers\ServiceProvider as ParentServiceProvider;

final class IntegrationServiceProvider extends ParentServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../Configs/integration.php',
            'integration'
        );
    }
}
EOF

# Set permissions
chown -R www-data:www-data app/Containers/AppSection/Integration/
chmod -R 755 app/Containers/AppSection/Integration/

# Clear cache và test
php artisan config:clear
php artisan cache:clear
php artisan package:discover --ansi

# Verify fix
echo "✅ Checking if fix worked..."
php artisan tinker --execute="echo 'Config loaded: '; var_dump(config('integration.payment'));"
```

6. **Permission denied khi chown:**
```bash
# Nếu không có quyền sudo
ls -la storage/ bootstrap/cache/

# Kiểm tra user hiện tại
whoami
id

# Fix permissions với sudo
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Hoặc sử dụng user hiện tại (development)
sudo chown -R $(whoami):$(whoami) storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

7. **Docker container permission issues:**
```bash
# Trong Docker container
docker exec -it honestay_php_fpm bash

# Fix permissions trong container
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Exit container
exit
```

8. **Config cache issues:**
```bash
# Clear tất cả cache Laravel
php artisan optimize:clear

# Hoặc clear từng loại
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear

# Regenerate optimized files
php artisan optimize
```

## Logs và Debugging

```bash
# Xem logs Laravel
docker exec -it honestay_php_fpm tail -f storage/logs/laravel.log

# Xem logs container
docker logs honestay_nginx
docker logs honestay_php_fpm
docker logs honestay_mysql
```
