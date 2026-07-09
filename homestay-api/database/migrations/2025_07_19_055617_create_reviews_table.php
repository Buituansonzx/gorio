<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng reviews để lưu thông tin đánh giá của khách hàng về phòng
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            // Primary key UUID - Mã định danh duy nhất của đánh giá
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của đánh giá (UUID)');

            // Foreign key - Liên kết với bảng users để xác định người đánh giá
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng users - ID của người đánh giá');

            // Foreign key UUID - Liên kết với bảng rooms để xác định phòng được đánh giá
            $table->uuid('room_id')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng được đánh giá (UUID)');

            // Tạo foreign key constraint cho room_id
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');

            // Điểm đánh giá - Thang điểm từ 1 đến 5 sao
            $table->unsignedTinyInteger('rating')
                  ->comment('Điểm đánh giá từ 1 đến 5 sao (1: rất tệ, 5: xuất sắc)');

            // Nội dung bình luận - Chi tiết đánh giá của khách hàng
            $table->text('content')
                  ->nullable()
                  ->comment('Nội dung bình luận, nhận xét chi tiết về phòng và dịch vụ');

            // Các cột đánh giá chi tiết
            $table->unsignedTinyInteger('cleanliness_rating')
                ->nullable()
                ->after('content')
                ->comment('Điểm vệ sinh');
            $table->unsignedTinyInteger('accuracy_rating')
                ->nullable()
                ->after('cleanliness_rating')
                ->comment('Điểm chính xác');
            $table->unsignedTinyInteger('checkin_rating')
                ->nullable()
                ->after('accuracy_rating')
                ->comment('Điểm nhận phòng');
            $table->unsignedTinyInteger('communication_rating')
                ->nullable()
                ->after('checkin_rating')
                ->comment('Điểm giao tiếp');
            $table->unsignedTinyInteger('location_rating')
                ->nullable()
                ->after('communication_rating')
                ->comment('Điểm vị trí');
            $table->unsignedTinyInteger('value_rating')
                ->nullable()
                ->after('location_rating')
                ->comment('Điểm giá');

            // Timestamps - Thời gian tạo và cập nhật bản ghi
            $table->timestamps();

            // Index cho tối ưu truy vấn
            $table->index('user_id', 'idx_reviews_user_id');
            $table->index('room_id', 'idx_reviews_room_id');
            $table->index('rating', 'idx_reviews_rating');
            $table->index('created_at', 'idx_reviews_created_at');

            // Unique constraint để mỗi user chỉ đánh giá 1 lần cho 1 phòng
            $table->unique(['user_id', 'room_id'], 'unique_user_room_review');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng reviews khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
