<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng rooms để lưu thông tin các phòng trong mỗi căn nhà homestay
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của phòng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của phòng (UUID)');

            // UID riêng biệt cho phòng (nếu có)
            $table->string('uid', 36)->after('id');
            // Mã định danh duy nhất của phòng (có thể dùng cho mã QR, v.v.)
            $table->string('code')->after('uid')->nullable()->comment('Mã định danh duy nhất của phòng');

            // Foreign key UUID - Liên kết với bảng houses để xác định căn nhà chứa phòng này
            $table->uuid('house_id')
                  ->nullable()
                  ->comment('Khóa ngoại tới bảng houses - ID của căn nhà chứa phòng này (UUID)');
            $table->foreign('house_id')->references('id')->on('houses')->onDelete('cascade');

            // Thêm host_id (nullable, sau house_id, foreign key tới hosts, index)
            $table->uuid('host_id')->nullable()->after('house_id')->comment('Khóa ngoại tới bảng hosts - ID của chủ nhà sở hữu phòng này (UUID)');
            $table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
            $table->index('host_id', 'idx_rooms_host_id');

            // Foreign key UUID - Liên kết với bảng districts (quận/huyện)
            $table->uuid('district_id')->nullable()->after('house_id')->comment('ID của quận/huyện (UUID)');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');

            // Tên phòng - Tên gọi hoặc mô tả ngắn gọn của phòng
            $table->string('name', 255)
                  ->comment('Tên phòng hoặc mô tả ngắn gọn (VD: Phòng Deluxe, Phòng VIP)');
            // Thêm title (nullable, sau name)
            $table->string('title')->nullable()->after('name');

            // Loại phòng - Phân loại phòng theo kiểu thiết kế hoặc số phòng ngủ
            $table->string('room_type', 100)
                  ->nullable()
                  ->comment('Loại phòng (VD: Studio, 1 phòng ngủ, 2 phòng ngủ, Penthouse)');

            // room_type_id - Liên kết với bảng room_types
            $table->uuid('room_type_id')->nullable()->after('room_type');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade')->comment('Khóa ngoại tới bảng room_types - ID của loại phòng (UUID)');

            // access_type_id - Liên kết với bảng room_access_types
            $table->uuid('access_type_id')->nullable()->after('room_type_id')->comment('ID của loại truy cập phòng (UUID)');
            $table->foreign('access_type_id')->references('id')->on('room_access_types')->onDelete('cascade')->comment('Khóa ngoại tới bảng room_access_types - ID của loại truy cập phòng (UUID)');

            // Địa chỉ, vĩ độ, kinh độ của phòng
            $table->string('address')->nullable()->after('room_type')->comment('Địa chỉ của phòng');
            $table->string('latitude')->nullable()->after('address')->comment('Vĩ độ của phòng');
            $table->string('longitude')->nullable()->after('latitude')->comment('Kinh độ của phòng');

            // Giá thuê theo đêm - Mức giá cơ bản cho một đêm
            $table->decimal('price_per_night', 10, 2)
                  ->comment('Giá thuê theo đêm (VND) - Mức giá cơ bản cho một đêm lưu trú');

            // Số lượng khách tối đa - Giới hạn số người có thể ở trong phòng
            $table->unsignedTinyInteger('max_guests')
                  ->comment('Số lượng khách tối đa có thể lưu trú trong phòng');

            // Số giường - Tổng số giường trong phòng
            $table->unsignedTinyInteger('num_beds')
                  ->comment('Số lượng giường trong phòng');

            // Số phòng tắm - Số lượng phòng tắm/toilet trong phòng
            $table->unsignedTinyInteger('num_bathrooms')
                  ->default(1)
                  ->comment('Số lượng phòng tắm/toilet trong phòng');

            // Diện tích phòng - Diện tích tính bằng mét vuông
            $table->decimal('area_sqm', 6, 2)
                  ->nullable()
                  ->comment('Diện tích phòng tính bằng mét vuông (m²)');

            // Mô tả chi tiết - Thông tin chi tiết về phòng, tiện nghi, nội thất
            $table->longText('description')
                  ->nullable()
                  ->comment('Mô tả chi tiết về phòng, tiện nghi, nội thất, view, đặc điểm nổi bật');

            // Trạng thái hiển thị - Xác định phòng có được hiển thị cho khách đặt hay không
            $table->boolean('is_active')
                  ->default(true)
                  ->comment('Trạng thái hiển thị phòng (true: hiển thị, false: ẩn khỏi danh sách)');
            // Thêm status (integer, default 1, sau is_active)
            $table->integer('status')->default(1)->after('is_active');

            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();

            // Index cho tối ưu truy vấn
            $table->index('house_id', 'idx_rooms_house_id');
            $table->index('room_type', 'idx_rooms_type');
            $table->index('is_active', 'idx_rooms_active');
            $table->index('price_per_night', 'idx_rooms_price');
            $table->index(['max_guests', 'is_active'], 'idx_rooms_guests_active');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng rooms khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
