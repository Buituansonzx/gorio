# Troubleshooting - Homestay API

## Tổng quan

Tài liệu này cung cấp hướng dẫn giải quyết các vấn đề thường gặp khi phát triển và triển khai Homestay API.

## Docker Issues

### 1. Container không khởi động được

#### Triệu chứng:
```bash
docker-compose up -d
# Container exits immediately
```

#### Nguyên nhân & Giải pháp:

**Port conflicts:**
```bash
# Kiểm tra port đang sử dụng
lsof -i :8080
lsof -i :3306
lsof -i :6379

# Giải pháp: Thay đổi port trong docker-compose.yml
ports:
  - "8081:80"  # Thay vì 8080:80
```

**Permission issues:**
```bash
# Fix permissions
sudo chown -R $USER:$USER .
chmod -R 755 .

# Hoặc trong container
docker exec honestay_php_fpm chown -R www-data:www-data /var/www/html
```

**Invalid Docker configuration:**
```bash
# Validate docker-compose.yml
docker-compose config

# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### 2. Container chạy nhưng không truy cập được

#### Triệu chứng:
```bash
curl http://localhost:8080
# Connection refused hoặc timeout
```

#### Giải pháp:

**Kiểm tra container logs:**
```bash
docker logs honestay_nginx
docker logs honestay_php_fpm
```

**Kiểm tra network:**
```bash
docker network ls
docker network inspect honestay-api_honestay-network
```

**Test connectivity:**
```bash
# Test từ host
curl -I http://localhost:8080

# Test từ container khác
docker exec honestay_nginx curl -I http://php-fpm:9000
```

### 3. Database connection issues trong Docker

#### Triệu chứng:
```
SQLSTATE[HY000] [2002] Connection refused
```

#### Giải pháp:

**Kiểm tra database host:**
```env
# Trong .env, sử dụng tên service thay vì localhost
DB_HOST=mysql  # Không phải localhost
```

**Wait for database:**
```bash
# Thêm healthcheck trong docker-compose.yml
mysql:
  healthcheck:
    test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
    timeout: 20s
    retries: 10
```

**Manual connection test:**
```bash
docker exec honestay_php_fpm ping mysql
docker exec honestay_mysql mysql -u root -p -e "SELECT 1"
```

## Laravel/PHP Issues

### 1. Composer memory limit

#### Triệu chứng:
```
Fatal error: Allowed memory size exhausted
```

#### Giải pháp:
```bash
# Tăng memory limit
php -d memory_limit=-1 /usr/local/bin/composer install

# Hoặc trong Dockerfile
RUN echo "memory_limit=-1" >> /usr/local/etc/php/conf.d/memory-limit.ini
```

### 2. Laravel permission errors

#### Triệu chứng:
```
The stream or file "storage/logs/laravel.log" could not be opened
```

#### Giải pháp:
```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# Hoặc trong container
docker exec honestay_php_fpm chmod -R 775 storage/ bootstrap/cache/
```

### 3. Artisan commands not working

#### Triệu chứng:
```bash
php artisan migrate
# Class not found errors
```

#### Giải pháp:
```bash
# Regenerate autoloader
composer dump-autoload

# Clear all caches
php artisan clear-compiled
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Optimize
php artisan optimize
```

### 4. Environment variables not loading

#### Triệu chứng:
```php
env('DB_HOST') returns null
```

#### Giải pháp:
```bash
# Check .env file exists
ls -la .env

# Check .env is properly formatted
cat .env | grep DB_HOST

# Clear config cache
php artisan config:clear

# In production, cache config
php artisan config:cache
```

## Database Issues

### 1. AWS RDS connection problems

#### Triệu chứng:
```
SQLSTATE[HY000] [2002] Connection timed out
```

#### Nguyên nhân & Giải pháp:

**Security Groups:**
```bash
# Kiểm tra AWS RDS Security Groups
# Đảm bảo inbound rule cho port 3306
# Source: 0.0.0.0/0 hoặc specific IP/CIDR
```

**Network ACLs:**
```bash
# Kiểm tra VPC Network ACLs
# Đảm bảo allow traffic trên port 3306
```

**DNS Resolution:**
```bash
# Test DNS resolution
nslookup database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com
dig database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com

