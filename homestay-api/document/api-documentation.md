# API Documentation - Homestay API

## Tổng quan

Homestay API được xây dựng trên framework Apiato (Laravel-based) cung cấp các endpoints RESTful để quản lý hệ thống homestay.

## Base URL

- **Development**: `http://localhost:8080/api`
- **Production**: `https://api.honestay.com/api`

## Authentication

Homestay API sử dụng Laravel Passport cho OAuth2 authentication.

### 1. Lấy Access Token

#### Personal Access Token
```http
POST /api/oauth/token
Content-Type: application/json

{
    "grant_type": "password",
    "client_id": "0197db1f-f249-71ea-9a82-2e83aedd866f",
    "client_secret": "BfeJniVcLyaxb7deiWnHSJREVzNBSO2X5Vebc1rf",
    "username": "user@example.com",
    "password": "password",
    "scope": "*"
}
```

#### Response
```json
{
    "token_type": "Bearer",
    "expires_in": 86400,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "refresh_token": "def50200..."
}
```

### 2. Sử dụng Token

Thêm token vào header cho tất cả authenticated requests:

```http
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...
```

## Common Headers

```http
Content-Type: application/json
Accept: application/json
Authorization: Bearer {access_token}
X-Requested-With: XMLHttpRequest
```

## Response Format

Tất cả API responses đều theo format chuẩn:

### Success Response
```json
{
    "data": {
        // Response data
    },
    "meta": {
        "include": [],
        "custom": []
    }
}
```

### Error Response
```json
{
    "message": "Error description",
    "errors": {
        "field": ["Error detail"]
    },
    "status_code": 422
}
```

### Pagination Response
```json
{
    "data": [...],
    "meta": {
        "pagination": {
            "total": 100,
            "count": 10,
            "per_page": 10,
            "current_page": 1,
            "total_pages": 10,
            "links": {
                "next": "https://api.honestay.com/api/users?page=2"
            }
        }
    }
}
```

## Authentication Endpoints

### 1. User Registration

```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "data": {
        "id": "abcd1234",
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2025-07-12T10:30:00.000000Z",
        "updated_at": "2025-07-12T10:30:00.000000Z"
    }
}
```

### 2. User Login

```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
        "token_type": "Bearer",
        "expires_in": 86400,
        "user": {
            "id": "abcd1234",
            "name": "John Doe",
            "email": "john@example.com"
        }
    }
}
```

### 3. User Logout

```http
POST /api/logout
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "message": "Successfully logged out"
}
```

### 4. Refresh Token

```http
POST /api/refresh
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
        "token_type": "Bearer",
        "expires_in": 86400
    }
}
```

## User Management

### 1. Get Current User

```http
GET /api/user
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "data": {
        "id": "abcd1234",
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2025-07-12T10:30:00.000000Z",
        "created_at": "2025-07-12T10:30:00.000000Z",
        "updated_at": "2025-07-12T10:30:00.000000Z",
        "roles": [
            {
                "id": "role1234",
                "name": "user",
                "display_name": "Regular User"
            }
        ]
    }
}
```

### 2. Update User Profile

```http
PUT /api/user
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

### 3. Change Password

```http
PUT /api/user/password
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "current_password": "oldpassword",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### 4. List Users (Admin)

```http
GET /api/users?page=1&limit=10&search=john
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `page`: Page number (default: 1)
- `limit`: Items per page (default: 10, max: 100)
- `search`: Search by name or email
- `role`: Filter by role
- `status`: Filter by status (active, inactive)

## Homestay Management

### 1. List Homestays

```http
GET /api/homestays?page=1&limit=10
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `page`: Page number
- `limit`: Items per page
- `location`: Filter by location
- `price_min`: Minimum price
- `price_max`: Maximum price
- `available_from`: Available from date (Y-m-d)
- `available_to`: Available to date (Y-m-d)

