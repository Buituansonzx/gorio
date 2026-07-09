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
        // Tạo bảng fixed_check_times: Khung giờ cố định (check-in/check-out)
        Schema::create('fixed_check_times', function (Blueprint $table) {
            // Primary key UUID - ID duy nhất cho khung giờ
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho khung giờ (UUID)');

            // Mã code duy nhất cho khung giờ (ví dụ: CHECKIN_MORNING)
            $table->string('code')->unique()->comment('Mã code duy nhất cho khung giờ');

            // Tên khung giờ (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên khung giờ (đa ngôn ngữ)');

            // Mô tả chi tiết về khung giờ (đa ngôn ngữ, dạng json, có thể null)
            $table->json('description')->nullable()->comment('Mô tả chi tiết về khung giờ (đa ngôn ngữ)');

            // Trạng thái hoạt động của khung giờ
            $table->boolean('is_active')->default(true)->comment('Khung giờ này có đang được sử dụng không');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_check_times');
    }
};
