<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng hosts để lưu thông tin các chủ nhà trong hệ thống
     */
    public function up(): void
    {
        Schema::create('hosts', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của chủ nhà
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của chủ nhà (UUID)');

            // Foreign key - Liên kết với bảng users để xác định người dùng nào là chủ nhà này
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng users - ID của người dùng là chủ nhà');

            // Tên thương hiệu - Cho phép null nếu chủ nhà không có tên thương hiệu
            $table->string('business_name', 255)
                  ->nullable()
                  ->comment('Tên thương hiệu/công ty của chủ nhà (nếu có)');

            // Ảnh đại diện của chủ nhà
            $table->string('avatar')
                  ->nullable()
                  ->comment('URL của ảnh đại diện người dùng, có thể là pre-signed URL từ AWS S3 hoặc URL công khai');

            // Mô tả về chủ nhà - Thông tin chi tiết về chủ nhà và dịch vụ
            $table->text('description')
                  ->nullable()
                  ->comment('Mô tả chi tiết về chủ nhà, kinh nghiệm, dịch vụ cung cấp');

            // Địa chỉ của host
            $table->string('address', 255)
                  ->nullable()
                  ->comment('Địa chỉ của host');

            // Số điện thoại hotline của host
            $table->string('hotline', 20)
                  ->nullable()
                  ->comment('Số điện thoại hotline của host');

            // Trạng thái hoạt động
            $table->boolean('is_active')
                  ->default(true)
                  ->comment('true=active, false=inactive');

            // Trạng thái xác minh - Đánh dấu chủ nhà đã được admin xác minh hay chưa
            $table->boolean('verified_status')
                  ->default(false)
                  ->comment('Trạng thái xác minh chủ nhà: true=đã xác minh, false=chưa xác minh');

            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();

            // Index cho tối ưu truy vấn
            $table->index('user_id', 'idx_hosts_user_id');
            $table->index('verified_status', 'idx_hosts_verified_status');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng hosts khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('hosts');
    }
};