**Response:**
```json
{
    "data": [
        {
            "id": "homestay123",
            "title": "Beautiful Beach House",
            "description": "A lovely homestay by the beach",
            "location": "Da Nang, Vietnam",
            "price_per_night": 50.00,
            "max_guests": 4,
            "bedrooms": 2,
            "bathrooms": 1,
            "amenities": ["wifi", "parking", "kitchen"],
            "images": [
                "https://example.com/image1.jpg",
                "https://example.com/image2.jpg"
            ],
            "host": {
                "id": "user123",
                "name": "Host Name",
                "email": "host@example.com"
            },
            "created_at": "2025-07-12T10:30:00.000000Z"
        }
    ],
    "meta": {
        "pagination": {
            "total": 50,
            "count": 10,
            "per_page": 10,
            "current_page": 1,
            "total_pages": 5
        }
    }
}
```

### 2. Get Homestay Details

```http
GET /api/homestays/{id}
Authorization: Bearer {access_token}
```

### 3. Create Homestay

```http
POST /api/homestays
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "title": "Beautiful Beach House",
    "description": "A lovely homestay by the beach with amazing view",
    "location": "Da Nang, Vietnam",
    "address": "123 Beach Street, Da Nang",
    "price_per_night": 50.00,
    "max_guests": 4,
    "bedrooms": 2,
    "bathrooms": 1,
    "amenities": ["wifi", "parking", "kitchen", "air_conditioning"],
    "rules": ["No smoking", "No pets"],
    "check_in_time": "14:00",
    "check_out_time": "11:00"
}
```

### 4. Update Homestay

```http
PUT /api/homestays/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "title": "Updated Beach House",
    "price_per_night": 55.00
}
```

### 5. Delete Homestay

```http
DELETE /api/homestays/{id}
Authorization: Bearer {access_token}
```

### 6. Upload Homestay Images

```http
POST /api/homestays/{id}/images
Authorization: Bearer {access_token}
Content-Type: multipart/form-data

images[]: file1.jpg
images[]: file2.jpg
```

## Booking Management

### 1. Create Booking

```http
POST /api/bookings
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "homestay_id": "homestay123",
    "check_in_date": "2025-08-01",
    "check_out_date": "2025-08-05",
    "guests": 2,
    "special_requests": "Late check-in"
}
```

### 2. List User Bookings

```http
GET /api/bookings?status=confirmed&page=1
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `status`: pending, confirmed, cancelled, completed
- `upcoming`: true/false
- `page`: Page number

### 3. Get Booking Details

```http
GET /api/bookings/{id}
Authorization: Bearer {access_token}
```

### 4. Cancel Booking

```http
PUT /api/bookings/{id}/cancel
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "reason": "Change of plans"
}
```

### 5. Confirm Booking (Host)

```http
PUT /api/bookings/{id}/confirm
Authorization: Bearer {access_token}
```

## Review System

### 1. Create Review

```http
POST /api/reviews
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "booking_id": "booking123",
    "rating": 5,
    "comment": "Amazing stay! Highly recommended.",
    "cleanliness_rating": 5,
    "location_rating": 4,
    "value_rating": 5
}
```

### 2. List Homestay Reviews

```http
GET /api/homestays/{id}/reviews?page=1
```

### 3. Update Review

```http
PUT /api/reviews/{id}
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "rating": 4,
    "comment": "Updated review comment"
}
```

## Payment Integration

### 1. Create Payment Intent

```http
POST /api/payments/intent
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "booking_id": "booking123",
    "payment_method": "stripe"
}
```

### 2. Confirm Payment

```http
POST /api/payments/confirm
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "payment_intent_id": "pi_123456789",
    "payment_method_id": "pm_123456789"
}
```

### 3. Process Refund

```http
POST /api/payments/refund
Authorization: Bearer {access_token}
Content-Type: application/json

