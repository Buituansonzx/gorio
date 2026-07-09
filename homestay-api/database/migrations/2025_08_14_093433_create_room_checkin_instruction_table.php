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
        Schema::create('room_checkin_instruction', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id');
            $table->foreign('room_id', 'rci_room_fk') // đặt tên ngắn gọn
            ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
            $table->uuid('checkin_method_id');
            $table->foreign('checkin_method_id', 'rci_method_fk') // đặt tên ngắn gọn
            ->references('id')
                ->on('checkin_methods')
                ->onDelete('cascade');
            $table->longText('way_to_house_message')->comment('Hướng dẫn đường đi đến nhà')->nullable();
            $table->string('directions_message')->nullable();
            $table->string('wifi_name')->nullable();
            $table->string('wifi_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_checkin_instruction');
    }
};
