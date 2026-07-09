<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_images để lưu thông tin hình ảnh của các phòng với AWS S3 storage
     */
    public function up(): void
    {
        Schema::create('room_images', function (Blueprint $table) {
            // Primary key UUID - ID duy nhất
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho ảnh (UUID)');

            // Foreign key UUID - Liên kết với bảng rooms
            $table->uuid('room_id')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng chứa ảnh này (UUID)');

            // Tạo foreign key constraint
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            $table->uuid('room_image_area_group_id');
            // Foreign key liên kết với bảng room_image_area_group
            $table->foreign('room_image_area_group_id')->references('id')->on('room_image_area_group')->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng room_image_area_group - ID của nhóm khu vực ảnh (VD: living_room, bedroom, bathroom, kitchen, balcony, exterior)');
            // AWS S3 storage information
            $table->text('s3_key')
                  ->nullable()
                  ->comment('Đường dẫn nội bộ trong S3 bucket (VD: rooms/abc123/image01.jpg)');

            $table->text('image_url')
                  ->comment('URL truy cập ảnh công khai hoặc pre-signed URL từ S3');

            // Thêm cột file_path sau image_url
            $table->string('file_path')->nullable()->after('image_url')->comment('Path to the image file');

            // File metadata
            $table->text('file_name')
                  ->nullable()
                  ->comment('Tên file gốc khi người dùng upload (VD: bedroom_photo.jpg)');

            $table->bigInteger('file_size')
                  ->unsigned()
                  ->nullable()
                  ->comment('Kích thước file tính bằng byte (VD: 1024567)');

            $table->string('content_type', 100)
                  ->nullable()
                  ->comment('MIME type của ảnh (VD: image/jpeg, image/png, image/webp)');

            // Image dimensions - optional
            $table->unsignedInteger('width')
                  ->nullable()
                  ->comment('Chiều rộng của ảnh tính bằng pixel');

            $table->unsignedInteger('height')
                  ->nullable()
                  ->comment('Chiều cao của ảnh tính bằng pixel');

            // Display settings
            $table->boolean('is_cover')
                  ->default(false)
                  ->comment('Ảnh đại diện chính của phòng (true: ảnh cover, false: ảnh phụ)');

            $table->unsignedInteger('order_index')
                  ->default(0)
                  ->comment('Thứ tự hiển thị của ảnh (0: đầu tiên, 1, 2, 3...)');

            // SEO and accessibility
            $table->text('alt_text')
                  ->nullable()
                  ->comment('Văn bản thay thế mô tả ảnh cho SEO và hỗ trợ truy cập');

            // Timestamps - thời gian tạo và cập nhật
            $table->timestamps();

            // Indexes cho tối ưu truy vấn
            $table->index('room_id', 'idx_room_images_room_id');
            $table->index('is_cover', 'idx_room_images_cover');
            $table->index('order_index', 'idx_room_images_order');
            $table->index(['room_id', 'is_cover'], 'idx_room_images_room_cover');
            $table->index(['room_id', 'order_index'], 'idx_room_images_room_order');
            $table->index('content_type', 'idx_room_images_content_type');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_images khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_images');
    }
};
