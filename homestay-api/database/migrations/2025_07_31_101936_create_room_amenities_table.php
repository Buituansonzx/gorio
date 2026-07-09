<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_amenities', function (Blueprint $table) {
            $table->uuid('room_id');
            $table->uuid('amenity_id');
            $table->primary(['room_id', 'amenity_id'], 'room_amenities_primary_key');
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng (UUID)');
            $table->foreign('amenity_id')
                  ->references('id')
                  ->on('amenities')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng amenities - ID của tiện nghi (UUID)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_amenities');
    }
};
