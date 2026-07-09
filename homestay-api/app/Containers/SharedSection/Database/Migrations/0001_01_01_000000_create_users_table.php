<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id(); // Chuyển từ uuid về auto-increment bigint
            $table->string('name')->nullable();

            // Thêm avatar sau name
            $table->string('avatar')->nullable();
            // Thêm first_name, last_name sau name
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('email')->unique()->nullable();
            // Thêm phone sau email
            $table->string('phone', 20)->nullable()->unique();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            // Thêm otp_code và otp_expires_at sau password
            $table->string('otp_code', 10)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('gender')->nullable();
            $table->date('birth')->nullable();

            // Thêm country_code, phone_code, phone_number sau phone
            $table->string('country_code', 10)->nullable()->comment('Mã quốc gia (VN, US, CN, ...)');
            $table->string('phone_code', 10)->nullable()->comment('Đầu số điện thoại quốc gia (+84, +1, +86, ...)');
            $table->string('phone_number', 20)->nullable()->comment('Số điện thoại dạng number (không có đầu số quốc gia)');

            // Thêm status sau avatar
            $table->integer('status')->default(-1)->comment('1=active, 0=blocked, -1=inactive');
            // Thêm is_blocked sau status
            $table->boolean('is_blocked')->default(false);

            $table->rememberToken();
            $table->timestamps();

            // Index cho country_code + phone_number
            $table->index(['country_code', 'phone_number'], 'users_country_phone_index');
        });

        Schema::create('password_reset_tokens', static function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', static function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
