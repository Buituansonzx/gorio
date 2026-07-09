<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_facilities để lưu thông tin các tiện nghi của từng phòng
     */
    public function up(): void
    {
        Schema::create('room_facilities', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của tiện nghi
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của tiện nghi phòng (UUID)');
            
            // Foreign key UUID - Liên kết với bảng rooms để xác định phòng có tiện nghi này
            $table->uuid('room_id')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng có tiện nghi này (UUID)');
            
            // Tạo foreign key constraint
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            
            // Tên tiện nghi - Tên gọi của tiện nghi
            $table->string('facility_name', 100)
                  ->comment('Tên tiện nghi (VD: WiFi miễn phí, Máy lạnh, Bếp nấu ăn, Máy giặt)');
            
            // Icon tiện nghi - Mã icon hoặc đường dẫn icon để hiển thị
            $table->string('facility_icon', 255)
                  ->nullable()
                  ->comment('Mã icon hoặc đường dẫn file icon để hiển thị tiện nghi (VD: fas fa-wifi, /icons/wifi.svg)');
            
            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();
            
            // Index cho tối ưu truy vấn
            $table->index('room_id', 'idx_room_facilities_room_id');
            $table->index('facility_name', 'idx_room_facilities_name');
            
            // Unique constraint để tránh trùng lặp tiện nghi trong cùng một phòng
            $table->unique(['room_id', 'facility_name'], 'unique_room_facility');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_facilities khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_facilities');
    }
};
