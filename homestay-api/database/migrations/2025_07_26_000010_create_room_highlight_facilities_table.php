<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_highlight_facilities: Tiện ích nổi bật của phòng
     */
    public function up(): void
    {
        Schema::create('room_highlight_facilities', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho tiện ích nổi bật');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');


            // Mã code duy nhất cho tiện ích nổi bật (ví dụ: DINING, PROJECTOR, TV, ...)
            $table->string('code')->comment('Mã code cho tiện ích nổi bật (có thể trùng giữa các phòng)');

            // Tên tiện ích
            $table->text('name')->comment('Tên tiện ích nổi bật');

            // Mô tả tiện ích
            $table->text('description')->nullable()->comment('Mô tả tiện ích');

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
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_highlight_facilities khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_highlight_facilities');
    }
};