# Test connection
telnet database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com 3306
```

### 2. Migration failures

#### Triệu chứng:
```
SQLSTATE[42S01]: Base table or view already exists
```

#### Giải pháp:
```bash
# Check migration status
php artisan migrate:status

# Rollback problematic migration
php artisan migrate:rollback --step=1

# Reset migrations (careful!)
php artisan migrate:reset
php artisan migrate

# Fresh migration with seed
php artisan migrate:fresh --seed
```

### 3. Slow database queries

#### Triệu chứng:
- API response time > 5 seconds
- High database CPU usage

#### Giải pháp:

**Enable query log:**
```php
// In AppServiceProvider
DB::listen(function ($query) {
    if ($query->time > 1000) { // > 1 second
        Log::warning('Slow Query', [
            'sql' => $query->sql,
            'bindings' => $query->bindings,
            'time' => $query->time . 'ms'
        ]);
    }
});
```

**Optimize queries:**
```sql
-- Check for missing indexes
EXPLAIN SELECT * FROM users WHERE email = 'test@example.com';

-- Add indexes
ALTER TABLE users ADD INDEX idx_email (email);

-- Check slow queries
SHOW FULL PROCESSLIST;
```

## Authentication Issues

### 1. Laravel Passport problems

#### Triệu chứng:
```
Encryption key not found
```

#### Giải pháp:
```bash
# Generate Passport keys
php artisan passport:keys

# Clear cache
php artisan config:clear

# Recreate client
php artisan passport:client --personal
```

### 2. JWT token issues

#### Triệu chứng:
```
Token Signature could not be verified
```

#### Giải pháp:
```bash
# Regenerate app key
php artisan key:generate

# Clear config cache
php artisan config:clear

# Check .env APP_KEY
grep APP_KEY .env
```

## Performance Issues

### 1. High memory usage

#### Triệu chứng:
- Container using > 1GB RAM
- Out of memory errors

#### Giải pháp:

**PHP-FPM optimization:**
```ini
; php-fpm.conf
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 2
pm.max_spare_servers = 10
pm.max_requests = 500
```

**Application optimization:**
```php
// Use pagination
$users = User::paginate(20);

// Eager loading
$posts = Post::with('user', 'comments')->get();

// Clear memory in loops
unset($largeVariable);
```

**Monitor memory:**
```bash
# Check container memory
docker stats honestay_php_fpm

# Check PHP memory
docker exec honestay_php_fpm php -i | grep memory_limit
```

### 2. Slow API responses

#### Triệu chứng:
- API response time > 3 seconds
- High CPU usage

#### Giải pháp:

**Enable caching:**
```php
// Cache database queries
$users = Cache::remember('users', 3600, function () {
    return User::all();
});

// Cache API responses
Route::middleware('cache.headers:public;max_age=3600')->group(function () {
    Route::get('/api/users', [UserController::class, 'index']);
});
```

**Database optimization:**
```php
// Use select() to limit columns
$users = User::select('id', 'name', 'email')->get();

// Use chunks for large datasets
User::chunk(100, function ($users) {
    foreach ($users as $user) {
        // Process user
    }
});
```

**Profile queries:**
```bash
# Enable query logging
DB_LOG_QUERIES=true

# Use Debugbar (development only)
DEBUGBAR_ENABLED=true
```

## SSL/HTTPS Issues

### 1. SSL certificate problems

#### Triệu chứng:
```
SSL certificate verify failed
```

#### Giải pháp:
```bash
# Check certificate validity
openssl x509 -in /etc/letsencrypt/live/api.honestay.com/cert.pem -text -noout

# Renew certificate
sudo certbot renew

