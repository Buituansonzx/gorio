# Database Design Documentation (Detailed)

## Overview

Tài liệu này mô tả chi tiết các bảng và ý nghĩa từng cột trong hệ thống quản lý Homestay. Mỗi bảng sẽ có bảng mô tả (table) cho từng trường, giải thích ý nghĩa và kiểu dữ liệu.

---

## 1. room_types
**Bảng lưu các loại phòng (ví dụ: căn hộ, biệt thự, phòng đơn, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất cho loại phòng |
| code      | VARCHAR(20)  | Mã code loại phòng (ví dụ: APARTMENT, VILLA) |
| name      | JSON         | Tên loại phòng (đa ngôn ngữ) |
| description | JSON, NULL | Mô tả chi tiết loại phòng (đa ngôn ngữ) |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 2. room_access_types
**Bảng lưu thông tin các loại quyền truy cập phòng**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | UUID, PK       | Mã định danh duy nhất                     |
| code        | VARCHAR, UNIQUE| Mã code loại quyền truy cập               |
| name        | JSON           | Tên loại quyền truy cập (đa ngôn ngữ)     |
| description | JSON, NULL     | Mô tả chi tiết (đa ngôn ngữ)              |
| icon_url    | TEXT, NULL     | URL icon đại diện                         |
| created_at  | TIMESTAMP      | Thời gian tạo                             |
| updated_at  | TIMESTAMP      | Thời gian cập nhật                        |

*Chú ý:*
- Có ràng buộc UNIQUE trên cột code
- Hỗ trợ đa ngôn ngữ cho tên và mô tả
- Ví dụ code: PRIVATE, SHARED

## 3. attributes
**Bảng lưu các thuộc tính chung của phòng (ví dụ: hướng, tầng, diện tích, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất cho thuộc tính |
| code      | VARCHAR(20)  | Mã code thuộc tính |
| name      | JSON         | Tên thuộc tính (đa ngôn ngữ) |
| description | JSON, NULL | Mô tả chi tiết thuộc tính (đa ngôn ngữ) |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 4. room_attributes
**Bảng liên kết phòng với các thuộc tính cụ thể và giá trị của từng thuộc tính**
| Tên cột     | Kiểu dữ liệu | Ý nghĩa |
|-------------|--------------|---------|
| id          | BIGINT, PK   | Mã định danh duy nhất |
| room_id     | BIGINT, FK   | Tham chiếu đến phòng |
| attribute_id| BIGINT, FK   | Tham chiếu đến thuộc tính |
| value       | VARCHAR, NULL| Giá trị thuộc tính (nếu có) |
| created_at  | TIMESTAMP    | Thời gian tạo |
| updated_at  | TIMESTAMP    | Thời gian cập nhật |

## 5. surrounding_facilities
**Bảng lưu các tiện ích xung quanh khu vực phòng (ví dụ: siêu thị, trường học, bãi đỗ xe, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất cho tiện ích xung quanh |
| code      | VARCHAR(20)  | Mã code tiện ích |
| name      | JSON         | Tên tiện ích (đa ngôn ngữ) |
| description | JSON, NULL | Mô tả chi tiết tiện ích (đa ngôn ngữ) |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 6. room_surrounding_facilities
**Bảng liên kết phòng với các tiện ích xung quanh và khoảng cách tới đó**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| facility_id| BIGINT, FK  | Tham chiếu đến tiện ích xung quanh |
| distance  | INTEGER, NULL| Khoảng cách (mét) |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 7. room_images
**Bảng lưu ảnh của từng phòng, phân loại ảnh đại diện, ảnh khu vực, ...**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất cho ảnh |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| image_url | TEXT         | Đường dẫn ảnh |
| s3_key    | TEXT, NULL   | Key trong S3 bucket |
| is_cover  | BOOLEAN      | Ảnh đại diện |
| order_index| INTEGER     | Thứ tự hiển thị |
| area_type | VARCHAR, NULL| Loại khu vực (nếu có) |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 8. room_highlight_facilities
**Bảng lưu các tiện ích nổi bật riêng của từng phòng**
| Tên cột     | Kiểu dữ liệu       | Ý nghĩa                                   |
|-------------|-------------------|-------------------------------------------|
| id          | UUID, PK          | Mã định danh duy nhất cho tiện ích        |
| room_id     | UUID, FK          | Khóa ngoại tới bảng rooms                |
| code        | VARCHAR           | Mã code tiện ích (VD: DINING, TV)         |
| name        | TEXT              | Tên tiện ích nổi bật                      |
| description | TEXT, NULL        | Mô tả chi tiết về tiện ích               |
| image_url   | TEXT, NULL        | Ảnh minh họa tiện ích                    |
| service_type| ENUM              | Loại hình dịch vụ (private/shared)       |
| is_free     | BOOLEAN           | Miễn phí (true) hay tính phí (false)     |
| price       | DECIMAL(12,2), NULL| Giá dịch vụ nếu có phí                  |
| unit        | TEXT, NULL        | Đơn vị tính (gói, lần, cái...)          |
| status      | BOOLEAN           | Trạng thái hoạt động của tiện ích        |
| created_at  | TIMESTAMP         | Thời gian tạo                            |
| updated_at  | TIMESTAMP         | Thời gian cập nhật                       |

*Chú ý:*
- Code có thể trùng giữa các phòng khác nhau
- service_type: private (dùng riêng), shared (dùng chung)
- status: true (đang hoạt động), false (ngưng hoạt động)
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng rooms

## 9. room_highlight_facility_images
**Bảng lưu ảnh cho từng tiện ích nổi bật của phòng**
| Tên cột     | Kiểu dữ liệu | Ý nghĩa |
|-------------|--------------|---------|
| id          | UUID, PK     | Mã định danh duy nhất cho ảnh tiện ích nổi bật |
| facility_id | UUID, FK     | ID tiện ích nổi bật |
| image_url   | TEXT         | URL ảnh (S3 hoặc public) |
| s3_key      | TEXT, NULL   | Key trong bucket S3 |
| is_cover    | BOOLEAN      | Ảnh đại diện của tiện ích (mặc định: false) |
| order_index | INTEGER      | Thứ tự hiển thị ảnh (mặc định: 0) |
| created_at  | TIMESTAMP    | Thời gian tạo |
| updated_at  | TIMESTAMP    | Thời gian cập nhật |

*Chú ý:*
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng room_highlight_facilities 
- Chỉ cho phép một ảnh là ảnh đại diện (is_cover = true) cho mỗi tiện ích
- order_index dùng để sắp xếp th�� tự hiển thị các ảnh

## 10. price_ratios
**Bảng lưu các hệ số/tỉ lệ giá (ví dụ: giá cơ bản, giá cuối tuần, giá lễ tết, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất cho tỉ lệ giá |
| code      | VARCHAR(20), UNIQUE | Mã code tỉ lệ giá |
| ratio     | DECIMAL(4,2) | Hệ số giá (ví dụ: 1.0, 1.5) |
| name      | JSON         | Tên tỉ lệ giá (đa ngôn ngữ) |
| description | JSON, NULL | Mô tả chi tiết tỉ lệ giá |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 11. room_pricing_policies
**Bảng lưu chính sách giá cơ bản cho từng phòng (giờ nhận/trả, giá cơ bản, số khách, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| checkin_time | TIME      | Giờ nhận phòng |
| checkout_time| TIME      | Giờ trả phòng |
| base_price| DECIMAL(12,2)| Giá cơ bản |
| currency  | VARCHAR(10)  | Loại tiền tệ |
| max_guests| INTEGER      | Số khách tối đa |
| standard_guests| INTEGER | Số khách tiêu chuẩn |
| extra_adult_price| DECIMAL(12,2), NULL | Giá thêm cho người lớn |
| extra_child_price| DECIMAL(12,2), NULL | Giá thêm cho trẻ em |
| extra_hour_price| DECIMAL(12,2), NULL | Giá thêm giờ |
| cleaning_fee| DECIMAL(12,2), NULL | Phí dọn dẹp |
| deposit_amount| DECIMAL(12,2), NULL | Tiền cọc |
| cleaning_gap_hours| INTEGER, NULL | Giờ dọn dẹp giữa 2 booking |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 12. room_weekday_prices
**Bảng lưu giá phòng theo từng ngày trong tuần cho từng chính sách giá**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| policy_id | BIGINT, FK   | Tham chiếu đến room_pricing_policies |
| weekday   | TINYINT      | Thứ trong tuần (1=Thứ 2, ..., 7=CN) |
| price     | DECIMAL(12,2)| Giá phòng theo ngày |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 13. room_price_history
**Bảng lưu lịch sử thay đổi chính sách giá của từng phòng**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| policy_snapshot| JSON     | Snapshot chính sách giá |
| changed_at| TIMESTAMP    | Thời điểm thay đổi giá |

## 14. room_discount_policies
**Bảng lưu các chính sách giảm giá đặc biệt cho phòng (dayuse, nhận/trả muộn, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| dayuse_checkin| TIME, NULL| Giờ nhận phòng Dayuse |
| dayuse_checkout| TIME, NULL| Giờ trả phòng Dayuse |
| price_ratio_id| BIGINT, FK, NULL | Tham chiếu đến price_ratios |
| late_checkin| TIME, NULL | Giờ nhận phòng muộn |
| late_checkout| TIME, NULL| Giờ trả phòng muộn |
| late_discount| ENUM('0','10',...,'100') | % giảm giá khi nhận/trả phòng muộn |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 15. room_hourly_pricing
**Bảng lưu giá phòng theo giờ (giá cho số giờ tối thiểu, giá mỗi giờ tiếp theo, ...)**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| min_hours | TINYINT      | Số giờ tối thiểu |
| min_hours_price| DECIMAL(12,2)| Giá cho số giờ tối thiểu |
| next_hour_price| DECIMAL(12,2), NULL | Giá cho mỗi giờ tiếp theo |
| currency  | VARCHAR(10)  | Loại tiền tệ |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 16. room_combo_pricing
**Bảng lưu giá combo phòng theo khung giờ và từng ngày trong tuần**
| Tên cột   | Kiểu dữ liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| start_time| TIME         | Giờ bắt đầu combo |
| end_time  | TIME         | Giờ kết thúc combo |
| mon_price | DECIMAL(12,2), NULL | Giá thứ 2 |
| tue_price | DECIMAL(12,2), NULL | Giá thứ 3 |
| wed_price | DECIMAL(12,2), NULL | Giá thứ 4 |
| thu_price | DECIMAL(12,2), NULL | Giá thứ 5 |
| fri_price | DECIMAL(12,2), NULL | Giá thứ 6 |
| sat_price | DECIMAL(12,2), NULL | Giá thứ 7 |
| sun_price | DECIMAL(12,2), NULL | Giá chủ nhật |
| currency  | VARCHAR(10)  | Loại tiền tệ |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 17. room_special_offers
**Bảng lưu các ưu đãi đặc biệt cho phòng (ưu đãi cận ngày, giá theo tháng, ...)**
| Tên cột   | Kiểu d��� liệu | Ý nghĩa |
|-----------|--------------|---------|
| id        | BIGINT, PK   | Mã định danh duy nhất |
| room_id   | BIGINT, FK   | Tham chiếu đến phòng |
| last_minute_hours| INTEGER, NULL | Số giờ trước checkin để áp dụng ưu đãi cận ngày |
| last_minute_discount_percent| DECIMAL(5,2), NULL | % giảm giá c��n ngày |
| monthly_price| DECIMAL(12,2), NULL | Giá đặt theo tháng (>=29 ngày) |
| currency  | VARCHAR(10)  | Loại tiền tệ |
| created_at| TIMESTAMP    | Thời gian tạo |
| updated_at| TIMESTAMP    | Thời gian cập nhật |

## 18. house_rules
**Bảng lưu các quy tắc nhà (ví dụ: không hút thuốc, không mang thú cưng, ...)**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | BIGINT, PK     | Mã định danh duy nhất cho quy tắc         |
| code        | VARCHAR(50)    | Mã code quy tắc                           |
| name        | JSON           | Tên quy tắc (đa ngôn ngữ)                 |
| type        | VARCHAR(50)    | Loại quy tắc (ví dụ: GENERAL, CUSTOM)     |
| is_required | BOOLEAN        | Có bắt buộc hay không                     |

## 19. fixed_check_times
**Bảng lưu các mốc thời gian check-in/check-out cố định**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | BIGINT, PK     | Mã định danh duy nhất cho mốc thời gian   |
| code        | VARCHAR(50)    | Mã code thời gian                         |
| name        | JSON           | Tên mốc thời gian (đa ngôn ngữ)           |
| description | JSON, NULL     | Mô tả chi tiết (đa ngôn ngữ)              |
| is_active   | BOOLEAN        | Có đang hoạt động hay không               |
| created_at  | TIMESTAMP      | Thời gian tạo                             |
| updated_at  | TIMESTAMP      | Thời gian cập nhật                        |

## 20. rooms
**Bảng lưu thông tin các phòng trong hệ thống**
| Tên cột         | Kiểu dữ liệu       | Ý nghĩa                                   |
|-----------------|-------------------|-------------------------------------------|
| id              | UUID, PK          | Mã định danh duy nhất cho phòng           |
| house_id        | UUID, FK          | Khóa ngoại tới bảng houses               |
| district_id     | UUID, FK, NULL    | ID của quận/huyện                        |
| host_id         | UUID, FK          | Khóa ngoại tới bảng hosts                |
| uid             | VARCHAR           | Mã định danh duy nhất của phòng           |
| code            | VARCHAR           | Mã code phòng                             |
| name            | VARCHAR(255)      | Tên phòng (VD: Phòng Deluxe, VIP)        |
| room_type       | VARCHAR(100)      | Loại phòng (Studio, 1PN, 2PN,...)        |
| price_per_night | DECIMAL(10,2)     | Giá thuê theo đêm (VND)                  |
| max_guests      | TINYINT UNSIGNED  | Số lượng khách tối đa                    |
| num_beds        | TINYINT UNSIGNED  | Số lượng giường trong phòng              |
| num_bathrooms   | TINYINT UNSIGNED  | Số lượng phòng tắm (mặc định: 1)        |
| address         | VARCHAR, NULL     | Địa chỉ của phòng                        |
| latitude        | VARCHAR, NULL     | Vĩ độ của phòng                          |
| longitude       | VARCHAR, NULL     | Kinh độ của phòng                        |
| created_at      | TIMESTAMP         | Thời gian tạo                            |
| updated_at      | TIMESTAMP         | Thời gian cập nhật                       |

*Chú ý:*
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng houses, districts và hosts
- Các trường uid và code được thêm để hỗ trợ tìm kiếm và phân loại
- Có index cho các trường host_id và district_id để tối ưu truy vấn

## 21. room_amenities
**Bảng liên kết phòng với các tiện nghi**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| room_id     | UUID, FK       | Khóa ngoại tới bảng rooms                |
| amenity_id  | UUID, FK       | Khóa ngoại tới bảng amenities            |

*Chú ý:* 
- Bảng này sử dụng khoá chính kết hợp (room_id, amenity_id)
- Có ràng buộc khoá ngoại với xoá CASCADE cho cả hai trường

## 22. provinces
**Bảng lưu thông tin các tỉnh/thành phố**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | UUID, PK       | Mã định danh duy nhất của tỉnh/thành phố  |
| code        | VARCHAR, UNIQUE| Mã tỉnh/thành phố                         |
| name        | JSON           | Tên tỉnh/thành phố (đa ngôn ngữ)          |
| created_at  | TIMESTAMP      | Thời gian tạo                             |
| updated_at  | TIMESTAMP      | Thời gian cập nhật                        |

*Chú ý:* 
- Có index trên cột code để tối ưu truy vấn

## 23. hosts
**Bảng lưu thông tin các chủ nhà trong hệ thống**
| Tên cột         | Kiểu dữ liệu       | Ý nghĩa                                   |
|-----------------|-------------------|-------------------------------------------|
| id              | UUID, PK          | Mã định danh duy nhất của chủ nhà         |
| user_id         | BIGINT, FK        | Khóa ngoại tới bảng users                |
| business_name   | VARCHAR(255), NULL| Tên thương hiệu/công ty của chủ nhà      |
| description     | TEXT, NULL        | Mô tả chi tiết về chủ nhà                |
| verified_status | BOOLEAN           | Trạng thái xác minh chủ nhà              |
| address         | TEXT, NULL        | Địa chỉ của chủ nhà                      |
| hotline         | VARCHAR, NULL     | Số điện thoại liên hệ                    |
| created_at      | TIMESTAMP         | Thời gian tạo                            |
| updated_at      | TIMESTAMP         | Thời gian cập nhật                       |

*Chú ý:*
- Có index cho cột user_id và verified_status để tối ưu truy vấn
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng users

## 24. room_views
**Bảng liên kết phòng với các view/hướng nhìn**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| room_id     | UUID, FK       | Khóa ngoại tới bảng rooms                |
| view_id     | UUID, FK       | Khóa ngoại tới bảng views                |

*Chú ý:* 
- Bảng này sử dụng khoá chính kết hợp (room_id, view_id)
- Có ràng buộc khoá ngoại với xoá CASCADE cho cả hai trường

## 25. room_parking_rules
**Bảng lưu quy định gửi xe cho từng phòng**
| Tên cột         | Kiểu dữ liệu       | Ý nghĩa                                   |
|-----------------|-------------------|-------------------------------------------|
| id              | UUID, PK          | Mã định danh duy nhất cho quy định gửi xe  |
| room_id         | UUID, FK          | Khóa ngoại tới bảng rooms                |
| vehicle_type    | ENUM              | Loại xe (motorbike/car)                  |
| is_free         | BOOLEAN           | Gửi xe miễn phí hay có phí               |
| price           | DECIMAL(12,2), NULL| Giá dịch vụ gửi xe                      |
| currency        | VARCHAR(10)       | Đơn vị tiền tệ (mặc định: VND)           |
| location        | ENUM              | Vị trí gửi xe (onsite/offsite)          |
| distance_meters | INTEGER, NULL     | Khoảng cách gửi xe (mét)                |
| description     | TEXT, NULL        | Mô tả chi tiết                          |
| created_at      | TIMESTAMP         | Thời gian tạo                           |
| updated_at      | TIMESTAMP         | Thời gian cập nhật                      |

*Chú ý:*
- Có ràng buộc khóa ngoại v��i xóa CASCADE tới bảng rooms
- Enum vehicle_type: motorbike, car
- Enum location: onsite (tại cơ sở), offsite (bên ngoài)

## 26. houses
**Bảng lưu thông tin các căn nhà trong hệ thống homestay**
| Tên cột         | Kiểu dữ liệu       | Ý nghĩa                                   |
|-----------------|-------------------|-------------------------------------------|
| id              | UUID, PK          | Mã định danh duy nhất của căn nhà         |
| host_id         | UUID, FK          | Khóa ngoại tới b��ng hosts                |
| name            | VARCHAR(255)      | Tên căn nhà hoặc tiêu đề mô tả ngắn gọn  |
| address         | VARCHAR(500)      | Địa chỉ đầy đủ của căn nhà               |
| latitude        | DECIMAL(10,8), NULL| Vĩ độ - Tọa độ địa lý cho định vị        |
| longitude       | DECIMAL(11,8), NULL| Kinh độ - Tọa độ địa lý cho định vị      |
| description     | LONGTEXT, NULL    | Mô tả chi tiết về căn nhà                |
| created_at      | TIMESTAMP         | Thời gian tạo                            |
| updated_at      | TIMESTAMP         | Thời gian cập nhật                       |

*Chú ý:*
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng hosts
- Địa chỉ lưu đầy đủ: số nhà, đường, phường, quận, thành phố
- Mô tả chi tiết bao gồm: thông tin về tiện nghi, quy định, đặc điểm nổi bật

## 27. checkin_methods
**Bảng lưu các phương thức check-in**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | UUID, PK       | Mã định danh duy nhất của phương thức     |
| code        | VARCHAR, UNIQUE| Mã phương thức check-in                   |
| name        | JSON           | Tên phương thức check-in (đa ngôn ngữ)    |
| description | JSON, NULL     | Mô tả chi tiết (đa ngôn ngữ)              |
| created_at  | TIMESTAMP      | Thời gian tạo                             |
| updated_at  | TIMESTAMP      | Thời gian cập nhật                        |

*Chú ý:*
- Có ràng buộc UNIQUE trên cột code
- Hỗ trợ đa ngôn ngữ cho tên và mô tả

## 28. amenities
**Bảng lưu thông tin các tiện nghi của phòng**
| Tên cột     | Kiểu dữ liệu   | Ý nghĩa                                   |
|-------------|----------------|-------------------------------------------|
| id          | UUID, PK       | Mã định danh duy nhất của tiện nghi       |
| code        | VARCHAR, UNIQUE| Mã code duy nhất cho tiện nghi            |
| name        | JSON           | Tên tiện nghi (đa ngôn ngữ)               |
| type        | VARCHAR        | Loại tiện nghi (ví dụ: basic, extra)     |
| Is_basic    | BOOLEAN        | Có phải tiện ích cơ bản không             |
| created_at  | TIMESTAMP      | Thời gian tạo                            |
| updated_at  | TIMESTAMP      | Thời gian cập nhật                       |

*Chú ý:*
- Có ràng buộc UNIQUE trên cột code
- Hỗ trợ đa ngôn ngữ cho tên tiện nghi
- Phân loại tiện ích cơ bản và bổ sung

## 29. room_policy
**Bảng lưu thông tin các chính sách của từng phòng**
| Tên cột      | Kiểu dữ liệu     | Ý nghĩa                                   |
|--------------|------------------|-------------------------------------------|
| id           | UUID, PK         | Mã định danh duy nhất của chính sách      |
| room_id      | UUID, FK         | ID của phòng có chính sách này            |
| policy_type  | VARCHAR(50)      | Loại chính sách (cancellation, house_rules, check_in, ...) |
| content      | LONGTEXT         | Nội dung chi tiết của chính sách          |
| created_at   | TIMESTAMP        | Thời gian tạo                             |
| updated_at   | TIMESTAMP        | Thời gian cập nhật                        |

*Chú ý:*
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng rooms
- Có index trên cột room_id và policy_type để tối ưu truy vấn
- Có ràng buộc unique trên cặp (room_id, policy_type) để tránh trùng lặp
- Các loại policy_type: cancellation, house_rules, check_in, check_out, payment, safety

## 30. room_highlight_amenities
**Bảng lưu các tiện ích nổi bật của từng phòng**
| Tên cột         | Kiểu dữ liệu       | Ý nghĩa                                   |
|-----------------|-------------------|-------------------------------------------|
| id              | UUID, PK          | Mã định danh duy nhất                     |
| room_id         | UUID, FK          | Khóa ngoại tới bảng rooms                |
| amenity_id      | UUID, FK          | Khóa ngoại tới bảng amenities            |
| is_highlighted  | BOOLEAN           | Có phải tiện ích nổi bật                 |
| created_at      | TIMESTAMP         | Thời gian tạo                            |
| updated_at      | TIMESTAMP         | Thời gian cập nhật                       |

*Chú ý:*
- Có ràng buộc khóa ngoại với xóa CASCADE tới bảng rooms và amenities
- Bảng này thay thế một phần chức năng của room_highlight_facilities

## 31. orders
**Bảng lưu thông tin các đơn đặt phòng trong hệ thống**
| Tên cột          | Kiểu dữ liệu       | Ý nghĩa                                   |
|------------------|-------------------|-------------------------------------------|
| id               | UUID, PK          | Mã định danh duy nhất của đơn hàng        |
| code             | VARCHAR, UNIQUE   | Mã đơn hàng, duy nhất cho mỗi đơn         |
| user_id          | UUID, FK          | ID của người đặt hàng                     |
| room_id          | UUID, FK          | ID của phòng được đặt                     |
| check_in         | DATETIME          | Thời gian nhận phòng                      |
| check_out        | DATETIME          | Thời gian trả phòng                       |
| number_of_guests | UNSIGNED INT      | Số lượng khách đặt phòng (mặc định: 1)    |
| guest_name       | VARCHAR(255), NULL| Tên khách hàng đặt phòng                  |
| guest_phone      | VARCHAR(255), NULL| Số điện thoại của khách hàng             |
| price            | UNSIGNED INT      | Giá phòng (VND)                           |
| note             | VARCHAR, NULL     | Ghi chú của khách hàng về đơn hàng        |
| status           | VARCHAR(255)     | Trạng thái đơn hàng                       |
| created_at       | TIMESTAMP         | Thời gian tạo đơn                         |
| updated_at       | TIMESTAMP         | Thời gian cập nhật đơn                    |

*Chú ý:*
- Có ràng buộc khóa ngoại với bảng users và rooms
- Trạng thái đơn hàng lưu dưới dạng chuỗi để dễ mở rộng các trạng thái mới
- Có index trên các trường:
  + code (UNIQUE) - để tìm kiếm đơn hàng nhanh chóng
  + user_id - để lọc đơn hàng theo người dùng
  + room_id - để lọc đơn hàng theo phòng
- Các trường guest_name và guest_phone:
  + Được đổi tên từ customer_name và customer_phone
  + Có thể để NULL (không bắt buộc)
- Giá phòng (price) được lưu bằng đơn vị VND, số nguyên không âm
- Số lượng khách (number_of_guests) mặc định là 1 nếu không được chỉ định

---

## Relationships
- Hầu hết các bảng đều tham chiếu đến bảng `rooms` qua trường `room_id`.
- Các bảng liên kết nhiều-nhiều: `room_attributes`, `room_surrounding_facilities`, `room_highlight_facility_images`.
- Các bảng giá và chính sách liên kết với phòng và nhau qua khóa ngoại.

---

## Notes
- Tất cả các bảng sử dụng InnoDB và hỗ trợ ràng buộc khóa ngoại.
- Các trường đa ngôn ngữ sử dụng JSON.
- Có trường thời gian tạo/cập nhật để tracking.
- Các trường tiền tệ dùng decimal để đảm bảo chính xác.

---

## Last Updated
- 2025-08-13
