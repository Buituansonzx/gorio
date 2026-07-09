<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_combo_pricing: Giá phòng combo theo khung giờ và từng ngày trong tuần
     */
    public function up(): void
    {
        Schema::create('room_combo_pricing', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho giá combo phòng');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Giờ bắt đầu và kết thúc combo
            $table->time('start_time')->nullable()->comment('Giờ bắt đầu combo');
            $table->time('end_time')->nullable()->comment('Giờ kết thúc combo');
            $table->unsignedInteger('price')->default(0)->after('end_time')->comment('Price of the combo');

            // Giá theo từng ngày trong tuần (có thể null)
            $table->decimal('mon_price', 12, 2)->nullable()->comment('Giá thứ 2');
            $table->decimal('tue_price', 12, 2)->nullable()->comment('Giá thứ 3');
            $table->decimal('wed_price', 12, 2)->nullable()->comment('Giá thứ 4');
            $table->decimal('thu_price', 12, 2)->nullable()->comment('Giá thứ 5');
            $table->decimal('fri_price', 12, 2)->nullable()->comment('Giá thứ 6');
            $table->decimal('sat_price', 12, 2)->nullable()->comment('Giá thứ 7');
            $table->decimal('sun_price', 12, 2)->nullable()->comment('Giá chủ nhật');

            // Loại tiền tệ
            $table->string('currency', 10)->default('VND')->comment('Loại tiền tệ');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_combo_pricing khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_combo_pricing');
    }
};
