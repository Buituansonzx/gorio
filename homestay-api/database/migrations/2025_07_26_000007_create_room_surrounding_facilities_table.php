<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tạo bảng room_surrounding_facilities: Liên kết phòng với tiện ích xung quanh
     */
    public function up(): void
    {
        Schema::create('room_surrounding_facilities', function (Blueprint $table) {
            // Khóa ngoại tới rooms
            $table->uuid('room_id')->comment('ID phòng (UUID)');

            // Khóa ngoại tới surrounding_facilities
            $table->uuid('facility_id')->comment('ID tiện ích xung quanh');

            // Khóa chính kết hợp
            $table->primary(['room_id', 'facility_id'], 'pk_room_surrounding_facilities');

            // Ràng buộc khóa ngoại
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('facility_id')->references('id')->on('surrounding_facilities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     * Xóa bảng room_surrounding_facilities khi rollback migration
     */
    public function down(): void
    {
        Schema::dropIfExists('room_surrounding_facilities');
    }
};
