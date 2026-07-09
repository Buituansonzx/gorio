<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng price_ratios: Tỉ lệ giá (hệ số giá theo từng loại)
     */
    public function up(): void
    {
        Schema::create('price_ratios', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho tỉ lệ giá');

            // Mã code duy nhất cho tỉ lệ giá (BASE, RATIO_1_5, RATIO_2, ...)
            $table->string('code', 20)->unique()->comment('Mã code cho tỉ lệ giá');

            // Hệ số giá
            $table->decimal('ratio', 4, 2)->comment('Hệ số giá (ví dụ: 1.0, 1.5, 2.0)');

            // Tên tỉ lệ giá (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên tỉ lệ giá (đa ngôn ngữ)');

            // Mô tả chi tiết (đa ngôn ngữ, có thể null)
            $table->json('description')->nullable()->comment('Mô tả chi tiết (đa ngôn ngữ)');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng price_ratios khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('price_ratios');
    }
};