# Test SSL configuration
curl -I https://api.honestay.com
```

### 2. Mixed content warnings

#### Triệu chứng:
- HTTPS page loading HTTP resources
- Browser security warnings

#### Giải pháp:
```php
// Force HTTPS in production
if (app()->environment('production')) {
    URL::forceScheme('https');
}

// Use secure cookies
'secure' => env('SESSION_SECURE_COOKIE', false),
'http_only' => true,
'same_site' => 'strict',
```

## Monitoring & Debugging

### 1. Application logging

```php
// Custom log channel
'channels' => [
    'api' => [
        'driver' => 'daily',
        'path' => storage_path('logs/api.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],

// Log API requests
Log::channel('api')->info('API Request', [
    'url' => request()->fullUrl(),
    'method' => request()->method(),
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```

### 2. Performance monitoring

```bash
# Install monitoring tools
composer require barryvdh/laravel-debugbar --dev
composer require spatie/laravel-ray --dev

# Monitor queries
DB::enableQueryLog();
$queries = DB::getQueryLog();
```

### 3. Health checks

```php
// Health check endpoint
Route::get('/health', function () {
    $checks = [
        'database' => false,
        'redis' => false,
        'storage' => false,
    ];

    try {
        DB::connection()->getPdo();
        $checks['database'] = true;
    } catch (Exception $e) {
        Log::error('Database health check failed', ['error' => $e->getMessage()]);
    }

    try {
        Redis::ping();
        $checks['redis'] = true;
    } catch (Exception $e) {
        Log::error('Redis health check failed', ['error' => $e->getMessage()]);
    }

    try {
        Storage::disk('local')->put('health-check.txt', 'ok');
        Storage::disk('local')->delete('health-check.txt');
        $checks['storage'] = true;
    } catch (Exception $e) {
        Log::error('Storage health check failed', ['error' => $e->getMessage()]);
    }

    $allHealthy = !in_array(false, $checks);

    return response()->json([
        'status' => $allHealthy ? 'ok' : 'error',
        'checks' => $checks,
        'timestamp' => now(),
    ], $allHealthy ? 200 : 503);
});
```

## Emergency Procedures

### 1. Application down

```bash
# Quick restart
docker-compose restart

# Check logs
docker logs honestay_nginx --tail=50
docker logs honestay_php_fpm --tail=50

# Rollback deployment
git checkout previous-working-commit
./deploy.sh
```

### 2. Database issues

```bash
# Switch to read-only mode
# Update .env to use read replica
DB_HOST=read-replica.endpoint.com

# Create maintenance page
php artisan down --message="Maintenance in progress"

# Restore from backup
mysql -h endpoint -u user -p database < backup.sql

# Bring application back up
php artisan up
```

### 3. High traffic load

```bash
# Scale containers
docker-compose up -d --scale php-fpm=3

# Enable additional caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Monitor resources
docker stats
htop
```

### 4. Security incidents

```bash
# Block suspicious IP
sudo ufw deny from suspicious.ip.address

# Check access logs
tail -f /var/log/nginx/access.log | grep suspicious.ip.address

# Rotate secrets
php artisan key:generate
php artisan passport:keys --force

# Force user logout
php artisan cache:clear
```

## Debug Commands Cheat Sheet

```bash
# Docker
docker ps                                    # List containers
docker logs container_name                   # View logs
docker exec -it container_name bash          # Access container
docker stats                                # Resource usage

# Laravel
php artisan tinker                          # Interactive shell
php artisan route:list                      # List routes
php artisan migrate:status                  # Migration status
php artisan queue:work                      # Process jobs

# Database
mysql -h host -u user -p                    # Connect to MySQL
SHOW PROCESSLIST;                           # Active connections
EXPLAIN SELECT ...;                         # Query execution plan

# System
htop                                        # Process monitor
df -h                                       # Disk usage
free -h                                     # Memory usage
netstat -tulpn                             # Network connections
```

Tài liệu này cung cấp hướng dẫn toàn diện để troubleshoot các vấn đề thường gặp với Homestay API.
