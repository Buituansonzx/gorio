<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_weekday_prices: Giá phòng theo từng ngày trong tuần
     */
    public function up(): void
    {
        Schema::create('room_weekday_prices', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho giá phòng theo ngày trong tuần');

            // Khóa ngoại tới room_pricing_policies
            $table->uuid('policy_id')->comment('ID chính sách giá phòng');

            // Thứ trong tuần (1 = Thứ 2, ..., 7 = Chủ nhật)
            $table->tinyInteger('weekday')->comment('Thứ trong tuần (1 = Thứ 2, ..., 7 = Chủ nhật)');

            // Giá phòng
            $table->decimal('price', 12, 2)->comment('Giá phòng theo ngày trong tuần');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('policy_id')->references('id')->on('room_pricing_policies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_weekday_prices khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_weekday_prices');
    }
};
