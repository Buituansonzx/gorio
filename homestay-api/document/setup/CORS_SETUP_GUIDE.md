# CORS Configuration Guide - Homestay API

## 📋 Tổng quan

Tài liệu này hướng dẫn cách cấu hình Cross-Origin Resource Sharing (CORS) cho Homestay API để cho phép frontend từ các domain khác truy cập API.

## 🎯 Vấn đề CORS

CORS error xảy ra khi:
- Frontend chạy trên `http://localhost:3000`
- API chạy trên `http://localhost:8080`
- Browser chặn request cross-origin theo security policy

**Lỗi thường gặp:**
```
Access to fetch at 'http://localhost:8080/v1/rooms' from origin 'http://localhost:3000' 
has been blocked by CORS policy
```

## ⚙️ Cấu hình CORS

### 1. Environment Variables

Thêm các biến sau vào file `.env`:

```bash
# CORS Configuration
# Comma-separated list of allowed origins for Cross-Origin requests
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:3001,http://127.0.0.1:3000"
# HTTP methods allowed for CORS requests
CORS_ALLOWED_METHODS="GET,POST,PUT,DELETE,OPTIONS,PATCH"
# Headers allowed in CORS requests
CORS_ALLOWED_HEADERS="Content-Type,Authorization,X-Requested-With,Accept,Origin,X-CSRF-TOKEN"
# Whether to support credentials in CORS requests
CORS_ALLOW_CREDENTIALS=true
# Cache duration for preflight requests (in seconds)
CORS_MAX_AGE=86400
```

### 2. CORS Middleware

File: `app/Ship/Middleware/CorsMiddleware.php`

```php
<?php

namespace App\Ship\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() == "OPTIONS") {
            $response = response('', 200);
        } else {
            $response = $next($request);
        }

        // Get CORS configuration from ENV
        $allowedOrigins = explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000'));
        $allowedMethods = env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS,PATCH');
        $allowedHeaders = env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With,Accept,Origin');
        $allowCredentials = env('CORS_ALLOW_CREDENTIALS', 'true');
        $maxAge = env('CORS_MAX_AGE', 86400);

        // Set origin header (handle multiple origins)
        $origin = $request->headers->get('Origin');
        if (in_array($origin, $allowedOrigins) || in_array('*', $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin ?: $allowedOrigins[0]);
        }

        // Add other CORS headers
        $response->headers->set('Access-Control-Allow-Methods', $allowedMethods);
        $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
        $response->headers->set('Access-Control-Allow-Credentials', $allowCredentials);
        $response->headers->set('Access-Control-Max-Age', $maxAge);

        return $response;
    }
}
```

### 3. Middleware Registration

File: `bootstrap/app.php`

```php
use App\Ship\Middleware\CorsMiddleware;

return Application::configure(basePath: $basePath)
    // ... other configurations
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            CorsMiddleware::class, // ✅ Thêm CORS middleware
            ValidateAppId::class,
        ]);
        // ... other middleware
    })
```

### 4. Config File Update

File: `config/cors.php`

```php
return [
    'paths' => ['*', 'sanctum/csrf-cookie'],
    
    'allowed_methods' => explode(',', env('CORS_ALLOWED_METHODS', 'GET,POST,PUT,DELETE,OPTIONS,PATCH')),
    
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000')),
    
    'allowed_origins_patterns' => [],
    
    'allowed_headers' => explode(',', env('CORS_ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With,Accept,Origin')),
    
    'exposed_headers' => [],
    
    'max_age' => env('CORS_MAX_AGE', 0),
    
    'supports_credentials' => env('CORS_ALLOW_CREDENTIALS', false),
];
```

## 🚀 Cài đặt

### Bước 1: Copy ENV Configuration

```bash
# Copy từ .env.example
cp .env.example .env

# Hoặc thêm trực tiếp vào .env hiện tại
echo '
# CORS Configuration
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:3001,http://127.0.0.1:3000"
CORS_ALLOWED_METHODS="GET,POST,PUT,DELETE,OPTIONS,PATCH"
CORS_ALLOWED_HEADERS="Content-Type,Authorization,X-Requested-With,Accept,Origin,X-CSRF-TOKEN"
CORS_ALLOW_CREDENTIALS=true
CORS_MAX_AGE=86400' >> .env
```

