<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('amenities', static function (Blueprint $table) {
            // Primary key UUID - ID duy nhất cho tiện ích
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho tiện ích (UUID)');

            // Mã code duy nhất cho tiện ích (ví dụ: WIFI, PARKING)
            $table->string('code')->unique()->comment('Mã code duy nhất cho tiện ích');

            // Tên tiện ích (đa ngôn ngữ, dạng json)
            $table->json('name')->comment('Tên tiện ích (đa ngôn ngữ)');
            // Thêm cột icon sau name
            $table->string('icon')->nullable()->after('name')->comment('Biểu tượng của tiện ích');

            // Loại tiện ích (ví dụ: basic, extra)
            $table->uuid('amenity_group_id')->comment('ID của nhóm tiện ích (UUID)');
            $table->foreign('amenity_group_id')
                ->references('id')
                ->on('amenity_group')
                ->onDelete('cascade')
                ->comment('Khóa ngoại tới bảng amenity_group - ID của nhóm tiện ích');

            // Có phải tiện ích cơ bản không
            $table->boolean('is_basic')->comment('Tiện ích cơ bản hay không');

            // Thời gian tạo/cập nhật
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
