<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng policies để lưu thông tin các chính sách của từng phòng
     */
    public function up(): void
    {
        Schema::create('room_policy', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của chính sách
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của chính sách phòng (UUID)');

            // Foreign key UUID - Liên kết với bảng rooms để xác định phòng có chính sách này
            $table->uuid('room_id')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng có chính sách này (UUID)');

            // Tạo foreign key constraint
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Loại chính sách - Phân loại chính sách để dễ quản lý và hiển thị
            $table->string('policy_type', 50)
                  ->comment('Loại chính sách (VD: cancellation, house_rules, check_in, check_out, payment, safety)');

            // Nội dung chính sách - Chi tiết đầy đủ của chính sách
            $table->longText('content')
                  ->comment('Nội dung chi tiết của chính sách (quy định, điều khoản, hướng dẫn)');

            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();

            // Index cho tối ưu truy vấn
            $table->index('room_id', 'idx_policies_room_id');
            $table->index('policy_type', 'idx_policies_type');

            // Unique constraint để tránh trùng lặp loại chính sách trong cùng một phòng
            $table->unique(['room_id', 'policy_type'], 'unique_room_policy_type');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng policies khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_policy');
    }
};