### Bước 2: Clear Cache

```bash
# Clear Laravel config cache
docker exec -w /var/www/html/homestay-api honestay_php_fpm php artisan config:clear

# Clear route cache
docker exec -w /var/www/html/homestay-api honestay_php_fpm php artisan route:clear
```

### Bước 3: Test CORS

```bash
# Test CORS từ localhost:3000
curl -v -H "Origin: http://localhost:3000" \
  -X GET "http://localhost:8080/v1/rooms" \
  -H "Accept: application/json"
```

**Expected Response Headers:**
```
< Access-Control-Allow-Origin: http://localhost:3000
< Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH
< Access-Control-Allow-Headers: Content-Type,Authorization,X-Requested-With,Accept,Origin,X-CSRF-TOKEN
< Access-Control-Allow-Credentials: true
< Access-Control-Max-Age: 86400
```

## 🌐 Frontend Usage

### JavaScript Fetch

```javascript
// ✅ Hoạt động sau khi cấu hình CORS
fetch('http://localhost:8080/v1/rooms', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  },
  credentials: 'include' // Nếu cần cookies/auth
})
.then(response => response.json())
.then(data => console.log(data));
```

### Axios

```javascript
// ✅ Hoạt động sau khi cấu hình CORS
axios.get('http://localhost:8080/v1/rooms', {
  headers: {
    'Accept': 'application/json'
  },
  withCredentials: true // Nếu cần cookies/auth
});
```

## 🛠️ Environment Specific Configuration

### Development
```bash
CORS_ALLOWED_ORIGINS="http://localhost:3000,http://localhost:3001,http://127.0.0.1:3000"
```

### Staging
```bash
CORS_ALLOWED_ORIGINS="https://staging.yourdomain.com,http://localhost:3000"
```

### Production
```bash
CORS_ALLOWED_ORIGINS="https://yourdomain.com,https://admin.yourdomain.com"
```

## 🔧 Troubleshooting

### Lỗi thường gặp

#### 1. CORS vẫn bị block
```bash
# Kiểm tra middleware có được load không
docker exec -w /var/www/html/homestay-api honestay_php_fpm php artisan route:list

# Clear tất cả cache
docker exec -w /var/www/html/homestay-api honestay_php_fpm php artisan optimize:clear
```

#### 2. Preflight OPTIONS request failed
```bash
# Test OPTIONS request
curl -X OPTIONS \
  -H "Origin: http://localhost:3000" \
  -H "Access-Control-Request-Method: GET" \
  -H "Access-Control-Request-Headers: Content-Type" \
  http://localhost:8080/v1/rooms
```

#### 3. Multiple CORS headers
- Đảm bảo chỉ có 1 middleware CORS
- Không thêm CORS headers trong controller khi đã có middleware

### Debug Commands

```bash
# Kiểm tra ENV variables
docker exec honestay_php_fpm printenv | grep CORS

# Kiểm tra config được load
docker exec -w /var/www/html/homestay-api honestay_php_fpm \
  php artisan tinker --execute="var_dump(config('cors'));"

# Test middleware
docker exec -w /var/www/html/homestay-api honestay_php_fpm \
  php artisan tinker --execute="var_dump(app('App\Ship\Middleware\CorsMiddleware'));"
```

## 📚 Tham khảo

- [MDN CORS Documentation](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)
- [Laravel CORS Package](https://github.com/fruitcake/laravel-cors)
- [Understanding CORS Preflight](https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request)

## ✅ Checklist

- [ ] Thêm CORS env variables vào `.env`
- [ ] Tạo `CorsMiddleware.php`
- [ ] Đăng ký middleware trong `bootstrap/app.php`
- [ ] Cập nhật `config/cors.php`
- [ ] Clear cache
- [ ] Test CORS với curl
- [ ] Test từ frontend

---
**📝 Note:** Tài liệu này được tạo cho Homestay API project sử dụng Apiato framework với Docker.
