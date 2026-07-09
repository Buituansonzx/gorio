<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng surrounding_facilities: Lưu thông tin các tiện ích xung quanh (vd: sân vườn, công viên...)
     */
    public function up(): void
    {
        Schema::create('surrounding_facilities', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho tiện ích xung quanh');


            // Mã code duy nhất cho tiện ích xung quanh (ví dụ: GARDEN, PARK)
            $table->string('code')->unique()->comment('Mã code duy nhất cho tiện ích xung quanh');

            // Tên tiện ích xung quanh (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên tiện ích xung quanh (đa ngôn ngữ)');

            // Đường dẫn icon (có thể null)
            $table->text('icon_url')->nullable()->comment('URL icon đại diện cho tiện ích xung quanh');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng surrounding_facilities khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('surrounding_facilities');
    }
};
