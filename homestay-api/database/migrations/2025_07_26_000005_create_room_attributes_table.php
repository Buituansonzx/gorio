<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_attributes: Liên kết phòng với thuộc tính và số lượng
     */
    public function up(): void
    {
        Schema::create('room_attributes', function (Blueprint $table) {
            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Khóa ngoại tới attributes
            $table->uuid('attribute_id')->comment('ID thuộc tính (UUID)');

            // Số lượng cấu hình
            $table->integer('quantity')->default(0)->comment('Số lượng thuộc tính');

            // Khóa chính kết hợp
            $table->primary(['room_id', 'attribute_id'], 'pk_room_attributes');

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_attributes khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_attributes');
    }
};
