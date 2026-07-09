<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_parking_rules: Quy định gửi xe cho từng phòng
     */
    public function up(): void
    {
        Schema::create('room_parking_rules', function (Blueprint $table) {
            // Primary key - ID tự động tăng
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất (UUID) cho quy định gửi xe');

            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Loại xe: motorbike/car
            $table->enum('vehicle_type', ['motorbike', 'car'])->comment('Loại xe: xe máy hoặc ô tô');

            // Gửi xe miễn phí hay có phí
            $table->boolean('is_free')->default(false)->comment('Gửi xe miễn phí hay có phí');

            // Giá dịch vụ (có thể null nếu miễn phí)
            $table->decimal('price', 12, 2)->nullable()->comment('Giá dịch vụ gửi xe');

            // Đơn vị tiền tệ
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');

            // Vị trí gửi xe: onsite/offsite
            $table->enum('location', ['onsite', 'offsite'])->comment('Vị trí gửi xe: tại cơ sở hay bên ngoài');

            // Khoảng cách gửi xe (m)
            $table->integer('distance_meters')->nullable()->comment('Khoảng cách gửi xe (m)');

            // Mô tả chi tiết
            $table->text('description')->nullable()->comment('Mô tả chi tiết');

            // Thời gian tạo/cập nhật
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_parking_rules khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_parking_rules');
    }
};
