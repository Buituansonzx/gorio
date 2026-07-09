# Cấu hình Database - Homestay API

## Tổng quan Database

Homestay API sử dụng **AWS RDS MySQL 8.0.42** làm database chính cho production và có thể sử dụng MySQL local cho development.

## Thông tin Database hiện tại

### Production Database (AWS RDS)
- **Host**: `database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com`
- **Port**: `3306`
- **Database**: `honestay`
- **Username**: `admin`
- **Password**: `Quang1998@@`
- **Engine**: MySQL 8.0.42
- **Region**: ap-southeast-1 (Singapore)

### Development Database (Docker)
- **Host**: `localhost` hoặc `mysql` (trong Docker network)
- **Port**: `3306`
- **Database**: `honestay_local`
- **Username**: `honestay_user`
- **Password**: `honestay_pass`

## Cấu hình .env

### Production
```env
DB_CONNECTION=mysql
DB_HOST=database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=honestay
DB_USERNAME=admin
DB_PASSWORD=Quang1998@@
```

### Development
```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=honestay_local
DB_USERNAME=honestay_user
DB_PASSWORD=honestay_pass
```

## Database Schema

### Bảng chính (Core Tables)

#### 1. users
```sql
CREATE TABLE users (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp NULL,
    password varchar(255) NOT NULL,
    otp varchar(6) NULL,
    remember_token varchar(100) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    PRIMARY KEY (id)
);
```

#### 2. oauth_clients (Laravel Passport)
```sql
CREATE TABLE oauth_clients (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    user_id bigint(20) unsigned NULL,
    name varchar(255) NOT NULL,
    secret varchar(100) NULL,
    provider varchar(255) NULL,
    redirect text NOT NULL,
    personal_access_client tinyint(1) NOT NULL,
    password_client tinyint(1) NOT NULL,
    revoked tinyint(1) NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    PRIMARY KEY (id)
);
```

#### 3. permissions & roles (Spatie Permission)
```sql
CREATE TABLE permissions (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    guard_name varchar(255) NOT NULL,
    display_name varchar(255) NULL,
    description text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    PRIMARY KEY (id)
);

CREATE TABLE roles (
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    guard_name varchar(255) NOT NULL,
    display_name varchar(255) NULL,
    description text NULL,
    accessible_domains text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    PRIMARY KEY (id)
);
```

### Bảng hệ thống (System Tables)

#### 1. migrations
Lưu trữ trạng thái migration
```sql
SELECT * FROM migrations ORDER BY batch DESC;
```

#### 2. cache & cache_locks
Lưu trữ cache data
```sql
SHOW CREATE TABLE cache;
SHOW CREATE TABLE cache_locks;
```

#### 3. jobs & failed_jobs
Queue system
```sql
SHOW CREATE TABLE jobs;
SHOW CREATE TABLE failed_jobs;
```

## Migrations

### Danh sách Migrations hiện tại
```
0001_01_01_000000_create_users_table
0001_01_01_000001_create_cache_table
0001_01_01_000002_create_jobs_table
0001_01_01_000003_create_notifications_table
0001_01_01_000004_create_test_users_table
0003_01_01_000001_create_permission_tables
0003_01_01_000002_add_info_fields_to_permissions_tale
0003_01_01_000003_add_info_fields_to_roles_table
2025_07_05_000001_add_accessible_domains_to_roles_table
2025_07_05_100000_add_otp_to_users_table
2025_07_05_170002_create_oauth_access_tokens_table
2025_07_05_170002_create_oauth_auth_codes_table
2025_07_05_170002_create_oauth_clients_table
2025_07_05_170002_create_oauth_device_codes_table
2025_07_05_170002_create_oauth_refresh_tokens_table
```

### Chạy Migrations
```bash
# Trong container PHP-FPM
docker exec -it honestay_php_fpm bash

# Kiểm tra migration status
php artisan migrate:status

# Chạy migrations
php artisan migrate

# Rollback (nếu cần)
php artisan migrate:rollback

# Refresh (reset & migrate)
php artisan migrate:refresh
```