{
    "booking_id": "booking123",
    "amount": 100.00,
    "reason": "Cancellation within refund period"
}
```

## Notifications

### 1. Get User Notifications

```http
GET /api/notifications?unread_only=true&page=1
Authorization: Bearer {access_token}
```

### 2. Mark Notification as Read

```http
PUT /api/notifications/{id}/read
Authorization: Bearer {access_token}
```

### 3. Mark All as Read

```http
PUT /api/notifications/read-all
Authorization: Bearer {access_token}
```

## Search & Filters

### 1. Search Homestays

```http
GET /api/search/homestays
Authorization: Bearer {access_token}
```

**Query Parameters:**
- `q`: Search query (title, description, location)
- `location`: Location filter
- `check_in`: Check-in date (Y-m-d)
- `check_out`: Check-out date (Y-m-d)
- `guests`: Number of guests
- `price_min`: Minimum price
- `price_max`: Maximum price
- `amenities[]`: Array of amenities
- `sort`: price_asc, price_desc, rating, newest
- `page`: Page number
- `limit`: Results per page

### 2. Get Popular Locations

```http
GET /api/locations/popular
```

### 3. Get Available Amenities

```http
GET /api/amenities
```

## Admin Endpoints

### 1. Dashboard Statistics

```http
GET /api/admin/dashboard
Authorization: Bearer {admin_access_token}
```

**Response:**
```json
{
    "data": {
        "total_users": 1250,
        "total_homestays": 480,
        "total_bookings": 2340,
        "total_revenue": 125000.00,
        "pending_reviews": 15,
        "recent_bookings": [...],
        "top_locations": [...],
        "monthly_revenue": [...]
    }
}
```

### 2. Manage Users

```http
GET /api/admin/users?role=host&status=active
PUT /api/admin/users/{id}/status
DELETE /api/admin/users/{id}
```

### 3. Moderate Content

```http
GET /api/admin/homestays/pending
PUT /api/admin/homestays/{id}/approve
PUT /api/admin/homestays/{id}/reject
```

## Webhooks

### 1. Payment Webhook

```http
POST /api/webhooks/payment
Content-Type: application/json
X-Webhook-Signature: {signature}

{
    "event": "payment.completed",
    "data": {
        "payment_id": "pay_123",
        "booking_id": "booking_123",
        "amount": 100.00,
        "status": "completed"
    }
}
```

## Rate Limiting

API có rate limiting để ngăn chặn abuse:

- **Authenticated requests**: 60 requests/minute
- **Unauthenticated requests**: 30 requests/minute
- **Search endpoints**: 100 requests/minute

Headers response bao gồm:
```http
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1625097600
```

## Error Codes

| Code | Message | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created |
| 400 | Bad Request | Invalid request format |
| 401 | Unauthorized | Authentication required |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation errors |
| 429 | Too Many Requests | Rate limit exceeded |
| 500 | Internal Server Error | Server error |

## SDKs và Examples

### JavaScript/Node.js

```javascript
const axios = require('axios');

const api = axios.create({
    baseURL: 'https://api.honestay.com/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Set auth token
api.defaults.headers.common['Authorization'] = `Bearer ${accessToken}`;

// Get homestays
const homestays = await api.get('/homestays');
```

### PHP

```php
$client = new GuzzleHttp\Client([
    'base_uri' => 'https://api.honestay.com/api',
    'headers' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Authorization' => 'Bearer ' . $accessToken
    ]
]);

$response = $client->get('/homestays');
$data = json_decode($response->getBody(), true);
```

### cURL

```bash
# Get homestays
curl -X GET "https://api.honestay.com/api/homestays" \
     -H "Authorization: Bearer {access_token}" \
     -H "Accept: application/json"

# Create booking
curl -X POST "https://api.honestay.com/api/bookings" \
     -H "Authorization: Bearer {access_token}" \
     -H "Content-Type: application/json" \
     -d '{
       "homestay_id": "homestay123",
       "check_in_date": "2025-08-01",
       "check_out_date": "2025-08-05",
       "guests": 2
     }'
```

## Testing

API có test suite đầy đủ. Để chạy tests:

```bash
# Unit tests
docker exec honestay_php_fpm php artisan test

# API tests
docker exec honestay_php_fpm php artisan test --filter=ApiTest

# Feature tests
docker exec honestay_php_fpm php artisan test --filter=FeatureTest
```

## Changelog

### v1.0.0 (2025-07-12)
- Initial API release
- Authentication with Laravel Passport
- Homestay CRUD operations
- Booking system
- Review system
- Payment integration

---

Để biết thêm thông tin hoặc hỗ trợ, vui lòng liên hệ team phát triển.
