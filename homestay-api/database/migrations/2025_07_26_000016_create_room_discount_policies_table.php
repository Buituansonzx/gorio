<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_discount_policies: Chính sách giảm giá cho phòng
     */
    public function up(): void
    {
        Schema::create('room_discount_policies', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho chính sách giảm giá');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Giờ nhận/trả phòng cho Dayuse (có thể null)
            $table->time('dayuse_checkin')->nullable()->comment('Giờ nhận phòng Dayuse');
            $table->time('dayuse_checkout')->nullable()->comment('Giờ trả phòng Dayuse');

            // Khóa ngoại tới price_ratios (có thể null)
            $table->uuid('price_ratio_id')->nullable()->comment('ID tỉ lệ giá áp dụng');

            // Giờ nhận/trả phòng muộn (có thể null)
            $table->time('late_checkin')->nullable()->comment('Giờ nhận phòng muộn');
            $table->time('late_checkout')->nullable()->comment('Giờ trả phòng muộn');

            // Mức giảm giá khi nhận/trả phòng muộn
            $table->enum('late_discount', ['0','10','20','30','40','50','60','70','80','90','100'])->default('0')->comment('Phần trăm giảm giá khi nhận/trả phòng muộn');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('price_ratio_id')->references('id')->on('price_ratios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_discount_policies khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_discount_policies');
    }
};
