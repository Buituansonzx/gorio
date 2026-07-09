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
        Schema::create('room_discount_pricing', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->comment('Khóa ngoại đến bảng rooms');
            $table->enum('type', ['week', 'month', 'last_hour'])->comment('Loại giảm giá: tuần, tháng, giờ chót');
            $table->double('pre_hours')->nullable()->comment('Số giờ đặt trước (có thể null nếu không áp dụng)');
            $table->decimal('percent')->comment('% giảm giá');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_discount_pricing');
    }
};
