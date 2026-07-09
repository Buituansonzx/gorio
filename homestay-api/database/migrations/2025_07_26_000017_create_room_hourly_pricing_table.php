<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_hourly_pricing: Giá phòng theo giờ
     */
    public function up(): void
    {
        Schema::create('room_hourly_pricing', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho giá phòng theo giờ');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Số giờ tối thiểu (1, 2, 3, 4)
            $table->tinyInteger('min_hours')->comment('Số giờ tối thiểu');

            // Giá cho số giờ tối thiểu
            $table->decimal('min_hours_price', 12, 2)->comment('Giá cho số giờ tối thiểu');

            // Giá cho mỗi giờ tiếp theo (có thể null)
            $table->decimal('next_hour_price', 12, 2)->nullable()->comment('Giá cho mỗi giờ tiếp theo');

            // Loại tiền tệ
            $table->string('currency', 10)->default('VND')->comment('Loại tiền tệ');

            // Trạng thái kích hoạt
            $table->boolean('is_active')->default(false)->comment('Trạng thái kích hoạt');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_hourly_pricing khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_hourly_pricing');
    }
};
