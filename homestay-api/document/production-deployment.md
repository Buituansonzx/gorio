# Triển khai Production - Homestay API

## Tổng quan

Hướng dẫn triển khai Homestay API lên môi trường production với các best practices về bảo mật, hiệu suất và monitoring.

## Chuẩn bị Production Environment

### Server Requirements
- **OS**: Ubuntu 20.04 LTS hoặc CentOS 8+
- **RAM**: 8GB+ (khuyến nghị 16GB)
- **CPU**: 4 cores+
- **Storage**: 50GB+ SSD
- **Network**: Stable internet connection

### Software Stack
- **Web Server**: Nginx
- **Application**: PHP 8.1+ với PHP-FPM
- **Database**: AWS RDS MySQL 8.0+
- **Cache**: Redis
- **Process Manager**: Supervisor
- **SSL**: Let's Encrypt hoặc commercial certificate

## Bước 1: Server Setup

### 1.1 Update System
```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y
sudo apt install curl wget git unzip software-properties-common -y

# CentOS/RHEL
sudo yum update -y
sudo yum install curl wget git unzip -y
```

### 1.2 Install Docker & Docker Compose
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.17.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Add user to docker group
sudo usermod -aG docker $USER
newgrp docker
```

### 1.3 Configure Firewall
```bash
# Ubuntu UFW
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw status

# CentOS FirewallD
sudo systemctl enable firewalld
sudo firewall-cmd --permanent --add-service=ssh
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

## Bước 2: Domain và SSL Setup

### 2.1 DNS Configuration
```bash
# Point your domain to server IP
# A record: api.honestay.com -> YOUR_SERVER_IP
# CNAME record: www.api.honestay.com -> api.honestay.com
```

### 2.2 SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install snapd -y
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Get SSL certificate
sudo certbot certonly --standalone -d api.honestay.com -d www.api.honestay.com
```

## Bước 3: Application Deployment

### 3.1 Clone Repository
```bash
# Create application directory
sudo mkdir -p /var/www/honestay-api
sudo chown $USER:$USER /var/www/honestay-api

# Clone code
cd /var/www/honestay-api
git clone <repository-url> .
```

### 3.2 Production Environment Configuration
```bash
# Copy and configure environment
cp .env.example .env
nano .env
```

### Production .env Configuration
```env
# Application
APP_NAME="Honestay API"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.honestay.com
API_URL=api.honestay.com

# Security
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Database (AWS RDS)
DB_CONNECTION=mysql
DB_HOST=database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com
DB_PORT=3306
DB_DATABASE=honestay
DB_USERNAME=admin
DB_PASSWORD=Quang1998@@

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=ses
MAIL_FROM_ADDRESS=noreply@honestay.com
MAIL_FROM_NAME="Honestay API"

# AWS
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=honestay-storage

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error
```

### 3.3 Production Docker Compose
```yaml
# docker-compose.prod.yml
version: '3.8'

services:
  nginx:
    container_name: honestay_nginx_prod
    build:
      context: ./docker/nginx
      dockerfile: Dockerfile.prod
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/nginx.prod.conf:/etc/nginx/nginx.conf
      - ./docker/nginx/sites/prod.conf:/etc/nginx/sites-available/default
      - /etc/letsencrypt:/etc/letsencrypt:ro
    depends_on:
      - php-fpm
    restart: unless-stopped
    networks:
      - honestay-network

  php-fpm:
    container_name: honestay_php_fpm_prod
    build:
      context: ./docker/php-fpm
      dockerfile: Dockerfile.prod
    volumes:
      - ./:/var/www/html
    environment:
      - APP_ENV=production
      - DB_HOST=${DB_HOST}
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
    depends_on:
      - redis
    restart: unless-stopped
    networks:
      - honestay-network

  redis:
    container_name: honestay_redis_prod
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    restart: unless-stopped
    networks:
      - honestay-network

  supervisor:
    container_name: honestay_supervisor_prod
    build:
      context: ./docker/supervisor
      dockerfile: Dockerfile
    volumes:
      - ./:/var/www/html
    depends_on:
      - php-fpm
      - redis
    restart: unless-stopped
    networks:
      - honestay-network

