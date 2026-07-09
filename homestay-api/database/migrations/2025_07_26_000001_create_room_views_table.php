<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_views: Liên kết nhiều-nhiều giữa rooms và views
     */
    public function up(): void
    {
        Schema::create('room_views', function (Blueprint $table) {
            // Khóa ngoại tới rooms (BIGINT UNSIGNED)
            $table->uuid('room_id')->comment('ID của phòng (UUID)');

            // Khóa ngoại tới views (BIGINT UNSIGNED)
            $table->uuid('view_id')->comment('ID của view');

            // Khóa chính kết hợp
            $table->primary(['room_id', 'view_id'], 'pk_room_views');

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('view_id')->references('id')->on('views')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_views khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_views');
    }
};
