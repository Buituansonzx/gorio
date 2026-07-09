<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng attributes: Lưu thông tin thuộc tính (vd: phòng ngủ, phòng tắm...)
     */
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho thuộc tính');

            // Tên thuộc tính (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên thuộc tính (đa ngôn ngữ)');

            // Đường dẫn icon (có thể null)
            $table->text('icon_url')->nullable()->comment('URL icon đại diện cho thuộc tính');


            // Mã code duy nhất cho thuộc tính (ví dụ: BEDROOM, BATHROOM)
            $table->string('code')->unique()->comment('Mã code duy nhất cho thuộc tính');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng attributes khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
