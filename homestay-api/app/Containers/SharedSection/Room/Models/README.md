# Room Models Documentation

## Overview
Các model đã được tạo trong thư mục `app/Containers/SharedSection/Room/Models/` để hỗ trợ hệ thống quản lý phòng homestay.

## Danh sách Models

### 1. Core Models

#### Room.php
- Model chính quản lý thông tin phòng
- Relationships: có quan hệ với tất cả các model khác
- Casts: floor, capacity, area, latitude, longitude

#### RoomType.php
- Quản lý các loại phòng (apartment, villa, ...)
- Casts: name, description (JSON cho đa ngôn ngữ)
- Relationship: hasMany rooms

#### RoomAccessType.php
- Quản lý loại hình truy cập phòng
- Casts: name, description (JSON cho đa ngôn ngữ)
- Relationship: hasMany rooms

### 2. Attribute Models

#### Attribute.php
- Quản lý các thuộc tính chung (hướng, tầng, diện tích, ...)
- Casts: name, description (JSON cho đa ngôn ngữ)
- Relationship: hasMany roomAttributes

#### RoomAttribute.php
- Bảng liên kết phòng với thuộc tính
- Relationships: belongsTo room, belongsTo attribute

### 3. Facility Models

#### SurroundingFacility.php
- Quản lý tiện ích xung quanh
- Casts: name, description (JSON cho đa ngôn ngữ)
- Relationship: hasMany roomSurroundingFacilities

#### RoomSurroundingFacility.php
- Liên kết phòng với tiện ích xung quanh + khoảng cách
- Relationships: belongsTo room, belongsTo facility

#### RoomHighlightFacility.php
- Quản lý tiện ích nổi bật riêng của phòng
- Casts: name, description (JSON cho đa ngôn ngữ)
- Relationships: belongsTo room, hasMany images

#### RoomHighlightFacilityImage.php
- Ảnh cho tiện ích nổi bật
- Casts: is_cover (boolean), order_index (integer)
- Relationship: belongsTo facility

### 4. Image Models

#### RoomImage.php
- Quản lý ảnh phòng
- Casts: is_cover (boolean), order_index (integer)
- Relationship: belongsTo room

### 5. Pricing Models

#### PriceRatio.php
- Quản lý hệ số giá
- Casts: ratio (decimal:2), name, description (JSON)
- Relationship: hasMany roomDiscountPolicies

#### RoomPricingPolicy.php
- Chính sách giá cơ bản cho phòng
- Casts: checkin_time, checkout_time, các giá tiền (decimal:2)
- Relationships: belongsTo room, hasMany weekdayPrices

#### RoomWeekdayPrice.php
- Giá phòng theo từng ngày trong tuần
- Casts: weekday (integer), price (decimal:2)
- Relationship: belongsTo policy

#### RoomPriceHistory.php
- Lịch sử thay đổi giá
- Casts: policy_snapshot (array), changed_at (datetime)
- Relationship: belongsTo room

### 6. Discount & Special Pricing Models

#### RoomDiscountPolicy.php
- Chính sách giảm giá (dayuse, late checkin/checkout)
- Casts: các thời gian (datetime:H:i)
- Relationships: belongsTo room, belongsTo priceRatio

#### RoomHourlyPricing.php
- Giá phòng theo giờ
- Casts: min_hours (integer), giá tiền (decimal:2)
- Relationship: belongsTo room

#### RoomComboPricing.php
- Giá combo theo khung giờ và ngày trong tuần
- Casts: start_time, end_time, các giá theo ngày (decimal:2)
- Relationship: belongsTo room

#### RoomSpecialOffer.php
- Ưu đãi đặc biệt (cận ngày, theo tháng)
- Casts: last_minute_hours (integer), giá tiền (decimal:2)
- Relationship: belongsTo room

## Chú ý
- Tất cả model đều extend từ `App\Ship\Parents\Models\Model`
- Các trường đa ngôn ngữ sử dụng cast 'array' cho JSON
- Các trường tiền tệ sử dụng cast 'decimal:2'
- Các trường thời gian sử dụng cast 'datetime:H:i' cho TIME
- Relationships đã được thiết lập đầy đủ giữa các model

## Usage Example
```php
// Lấy phòng với tất cả thông tin liên quan
$room = Room::with([
    'roomType',
    'accessType', 
    'attributes.attribute',
    'surroundingFacilities.facility',
    'images',
    'highlightFacilities.images',
    'pricingPolicies.weekdayPrices',
    'discountPolicies.priceRatio',
    'hourlyPricing',
    'comboPricing',
    'specialOffers'
])->find(1);
```
