# Room Repositories Documentation

## Overview
Các repository đã được tạo trong thư mục `app/Containers/SharedSection/Room/Data/Repositories/` để hỗ trợ các thao tác database cho hệ thống quản lý phòng homestay.

## Danh sách Repositories

### 1. Core Repositories

#### RoomRepository.php
- Repository chính quản lý các thao tác với bảng rooms
- Searchable fields: id, name, description, room_type_id, access_type_id, status, floor, capacity, area, address, latitude, longitude

#### RoomTypeRepository.php
- Quản lý các loại phòng
- Searchable fields: id, code, name, description

#### RoomAccessTypeRepository.php
- Quản lý loại hình truy cập phòng
- Searchable fields: id, code, name, description

### 2. Attribute Repositories

#### AttributeRepository.php
- Quản lý các thuộc tính chung
- Searchable fields: id, code, name, description

#### RoomAttributeRepository.php
- Quản lý liên kết phòng-thuộc tính
- Searchable fields: id, room_id, attribute_id, value

### 3. Facility Repositories

#### SurroundingFacilityRepository.php
- Quản lý tiện ích xung quanh
- Searchable fields: id, code, name, description

#### RoomSurroundingFacilityRepository.php
- Quản lý liên kết phòng-tiện ích xung quanh
- Searchable fields: id, room_id, facility_id, distance

#### RoomHighlightFacilityRepository.php
- Quản lý tiện ích nổi bật của phòng
- Searchable fields: id, room_id, code, name, description

#### RoomHighlightFacilityImageRepository.php
- Quản lý ảnh tiện ích nổi bật
- Searchable fields: id, facility_id, image_url, s3_key, is_cover, order_index

### 4. Image Repositories

#### RoomImageRepository.php
- Quản lý ảnh phòng
- Searchable fields: id, room_id, image_url, s3_key, is_cover, order_index, area_type

### 5. Pricing Repositories

#### PriceRatioRepository.php
- Quản lý hệ số giá
- Searchable fields: id, code, ratio, name, description

#### RoomPricingPolicyRepository.php
- Quản lý chính sách giá phòng
- Searchable fields: id, room_id, checkin_time, checkout_time, base_price, currency, max_guests, standard_guests, extra_adult_price, extra_child_price, extra_hour_price, cleaning_fee, deposit_amount, cleaning_gap_hours

#### RoomWeekdayPriceRepository.php
- Quản lý giá phòng theo ngày trong tuần
- Searchable fields: id, policy_id, weekday, price

#### RoomPriceHistoryRepository.php
- Quản lý lịch sử thay đổi giá
- Searchable fields: id, room_id, policy_snapshot, changed_at

### 6. Special Pricing Repositories

#### RoomDiscountPolicyRepository.php
- Quản lý chính sách giảm giá
- Searchable fields: id, room_id, dayuse_checkin, dayuse_checkout, price_ratio_id, late_checkin, late_checkout, late_discount

#### RoomHourlyPricingRepository.php
- Quản lý giá phòng theo giờ
- Searchable fields: id, room_id, min_hours, min_hours_price, next_hour_price, currency

#### RoomComboPricingRepository.php
- Quản lý giá combo phòng
- Searchable fields: id, room_id, start_time, end_time, mon_price, tue_price, wed_price, thu_price, fri_price, sat_price, sun_price, currency

#### RoomSpecialOfferRepository.php
- Quản lý ưu đãi đặc biệt
- Searchable fields: id, room_id, last_minute_hours, last_minute_discount_percent, monthly_price, currency

## Tính năng chung

### Searchable Fields
Mỗi repository đều có định nghĩa `$fieldSearchable` để hỗ trợ tìm kiếm với các operator:
- `=` : Tìm kiếm chính xác
- `like` : Tìm kiếm gần đúng (cho text)

### Template Support
Tất cả repository đều có template annotations để hỗ trợ IDE và static analysis.

## Usage Examples

```php
// Inject repository via constructor
public function __construct(
    private readonly RoomRepository $roomRepository,
    private readonly RoomTypeRepository $roomTypeRepository
) {}

// Tìm phòng theo ID
$room = $this->roomRepository->find(1);

// Tìm kiếm phòng theo tên
$rooms = $this->roomRepository->findWhere(['name' => 'like', '%Deluxe%']);

// Tạo phòng mới
$room = $this->roomRepository->create([
    'name' => 'Deluxe Room 101',
    'description' => 'Beautiful deluxe room',
    'room_type_id' => 1,
    'capacity' => 2
]);

// Cập nhật phòng
$updatedRoom = $this->roomRepository->update($roomData, $roomId);

// Xóa phòng
$this->roomRepository->delete($roomId);

// Pagination
$rooms = $this->roomRepository->paginate(15);

// Với relationships
$room = $this->roomRepository->with(['roomType', 'images'])->find(1);
```

## Notes
- Tất cả repository extend từ `App\Ship\Parents\Repositories\Repository`
- Có hỗ trợ đầy đủ các method từ AbstractRepository
- Template annotations cho type safety
- Searchable fields đã được định nghĩa cho mỗi repository
