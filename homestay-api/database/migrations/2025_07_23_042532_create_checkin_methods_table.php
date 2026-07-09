<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('checkin_methods', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique()->comment('Mã phương thức checkin');
            $table->json('name')->comment('Tên phương thức checkin');
            $table->json('description')->nullable()->comment('Mô tả phương thức checkin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkin_methods');
    }
};
