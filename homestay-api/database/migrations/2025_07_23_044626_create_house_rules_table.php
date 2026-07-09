<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        // Tạo bảng house_rules: Quy định nhà
        Schema::create('house_rules', static function (Blueprint $table) {
            // Primary key UUID - ID duy nhất
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho rule (UUID)');

            // Mã rule duy nhất
            $table->string('code')->unique()->comment('Mã rule duy nhất');

            // Tên rule đa ngôn ngữ (json)
            $table->json('name')->comment('Tên rule (đa ngôn ngữ)');

            // Loại rule (mặc định là default)
            $table->string('type')->default('default')->comment('Loại rule, mặc định là default');

            // Có bắt buộc không
            $table->boolean('is_required')->default(false)->comment('Rule này có bắt buộc không');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_rules');
    }
};
