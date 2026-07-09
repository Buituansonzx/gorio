<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng houses để lưu thông tin các căn nhà trong hệ thống homestay
     */
    public function up(): void
    {
        Schema::create('houses', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của căn nhà
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của căn nhà (UUID)');
            
            // Foreign key UUID - Liên kết với bảng hosts để xác định chủ nhà sở hữu căn nhà này
            $table->uuid('host_id')
                  ->comment('Khóa ngoại tới bảng hosts - ID của chủ nhà sở hữu căn nhà (UUID)');
            
            // Tạo foreign key constraint
            $table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
            
            // Tên căn nhà - Tên gọi hoặc tiêu đề của căn nhà
            $table->string('name', 255)
                  ->comment('Tên căn nhà hoặc tiêu đề mô tả ngắn gọn');
            
            // Địa chỉ đầy đủ - Địa chỉ chi tiết của căn nhà
            $table->string('address', 500)
                  ->comment('Địa chỉ đầy đủ của căn nhà (số nhà, đường, phường, quận, thành phố)');
            
            // Tọa độ địa lý - Vĩ độ cho định vị trên bản đồ
            $table->decimal('latitude', 10, 8)
                  ->nullable()
                  ->comment('Vĩ độ (latitude) - Tọa độ địa lý cho định vị trên bản đồ');
            
            // Tọa độ địa lý - Kinh độ cho định vị trên bản đồ  
            $table->decimal('longitude', 11, 8)
                  ->nullable()
                  ->comment('Kinh độ (longitude) - Tọa độ địa lý cho định vị trên bản đồ');
            
            // Mô tả chi tiết - Thông tin chi tiết về căn nhà, tiện nghi, đặc điểm
            $table->longText('description')
                  ->nullable()
                  ->comment('Mô tả chi tiết về căn nhà, tiện nghi, quy định, đặc điểm nổi bật');
            
            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();
            
            // Index cho tối ưu truy vấn
            $table->index('host_id', 'idx_houses_host_id');
            $table->index(['latitude', 'longitude'], 'idx_houses_coordinates');
            $table->index('name', 'idx_houses_name');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng houses khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
