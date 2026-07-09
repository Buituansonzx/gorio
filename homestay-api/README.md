# HoneStay API

<h2 align="center">Homestay Booking Platform API</h2>
<h3 align="center">Modern homestay booking API built with Laravel Apiato | Powered by Docker</h3>

---

## 🏠 Overview

**HoneStay API** is a modern, scalable homestay booking platform API built on Laravel Apiato framework. The project provides a complete backend solution for homestay bookings with features like property management, user authentication, booking system, payment integration, and more.

### ✨ Key Features

- **🏡 Property Management** - Complete CRUD operations for homestay properties
- **👥 User Management** - Host and guest user management with role-based access
- **📅 Booking System** - Full booking lifecycle management
- **💳 Payment Integration** - Secure payment processing
- **🔐 OAuth2.0 Authentication** - Secure API authentication
- **📱 RESTful API** - Clean, well-documented REST endpoints
- **🌐 CORS Support** - Configurable cross-origin resource sharing
- **🐳 Docker Support** - Containerized development environment
- **🚀 High Performance** - Optimized with Redis caching
- **📊 Database** - MySQL database with proper indexing
- **📝 API Documentation** - Comprehensive API documentation

---

## 🛠 Tech Stack

- **Backend**: Laravel 10.x with Apiato framework
- **Database**: MySQL 8.0
- **Cache**: Redis 7.0
- **Web Server**: Nginx
- **PHP**: PHP 8.1-FPM
- **Authentication**: Laravel Passport (OAuth2.0)
- **Containerization**: Docker & Docker Compose

---

## 🚀 Quick Start with Docker

### Prerequisites

- Docker Desktop (or Docker Engine + Docker Compose)
- Git

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Homestay
```

### 2. Environment Setup

Create environment files from templates:

```bash
# Copy Docker environment file
cp docker/.env.example docker/.env

# Copy Laravel environment file  
cp homestay-api/.env.example homestay-api/.env
```

### 3. Configure Environment Variables

Edit `homestay-api/.env` with the following Docker-specific settings:

```env
# Database Configuration (Docker)
DB_CONNECTION=mysql
DB_HOST=honestay_mysql
DB_PORT=3306
DB_DATABASE=honestay
DB_USERNAME=root
DB_PASSWORD=root

# Redis Configuration (Docker)
REDIS_HOST=honestay_redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Application Settings
APP_NAME="HoneStay API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Generate a new key after first container startup
APP_KEY=base64:your-app-key-here

# CORS Configuration (for frontend development)
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:3001"
CORS_ALLOWED_METHODS="GET,POST,PUT,DELETE,OPTIONS,PATCH"
CORS_ALLOWED_HEADERS="Content-Type,Authorization,X-Requested-With,Accept,Origin,X-CSRF-TOKEN"
CORS_ALLOW_CREDENTIALS=true
CORS_MAX_AGE=86400
```

### 4. Build and Start Services

```bash
# Navigate to docker directory
cd docker

# Build and start all services
docker-compose up -d --build

# Wait for all services to be ready (check status)
docker-compose ps
```

### 5. Application Setup

```bash
# Access the PHP-FPM container
docker exec -it honestay_php_fpm bash

# Generate application key
php artisan key:generate

# Install dependencies (if needed)
composer install

# Run database migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed

# Install Passport OAuth2 keys
php artisan passport:install

# Clear and cache configurations
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### 6. Verify Installation

Test the API endpoints:

```bash
# Check API health
curl http://localhost:8080/

# Check API info
curl http://localhost:8080/info

# Test API endpoint (after CORS setup)
curl -H "Origin: http://localhost:3000" http://localhost:8080/v1/rooms

# List available routes
docker exec -it honestay_php_fpm php artisan route:list
```

## 🌐 CORS Configuration

For frontend development, CORS is pre-configured to allow requests from common development ports.

**Quick CORS Setup:**
1. Environment variables are already configured in `.env.example`
2. Copy to your `.env` file during setup
3. Clear config cache: `php artisan config:clear`

**📚 For detailed CORS setup:** See [`docs/CORS_SETUP_GUIDE.md`](./docs/CORS_SETUP_GUIDE.md)

---

## 🐳 Docker Services

The application runs with the following Docker services:

| Service | Container Name | Port | Description |
|---------|---------------|------|-------------|
| **Nginx** | `honestay_nginx` | 80, 443 | Web server & reverse proxy |
| **PHP-FPM** | `honestay_php_fpm` | 9000 | PHP application server |
| **MySQL** | `honestay_mysql` | 3306 | Database server |
| **Redis** | `honestay_redis` | 6379 | Cache & session store |

### Docker Commands

```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f [service_name]

# Rebuild services
docker-compose up -d --build

# Access containers
docker exec -it honestay_php_fpm bash
docker exec -it honestay_mysql bash
docker exec -it honestay_redis redis-cli
```

---

## 📁 Project Structure

```
HoneStay/
├── docker/                    # Docker configuration
│   ├── docker-compose.yml    # Docker Compose file
│   ├── .env                  # Docker environment variables
│   ├── nginx/                # Nginx configuration
│   ├── php-fpm/             # PHP-FPM configuration
│   ├── mysql/               # MySQL configuration
│   └── redis/               # Redis configuration
│
└── homestay-api/            # Laravel Apiato application
    ├── app/
    │   ├── Containers/      # Apiato containers (features)
    │   └── Ship/           # Apiato ship (core)
    ├── config/             # Configuration files
    ├── database/           # Migrations & seeders
    ├── public/             # Web root
    ├── routes/             # Route definitions
    ├── storage/            # File storage
    └── .env               # Laravel environment
```

---

## 🗂️ Project Section Structure & Best Practices

### Section Structure (Porto/Apiato)

