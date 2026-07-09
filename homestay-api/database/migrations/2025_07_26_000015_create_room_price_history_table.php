<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_price_history: Lưu lịch sử thay đổi giá phòng
     */
    public function up(): void
    {
        Schema::create('room_price_history', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho lịch sử giá phòng');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Snapshot chính sách giá (dạng JSON)
            $table->json('policy_snapshot')->comment('Snapshot chính sách giá');

            // Thời điểm thay đổi giá
            $table->timestamp('changed_at')->useCurrent()->comment('Thời điểm thay đổi giá');

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_price_history khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_price_history');
    }
};