volumes:
  redis_data:

networks:
  honestay-network:
    driver: bridge
```

## Bước 4: Production Optimizations

### 4.1 Composer Optimization
```bash
# Install production dependencies
docker exec honestay_php_fpm_prod composer install --no-dev --optimize-autoloader --no-interaction

# Generate optimized autoloader
docker exec honestay_php_fpm_prod composer dump-autoload --optimize --classmap-authoritative
```

### 4.2 Laravel Optimizations
```bash
# Generate application key
docker exec honestay_php_fpm_prod php artisan key:generate --force

# Cache configurations
docker exec honestay_php_fpm_prod php artisan config:cache
docker exec honestay_php_fpm_prod php artisan route:cache
docker exec honestay_php_fpm_prod php artisan view:cache

# Run migrations
docker exec honestay_php_fpm_prod php artisan migrate --force

# Setup Laravel Passport
docker exec honestay_php_fpm_prod php artisan passport:keys
docker exec honestay_php_fpm_prod php artisan passport:client --personal --no-interaction
```

### 4.3 File Permissions
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/honestay-api/storage
sudo chown -R www-data:www-data /var/www/honestay-api/bootstrap/cache
sudo chmod -R 775 /var/www/honestay-api/storage
sudo chmod -R 775 /var/www/honestay-api/bootstrap/cache
```

## Bước 5: Nginx Production Configuration

### Production Nginx Config
```nginx
# docker/nginx/sites/prod.conf
server {
    listen 80;
    server_name api.honestay.com www.api.honestay.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.honestay.com www.api.honestay.com;
    root /var/www/html/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/api.honestay.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/api.honestay.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline';" always;

    # Rate Limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    limit_req zone=api burst=20 nodelay;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php-fpm:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    location ~ /\. {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

## Bước 6: Monitoring và Logging

### 6.1 Application Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Setup log rotation
sudo nano /etc/logrotate.d/honestay-api
```

### Log Rotation Configuration
```bash
/var/www/honestay-api/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
    postrotate
        docker exec honestay_php_fpm_prod php artisan cache:clear
    endscript
}
```

### 6.2 Health Check Endpoint
```php
// routes/api.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'version' => config('app.version', '1.0.0'),
        'environment' => app()->environment(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::store('redis')->get('health_check') !== null ? 'connected' : 'disconnected'
    ]);
});
```

### 6.3 Uptime Monitoring
```bash
# Setup cron job for health checks
crontab -e

# Add this line to check every 5 minutes
*/5 * * * * curl -f https://api.honestay.com/api/health || echo "API Health Check Failed: $(date)" >> /var/log/honestay-health.log
```

## Bước 7: Backup Strategy

### 7.1 Database Backup
```bash
#!/bin/bash
# backup-database.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/honestay"
FILENAME="honestay_backup_$DATE.sql"

mkdir -p $BACKUP_DIR

# Backup AWS RDS
mysqldump -h database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com \
          -u admin -p'Quang1998@@' \
          --single-transaction \
          --routines \
          --triggers \
          honestay > $BACKUP_DIR/$FILENAME

# Compress backup
gzip $BACKUP_DIR/$FILENAME

# Remove backups older than 7 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "Database backup completed: $FILENAME.gz"
```

### 7.2 Application Backup
```bash
#!/bin/bash
# backup-application.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/honestay"
APP_DIR="/var/www/honestay-api"

mkdir -p $BACKUP_DIR

# Backup application files (excluding vendor and node_modules)
tar -czf $BACKUP_DIR/app_backup_$DATE.tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    --exclude='storage/framework/sessions' \
    --exclude='storage/framework/views' \
    -C $APP_DIR .

echo "Application backup completed: app_backup_$DATE.tar.gz"
```

