<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_highlight_amenities', function (Blueprint $table) {
            // Primary key UUID
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho tiện ích nổi bật (UUID)');

            // Khóa ngoại tới rooms (UUID)
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Khóa ngoại tới amenities (UUID)
            $table->uuid('amenity_id')->comment('ID tiện ích (UUID)');

            // Ảnh minh họa tiện ích
            $table->text('image_url')->nullable()->comment('Ảnh minh họa tiện ích');

            // Loại hình dịch vụ: Riêng / Chung
            $table->enum('service_type', ['private', 'shared'])->comment('Loại hình dịch vụ: Riêng/Chung');

            // Miễn phí hay tính phí
            $table->boolean('is_free')->default(true)->comment('Miễn phí hay tính phí');

            // Giá (nếu có phí)
            $table->decimal('price', 12, 2)->nullable()->comment('Giá dịch vụ (nếu có phí)');

            // Đơn vị (gói, lần, cái...)
            $table->text('unit')->nullable()->comment('Đơn vị tính');

            // Trạng thái hoạt động
            $table->boolean('status')->default(true)->comment('Trạng thái hoạt động');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_highlight_amenities');
    }
};
