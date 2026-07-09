<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_highlight_facility_images: Ảnh cho tiện ích nổi bật của phòng
     */
    public function up(): void
    {
        Schema::create('room_highlight_facility_images', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho ảnh tiện ích nổi bật');

            // Khóa ngoại tới room_highlight_facilities
            $table->uuid('facility_id')->comment('ID tiện ích nổi bật (UUID)');

            // URL ảnh (bắt buộc)
            $table->text('image_url')->comment('URL ảnh (S3 hoặc public)');

            // S3 key (có thể null)
            $table->text('s3_key')->nullable()->comment('Key trong bucket S3');

            // Ảnh đại diện của tiện ích
            $table->boolean('is_cover')->default(false)->comment('Ảnh đại diện của tiện ích');

            // Thứ tự hiển thị ảnh
            $table->integer('order_index')->default(0)->comment('Thứ tự hiển thị ảnh');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('facility_id')->references('id')->on('room_highlight_facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_highlight_facility_images khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_highlight_facility_images');
    }
};
