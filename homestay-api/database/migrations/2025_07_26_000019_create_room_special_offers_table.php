<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_special_offers: Ưu đãi đặc biệt cho phòng
     */
    public function up(): void
    {
        Schema::create('room_special_offers', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho ưu đãi đặc biệt');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Ưu đãi cận ngày
            $table->integer('last_minute_hours')->nullable()->comment('Số giờ trước checkin để áp dụng ưu đãi cận ngày');
            $table->decimal('last_minute_discount_percent', 5, 2)->nullable()->comment('% giảm giá cận ngày');

            // Ưu đãi đặt theo tháng
            $table->decimal('monthly_price', 12, 2)->nullable()->comment('Giá đặt theo tháng (>=29 ngày)');

            // Loại tiền tệ
            $table->string('currency', 10)->default('VND')->comment('Loại tiền tệ');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_special_offers khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_special_offers');
    }
};