### 7.3 Automated Backup Cron
```bash
# Setup cron jobs
crontab -e

# Daily database backup at 2 AM
0 2 * * * /var/scripts/backup-database.sh

# Weekly application backup at 3 AM on Sunday
0 3 * * 0 /var/scripts/backup-application.sh
```

## Bước 8: Security Hardening

### 8.1 Server Security
```bash
# Disable root login
sudo nano /etc/ssh/sshd_config
# Set: PermitRootLogin no
# Set: PasswordAuthentication no

# Install fail2ban
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban

# Configure fail2ban for Nginx
sudo nano /etc/fail2ban/jail.local
```

### 8.2 Application Security
```env
# Add to .env
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

# Add CSRF protection
SANCTUM_STATEFUL_DOMAINS=api.honestay.com
```

### 8.3 Database Security
```sql
-- Create limited database user for application
CREATE USER 'honestay_app'@'%' IDENTIFIED BY 'strong_random_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON honestay.* TO 'honestay_app'@'%';
FLUSH PRIVILEGES;
```

## Bước 9: Performance Optimization

### 9.1 PHP-FPM Tuning
```ini
; docker/php-fpm/php-fpm.prod.conf
[www]
user = www-data
group = www-data
listen = 9000
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500
```

### 9.2 Redis Configuration
```conf
# redis.conf
maxmemory 1gb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### 9.3 Application Caching
```bash
# Enable OPcache
docker exec honestay_php_fpm_prod php -i | grep opcache

# Laravel optimizations
docker exec honestay_php_fpm_prod php artisan optimize
```

## Bước 10: Deployment Automation

### 10.1 Deployment Script
```bash
#!/bin/bash
# deploy.sh

set -e

echo "Starting deployment..."

# Pull latest code
git pull origin main

# Update dependencies
docker exec honestay_php_fpm_prod composer install --no-dev --optimize-autoloader

# Run migrations
docker exec honestay_php_fpm_prod php artisan migrate --force

# Clear and cache optimizations
docker exec honestay_php_fpm_prod php artisan optimize:clear
docker exec honestay_php_fpm_prod php artisan optimize

# Restart services
docker-compose -f docker-compose.prod.yml restart php-fpm nginx

echo "Deployment completed successfully!"
```

### 10.2 Zero-Downtime Deployment
```bash
#!/bin/bash
# zero-downtime-deploy.sh

BLUE_CONTAINER="honestay_php_fpm_blue"
GREEN_CONTAINER="honestay_php_fpm_green"
CURRENT_CONTAINER=$(docker ps --format "table {{.Names}}" | grep honestay_php_fpm | head -1)

if [ "$CURRENT_CONTAINER" = "$BLUE_CONTAINER" ]; then
    NEW_CONTAINER=$GREEN_CONTAINER
else
    NEW_CONTAINER=$BLUE_CONTAINER
fi

echo "Deploying to $NEW_CONTAINER..."

# Deploy to new container
# Switch traffic
# Stop old container

echo "Zero-downtime deployment completed!"
```

## Troubleshooting Production Issues

### Common Issues và Solutions

1. **High CPU Usage:**
```bash
# Check processes
htop
docker stats

# Optimize PHP-FPM
# Tune database queries
# Enable caching
```

2. **Memory Leaks:**
```bash
# Monitor memory usage
free -h
docker exec honestay_php_fpm_prod php artisan horizon:status

# Check for memory leaks in code
# Restart PHP-FPM periodically
```

3. **Database Connection Issues:**
```bash
# Check connection pool
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Connections';

# Optimize connection pooling
# Monitor slow queries
```

4. **SSL Certificate Renewal:**
```bash
# Automatic renewal
sudo certbot renew --dry-run
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

Tài liệu này cung cấp hướng dẫn đầy đủ để triển khai Homestay API lên production một cách an toàn và hiệu quả.
