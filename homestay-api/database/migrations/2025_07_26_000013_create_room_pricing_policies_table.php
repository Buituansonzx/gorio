<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_pricing_policies: Chính sách giá cho từng phòng
     */
    public function up(): void
    {
        Schema::create('room_pricing_policies', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho chính sách giá');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Giờ nhận/trả phòng
            $table->time('checkin_time')->nullable()->comment('Giờ nhận phòng');
            $table->time('checkout_time')->nullable()->comment('Giờ trả phòng');

            // Giá cơ bản và loại tiền tệ
            $table->decimal('base_price', 12, 2)->nullable()->comment('Mức giá cơ bản');
            $table->string('currency', 10)->nullable()->default('VND')->comment('Loại tiền tệ');

            // Số khách tối đa và tiêu chuẩn
            $table->integer('max_guests')->nullable()->comment('Số khách tối đa');
            $table->integer('standard_guests')->nullable()->comment('Số khách tiêu chuẩn');

            // Giá thêm cho người lớn/trẻ em
            $table->decimal('extra_adult_price', 12, 2)->nullable()->comment('Giá thêm cho 1 người lớn');
            $table->decimal('extra_child_price', 12, 2)->nullable()->comment('Giá thêm cho 1 trẻ em (2-12 tuổi)');

            // Giá thêm giờ, phí dọn dẹp, tiền cọc, giờ dọn dẹp giữa 2 booking
            $table->decimal('extra_hour_price', 12, 2)->nullable()->comment('Giá thêm giờ');
            $table->decimal('cleaning_fee', 12, 2)->nullable()->comment('Phí dọn dẹp');
            $table->decimal('deposit_amount', 12, 2)->nullable()->comment('Cọc cơ sở vật chất');
            $table->integer('cleaning_gap_hours')->nullable()->comment('Giờ dọn dẹp giữa 2 booking');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_pricing_policies khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_pricing_policies');
    }
};
