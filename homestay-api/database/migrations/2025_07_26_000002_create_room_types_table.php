<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_types: Lưu thông tin các loại phòng
     */
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho loại phòng');

            // Mã code duy nhất cho loại phòng (ví dụ: DELUXE, STANDARD)
            $table->string('code')->unique()->comment('Mã code duy nhất cho loại phòng');

            // Tên loại phòng (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên loại phòng (đa ngôn ngữ)');

            // Mô tả loại phòng (đa ngôn ngữ, dạng json, có thể null)
            $table->json('description')->nullable()->comment('Mô tả loại phòng (đa ngôn ngữ)');

            // Đường dẫn icon (có thể null)
            $table->text('icon_url')->nullable()->comment('URL icon đại diện cho loại phòng');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_types khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
