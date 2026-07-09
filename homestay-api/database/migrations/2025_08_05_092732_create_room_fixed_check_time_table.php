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
        Schema::create('room_fixed_check_time', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->comment('Khóa ngoại đến bảng rooms');
            $table->uuid('fixed_check_time_id');
            $table->foreign('fixed_check_time_id')->references('id')->on('fixed_check_times')->onDelete('cascade')->comment('Khóa ngoại đến bảng fixed_check_times');
            $table->unsignedInteger('price');
            $table->unsignedInteger('mon_price')->default(0)->comment('Giá đặt trước vào thứ Hai');
            $table->unsignedInteger('tue_price')->default(0)->comment('Giá đặt trước vào thứ Ba');
            $table->unsignedInteger('wed_price')->default(0)->comment('Giá đặt trước vào thứ Tư');
            $table->unsignedInteger('thu_price')->default(0)->comment('Giá đặt trước vào thứ Năm');
            $table->unsignedInteger('fri_price')->default(0)->comment('Giá đặt trước vào thứ Sáu');
            $table->unsignedInteger('sat_price')->default(0)->comment('Giá đặt trước vào thứ Bảy');
            $table->unsignedInteger('sun_price')->default(0)->comment('Giá đặt trước vào Chủ Nhật');
            $table->string('currency', 3)->default('VND')->comment('Mã tiền tệ, mặc định là VND');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_fixed_check_time');
    }
};