## Database Operations

### Backup Database
```bash
# Backup AWS RDS
mysqldump -h database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com \
          -u admin -p honestay > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup local Docker database
docker exec honestay_mysql mysqldump -u root -proot honestay_local > backup_local.sql
```

### Restore Database
```bash
# Restore to AWS RDS
mysql -h database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com \
      -u admin -p honestay < backup_file.sql

# Restore to local Docker
docker exec -i honestay_mysql mysql -u root -proot honestay_local < backup_file.sql
```

### Database Maintenance
```bash
# Trong container PHP-FPM
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize application
php artisan optimize
php artisan config:cache
php artisan route:cache
```

## Monitoring và Performance

### Connection Testing
```bash
# Test từ container
docker exec -it honestay_php_fpm php artisan tinker --execute="
\$connection = DB::connection();
\$version = DB::select('SELECT VERSION() as version');
\$database = DB::select('SELECT DATABASE() as database_name');
echo 'Version: ' . \$version[0]->version . PHP_EOL;
echo 'Database: ' . \$database[0]->database_name . PHP_EOL;
"
```

### Performance Queries
```sql
-- Kiểm tra số lượng bảng
SELECT COUNT(*) as table_count FROM information_schema.tables 
WHERE table_schema = 'honestay';

-- Kiểm tra kích thước database
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'honestay'
GROUP BY table_schema;

-- Top 5 bảng lớn nhất
SELECT 
    table_name AS 'Table',
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'honestay'
ORDER BY (data_length + index_length) DESC
LIMIT 5;
```

### Index Optimization
```sql
-- Kiểm tra missing indexes
SELECT DISTINCT
    CONCAT(
        'ALTER TABLE `', table_schema, '`.`', table_name, '` ',
        'ADD INDEX `idx_', column_name, '` (`', column_name, '`);'
    ) AS 'Suggested Index'
FROM information_schema.columns
WHERE table_schema = 'honestay'
  AND column_name IN ('created_at', 'updated_at', 'user_id', 'status');
```

## Troubleshooting

### Connection Issues

1. **AWS RDS Connection timeout:**
```bash
# Kiểm tra security groups
# Đảm bảo inbound rule cho port 3306 từ IP hiện tại

# Test connection từ local
telnet database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com 3306
```

2. **Authentication failed:**
```bash
# Verify credentials
mysql -h database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com \
      -u admin -p

# Check user permissions
SHOW GRANTS FOR 'admin'@'%';
```

### Migration Issues

1. **Migration stuck:**
```bash
# Clear migration lock
DELETE FROM migrations WHERE migration = 'stuck_migration_name';

# Force reset migration status
php artisan migrate:reset
php artisan migrate
```

2. **Foreign key constraints:**
```bash
# Disable foreign key checks temporarily
SET FOREIGN_KEY_CHECKS = 0;
-- Run your migration
SET FOREIGN_KEY_CHECKS = 1;
```

### Performance Issues

1. **Slow queries:**
```sql
-- Enable slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check slow queries
SHOW VARIABLES LIKE 'slow_query_log%';
```

2. **Connection limits:**
```sql
-- Check connection limits
SHOW VARIABLES LIKE 'max_connections';
SHOW STATUS LIKE 'Connections';
SHOW PROCESSLIST;
```

## Security Best Practices

### AWS RDS Security
- Sử dụng VPC security groups
- Enable encryption at rest
- Regular security updates
- Strong password policy
- Monitor access logs

### Application Security
```php
// Use prepared statements (Laravel ORM tự động)
$users = DB::select('SELECT * FROM users WHERE id = ?', [$id]);

// Sanitize input
$validated = $request->validate([
    'email' => 'required|email|max:255',
    'password' => 'required|min:8'
]);
```

### Database Access Control
```sql
-- Tạo user với quyền hạn chế
CREATE USER 'app_user'@'%' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON honestay.* TO 'app_user'@'%';
FLUSH PRIVILEGES;
```
