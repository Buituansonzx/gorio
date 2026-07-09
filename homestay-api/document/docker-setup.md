# Cấu hình Docker - Homestay API

## Tổng quan Architecture

Homestay API sử dụng Docker multi-container architecture với các service sau:

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     Nginx       │    │    PHP-FPM      │    │     Redis       │
│   (Web Server)  │◄──►│   (App Logic)   │◄──►│     (Cache)     │
│   Port: 8080    │    │   Port: 9000    │    │   Port: 6379    │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │              ┌─────────────────┐              │
         └──────────────►│   AWS RDS       │◄─────────────┘
                        │  (Database)     │
                        │  Port: 3306     │
                        └─────────────────┘
```

## Docker Containers

### 1. Nginx Container (`honestay_nginx`)
- **Image**: `docker-nginx`
- **Port**: `8080:80`
- **Role**: Web server, reverse proxy
- **Config**: Nginx configuration for Laravel

### 2. PHP-FPM Container (`honestay_php_fpm`)
- **Image**: `docker-php-fpm`
- **Port**: `9000` (internal)
- **Role**: PHP application processor
- **Extensions**: 
  - PDO MySQL
  - Redis
  - GD, ZIP, CURL
  - Composer

### 3. Redis Container (`honestay_redis`)
- **Image**: `docker-redis`
- **Port**: `6379:6379`
- **Role**: Cache, Session storage
- **Persistence**: Optional volume mount

### 4. MySQL Container (`honestay_mysql`) - Local Development
- **Image**: `docker-mysql`
- **Port**: `3306:3306`
- **Role**: Local database (development only)
- **Note**: Production sử dụng AWS RDS

## Docker Compose Configuration

### docker-compose.yml
```yaml
version: '3.8'

services:
  nginx:
    container_name: honestay_nginx
    build:
      context: ./docker/nginx
      dockerfile: Dockerfile
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/nginx.conf:/etc/nginx/nginx.conf
      - ./docker/nginx/sites/:/etc/nginx/sites-available
    depends_on:
      - php-fpm
    networks:
      - honestay-network

  php-fpm:
    container_name: honestay_php_fpm
    build:
      context: ./docker/php-fpm
      dockerfile: Dockerfile
    volumes:
      - ./:/var/www/html
    environment:
      - DB_HOST=${DB_HOST}
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
    depends_on:
      - redis
    networks:
      - honestay-network

  redis:
    container_name: honestay_redis
    build:
      context: ./docker/redis
      dockerfile: Dockerfile
    ports:
      - "6379:6379"
    volumes:
      - redis_data:/data
    networks:
      - honestay-network

  mysql:
    container_name: honestay_mysql
    build:
      context: ./docker/mysql
      dockerfile: Dockerfile
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: honestay_local
      MYSQL_USER: honestay_user
      MYSQL_PASSWORD: honestay_pass
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - honestay-network

volumes:
  mysql_data:
  redis_data:

networks:
  honestay-network:
    driver: bridge
```

## Dockerfile Configurations

### PHP-FPM Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Change current user to www
USER www-data

# Expose port 9000
EXPOSE 9000
CMD ["php-fpm"]
```

### Nginx Dockerfile
```dockerfile
FROM nginx:alpine

# Copy custom nginx config
COPY nginx.conf /etc/nginx/nginx.conf
COPY sites/ /etc/nginx/sites-available/

# Create symbolic link
RUN ln -sf /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/

# Expose port 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
```

## Docker Commands

### Khởi động services
```bash
# Build và start tất cả containers
docker-compose up -d --build

# Start containers (không build lại)
docker-compose up -d

# Start specific service
docker-compose up -d nginx php-fpm
```

### Quản lý containers
```bash
# Xem status containers
docker ps

# Xem logs
docker logs honestay_nginx
docker logs honestay_php_fpm -f

# Truy cập container
docker exec -it honestay_php_fpm bash
docker exec -it honestay_nginx sh

# Stop containers
docker-compose down

# Stop và xóa volumes
docker-compose down -v
```

### Maintenance commands
```bash
# Rebuild specific container
docker-compose build php-fpm
docker-compose up -d php-fpm

# Restart container
docker-compose restart php-fpm

# View resource usage
docker stats

# Clean up unused resources
docker system prune -a
```

## Network Configuration

### Internal Communication
- Containers communicate qua `honestay-network`
- PHP-FPM connects to Redis: `redis:6379`
- PHP-FPM connects to MySQL: `mysql:3306` (local) hoặc external AWS RDS

### External Access
- Web: `localhost:8080` → `nginx:80`
- MySQL: `localhost:3306` → `mysql:3306`
- Redis: `localhost:6379` → `redis:6379`

## Volume Mounts

### Application Code
```bash
# Host code được mount vào containers
./:/var/www/html
```

### Configuration Files
```bash
# Nginx configs
./docker/nginx/nginx.conf:/etc/nginx/nginx.conf
./docker/nginx/sites/:/etc/nginx/sites-available
```

### Persistent Data
```bash
# MySQL data
mysql_data:/var/lib/mysql

# Redis data
redis_data:/data
```

## Environment Variables

### Container Environment
```env
# PHP-FPM container
DB_HOST=database-1.c9q2w8g4u0o1.ap-southeast-1.rds.amazonaws.com
DB_DATABASE=honestay
DB_USERNAME=admin
DB_PASSWORD=Quang1998@@
REDIS_HOST=redis
REDIS_PORT=6379
```

## Health Checks

### Container Health
```bash
# Check container health
docker exec honestay_php_fpm php artisan --version

# Test database connection
docker exec honestay_php_fpm php artisan tinker --execute="DB::connection()->getPdo();"

# Test Redis connection
docker exec honestay_redis redis-cli ping
```

### Application Health
```bash
# API health endpoint
curl http://localhost:8080/api/health

# Application status
curl http://localhost:8080/api/status
```

## Troubleshooting

### Common Issues

1. **Port conflicts:**
```bash
# Check port usage
lsof -i :8080
lsof -i :3306

# Change ports in docker-compose.yml
```

2. **Permission issues:**
```bash
# Fix file permissions
docker exec honestay_php_fpm chown -R www-data:www-data /var/www/html/storage
docker exec honestay_php_fpm chmod -R 775 /var/www/html/storage
```

3. **Container fails to start:**
```bash
# Check logs
docker-compose logs php-fpm

# Rebuild container
docker-compose build --no-cache php-fpm
```

4. **Network issues:**
```bash
# Inspect network
docker network inspect honestay-api_honestay-network

# Recreate network
docker-compose down
docker-compose up -d
```
