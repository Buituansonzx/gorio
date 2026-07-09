# ClientSection - Customer/Guest Portal

## Overview
ClientSection chứa tất cả các module và API dành cho khách hàng cuối (guest/customer) trên nền tảng đặt phòng homestay. Section này xử lý các chức năng mà khách hàng sử dụng trực tiếp qua ứng dụng mobile hoặc website.

## Core Modules

### 1. Authentication Module
- **Mục đích**: Quản lý đăng ký, đăng nhập, xác thực khách hàng
- **Components**:
  - Guest registration/login
  - Social login (Facebook, Google)
  - Phone number verification
  - Password reset
  - JWT token management

### 2. Profile Module
- **Mục đích**: Quản lý thông tin cá nhân khách hàng
- **Components**:
  - Profile information (name, email, phone, avatar)
  - Identity verification (CCCD/CMND, passport)
  - Preferences and settings
  - Emergency contacts

### 3. Search Module
- **Mục đích**: Tìm kiếm và lọc homestay
- **Components**:
  - Location-based search
  - Date availability search
  - Price range filtering
  - Amenities filtering
  - Advanced search criteria
  - Map integration

### 4. Homestay Module
- **Mục đích**: Hiển thị thông tin chi tiết homestay
- **Components**:
  - Property details and descriptions
  - Photo galleries and virtual tours
  - Amenities and facilities
  - House rules and policies
  - Reviews and ratings
  - Availability calendar

### 5. Booking Module
- **Mục đích**: Xử lý quy trình đặt phòng
- **Components**:
  - Booking creation and management
  - Date selection and validation
  - Guest information collection
  - Special requests handling
  - Booking confirmation
  - Modification and cancellation

### 6. Payment Module
- **Mục đích**: Xử lý thanh toán đặt phòng
- **Components**:
  - Payment method management
  - Secure payment processing
  - Payment confirmations
  - Refund handling
  - Payment history
  - Multi-currency support

### 7. Communication Module
- **Mục đích**: Giao tiếp giữa khách và chủ nhà
- **Components**:
  - In-app messaging
  - Booking-related notifications
  - Host contact information
  - Emergency communication
  - Translation support

### 8. Review Module
- **Mục đích**: Đánh giá và phản hồi
- **Components**:
  - Review submission
  - Rating system (cleanliness, accuracy, communication, etc.)
  - Photo uploads with reviews
  - Host response to reviews
  - Review moderation

### 9. Support Module
- **Mục đích**: Hỗ trợ khách hàng
- **Components**:
  - Help center and FAQs
  - Contact support
  - Issue reporting
  - Live chat integration
  - Complaint handling

### 10. Loyalty Module
- **Mục đích**: Chương trình khách hàng thân thiết
- **Components**:
  - Points and rewards system
  - Loyalty level management
  - Special offers and discounts
  - Referral programs
  - Member benefits

## API Structure

### Authentication APIs
```
POST /api/v1/client/auth/register
POST /api/v1/client/auth/login
POST /api/v1/client/auth/logout
POST /api/v1/client/auth/refresh
POST /api/v1/client/auth/forgot-password
POST /api/v1/client/auth/reset-password
POST /api/v1/client/auth/verify-phone
```

### Profile APIs
```
GET    /api/v1/client/profile
PUT    /api/v1/client/profile
POST   /api/v1/client/profile/avatar
POST   /api/v1/client/profile/verify-identity
GET    /api/v1/client/profile/verification-status
```

### Search APIs
```
GET    /api/v1/client/search/homestays
GET    /api/v1/client/search/locations
GET    /api/v1/client/search/suggestions
POST   /api/v1/client/search/filters
```

### Homestay APIs
```
GET    /api/v1/client/homestays
GET    /api/v1/client/homestays/{id}
GET    /api/v1/client/homestays/{id}/availability
GET    /api/v1/client/homestays/{id}/reviews
GET    /api/v1/client/homestays/{id}/amenities
```

### Booking APIs
```
GET    /api/v1/client/bookings
POST   /api/v1/client/bookings
GET    /api/v1/client/bookings/{id}
PUT    /api/v1/client/bookings/{id}
DELETE /api/v1/client/bookings/{id}
POST   /api/v1/client/bookings/{id}/cancel
```

### Payment APIs
```
GET    /api/v1/client/payments/methods
POST   /api/v1/client/payments/methods
POST   /api/v1/client/payments/process
GET    /api/v1/client/payments/history
POST   /api/v1/client/payments/refund
```

### Communication APIs
```
GET    /api/v1/client/messages
POST   /api/v1/client/messages
GET    /api/v1/client/messages/{conversationId}
POST   /api/v1/client/messages/{conversationId}
```

### Review APIs
```
GET    /api/v1/client/reviews
POST   /api/v1/client/reviews
GET    /api/v1/client/reviews/{id}
PUT    /api/v1/client/reviews/{id}
```

## Key Features

### User Experience
- **Mobile-first design**: Optimized cho mobile app
- **Multilingual support**: Hỗ trợ đa ngôn ngữ
- **Offline capability**: Một số tính năng hoạt động offline
- **Push notifications**: Thông báo real-time

### Security & Privacy
- **Data encryption**: Mã hóa dữ liệu nhạy cảm
- **Privacy controls**: Kiểm soát quyền riêng tư
- **Secure payments**: Thanh toán an toàn
- **Identity verification**: Xác minh danh tính

### Performance
- **Fast search**: Tìm kiếm nhanh với caching
- **Image optimization**: Tối ưu hình ảnh
- **Lazy loading**: Tải nội dung theo yêu cầu
- **CDN integration**: Tích hợp CDN

## Database Relationships

### Core Entities
- **Customers**: Thông tin khách hàng
- **Bookings**: Thông tin đặt phòng
- **Payments**: Thông tin thanh toán
- **Reviews**: Đánh giá và nhận xét
- **Messages**: Tin nhắn giao tiếp
- **Loyalty**: Điểm thưởng và ưu đãi

### Relationships
- Customer hasMany Bookings
- Customer hasMany Reviews
- Customer hasMany Payments
- Booking belongsTo Customer
- Booking hasMany Payments
- Review belongsTo Customer and Homestay

## Development Guidelines

### Code Standards
- Follow Laravel/Apiato naming conventions
- Use proper validation and error handling
- Implement comprehensive logging
- Write unit and integration tests

### Security Considerations
- Validate all input data
- Implement rate limiting
- Use HTTPS for all communications
- Regular security audits

### Performance Optimization
- Use database indexing effectively
- Implement caching strategies
- Optimize database queries
- Use queues for heavy operations

## Testing Strategy
- Unit tests for all business logic
- Integration tests for API endpoints
- End-to-end tests for critical user flows
- Performance testing for high-load scenarios

## Future Enhancements
- AI-powered recommendations
- Voice search integration
- Augmented reality tours
- Blockchain-based reviews
- Smart home integration