```
homestay-api/
└── app/
    └── Containers/
        ├── SharedSection/      # Chứa migration, model, repository, service DÙNG CHUNG cho toàn hệ thống
        │   ├── Database/Migrations/   # Migration dùng chung (users, bookings, ...)
        │   ├── Core/Models/           # Model dùng chung
        │   └── ...
        ├── AdminSection/       # Chỉ chứa business logic, API, repository, action, task cho admin
        │   └── ...
        ├── ClientSection/      # Chỉ chứa business logic, API, repository, action, task cho client/guest
        │   └── ...
        └── ...
```

- **SharedSection**: Chỉ chứa migration, model, repository, service dùng chung. KHÔNG chứa business logic (action, controller, task, ...).
- **AdminSection**: Chỉ chứa logic/phục vụ cho admin. KHÔNG gọi code từ ClientSection.
- **ClientSection**: Chỉ chứa logic/phục vụ cho client/guest. KHÔNG gọi code từ AdminSection.

### 🛡️ Quy tắc kế thừa & phụ thuộc

- **Repository/model dùng chung**: Đặt ở SharedSection, cả AdminSection và ClientSection đều kế thừa hoặc sử dụng lại.
- **Không được gọi lẫn nhau giữa các Section**: AdminSection KHÔNG gọi code (action, controller, repository, model) từ ClientSection và ngược lại.
- **Nếu cần dùng chung**: Đưa vào SharedSection.
- **Không đặt migration, model, repository domain ở Ship**: Ship chỉ chứa code core, helper, trait, base class, contract dùng chung.

### 🚫 Lý do nghiêm ngặt

- Đảm bảo khi tách riêng hai server (admin/client) không bị lỗi config, không bị phụ thuộc lẫn nhau.
- Dễ dàng deploy, scale, maintain từng server độc lập.
- Tránh circular dependency, tránh lỗi khi build hoặc migrate.

### ✅ Ví dụ kế thừa repository/model dùng chung

```php
// SharedSection/Repository/UserRepository.php
namespace App\Containers\SharedSection\User\Data\Repositories;

use App\Containers\SharedSection\User\Data\Models\User;
use App\Ship\Parents\Repositories\Repository;

class UserRepository extends Repository
{
    protected function model(): string
    {
        return User::class;
    }
}
```

```php
// AdminSection sử dụng lại repository/model từ SharedSection
use App\Containers\SharedSection\User\Data\Repositories\UserRepository;

class AdminUserController extends ApiController
{
    public function __construct(private UserRepository $userRepository) {}
}
```

```php
// ClientSection cũng sử dụng lại repository/model từ SharedSection
use App\Containers\SharedSection\User\Data\Repositories\UserRepository;

class ClientProfileController extends ApiController
{
    public function __construct(private UserRepository $userRepository) {}
}
```

### 🔒 Lưu ý khi phát triển
- Không import, không gọi action, controller, repository, model giữa các Section admin/client.
- Nếu cần dùng chung, luôn refactor sang SharedSection.
- Khi tách server, chỉ cần copy SharedSection và config lại là chạy độc lập.

---

## 🔧 Development

### Artisan Commands

```bash
# Access PHP container
docker exec -it honestay_php_fpm bash

# Common Laravel commands
php artisan migrate              # Run migrations
php artisan db:seed             # Seed database
php artisan route:list          # List routes
php artisan cache:clear         # Clear cache
php artisan config:clear        # Clear config cache
php artisan queue:work          # Start queue worker
```

### Database Management

```bash
# Access MySQL container
docker exec -it honestay_mysql mysql -u root -p

# Or use external tools with:
# Host: localhost
# Port: 3306
# Username: root
# Password: root
# Database: honestay
```

### Logs & Debugging

```bash
# View application logs
docker exec -it honestay_php_fpm tail -f storage/logs/laravel.log

# View web server logs
docker-compose logs -f nginx

# View database logs
docker-compose logs -f mysql
```

---

## 🔐 Authentication

The API uses Laravel Passport for OAuth2.0 authentication.

### Setup Passport

```bash
# Install Passport keys
php artisan passport:install

# Create personal access client
php artisan passport:client --personal
```

### API Authentication

```bash
# Example login request
curl -X POST http://localhost/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

---

## 📚 API Documentation

### Available Endpoints

Access the API documentation at: `http://localhost/docs` (if configured)

### Key Endpoints

- **Authentication**: `/api/v1/login`, `/api/v1/register`
- **Properties**: `/api/v1/properties`
- **Bookings**: `/api/v1/bookings`
- **Users**: `/api/v1/users`

---

## 🧪 Testing

```bash
# Run tests
docker exec -it honestay_php_fpm php artisan test

# Run specific test suite
docker exec -it honestay_php_fpm php artisan test --testsuite=Feature
```

---

## 📋 Troubleshooting

### Common Issues

**1. Permission Issues**
```bash
# Fix storage permissions
docker exec -it honestay_php_fpm chmod -R 775 storage bootstrap/cache
docker exec -it honestay_php_fpm chown -R www-data:www-data storage bootstrap/cache
```

**2. Database Connection Issues**
```bash
# Check MySQL container status
docker-compose logs mysql

# Verify environment variables
docker exec -it honestay_php_fpm env | grep DB_
```

**3. Clear All Caches**
```bash
docker exec -it honestay_php_fpm php artisan optimize:clear
```

**4. Rebuild Containers**
```bash
docker-compose down
docker-compose up -d --build --force-recreate
```

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write tests for new features
5. Submit a pull request

---

## 📄 License

This project is licensed under the MIT License.

---

## 📞 Support

For support and questions:
- Create an issue in the repository
- Check the troubleshooting section
- Review Docker and Laravel logs
