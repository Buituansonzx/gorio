<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('notifications', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type', 30); // ví dụ: order, booking, system...
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();  // chứa order_id, booking_id...
            $table->timestamps();              // created_at = lúc gửi thông báo
        });

        Schema::create('notification_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('notification_id');
            $table->uuid('user_id');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->unique(['notification_id', 'user_id']);
        });

        Schema::create('user_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('player_id');
            $table->string('device_type')->nullable(); // ios, android, web
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_users');
        Schema::dropIfExists('user_devices');
    }
};
