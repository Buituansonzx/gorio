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
        Schema::create('room_image_area_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 100)->unique()->comment('Mã định danh cho nhóm khu vực ảnh (VD: living_room, bedroom, bathroom, kitchen, balcony, exterior)');
            $table->json('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_image_area_group');
    }
};
