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
        Schema::create('checkout_instructions_types', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho loại hướng dẫn checkout');

            // Mã code duy nhất cho loại hướng dẫn checkout (ví dụ: RETURN_KEY, CLEAN_ROOM)
            $table->string('code')->unique()->comment('Mã code duy nhất cho loại hướng dẫn checkout');

            // Tên loại hướng dẫn checkout (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên loại hướng dẫn checkout (đa ngôn ngữ)');

            // Trạng thái hoạt động của loại hướng dẫn checkout
            $table->boolean('is_active')->default(true)->comment('Loại hướng dẫn này có đang được sử dụng không');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_instructions_types');
    }
};
