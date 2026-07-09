<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng views: Lưu thông tin các loại view (hướng nhìn, cảnh quan, v.v.)
     */
    public function up(): void
    {
        Schema::create('views', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho view');

            // Mã code duy nhất cho view (ví dụ: SEA_VIEW, CITY_VIEW)
            $table->string('code')->unique()->comment('Mã code duy nhất cho view');

            // Tên view (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên view (đa ngôn ngữ)');

            // Mô tả view (đa ngôn ngữ, dạng json, có thể null)
            $table->json('description')->nullable()->comment('Mô tả view (đa ngôn ngữ)');

            // Đường dẫn icon (có thể null)
            $table->text('icon_url')->nullable()->comment('URL icon đại diện cho view');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng views khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
