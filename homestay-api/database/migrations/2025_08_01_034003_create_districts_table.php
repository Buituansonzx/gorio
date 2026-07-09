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
        Schema::create('districts', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của huyện/quận (UUID)');
            $table->uuid('province_id')->comment('ID của tỉnh/thành phố (UUID)');
            $table->string('code')->unique()->comment('Mã huyện/quận');
            $table->json('name')->comment('Tên huyện/quận (đa ngôn ngữ)');
            $table->foreign('province_id')
                  ->references('id')
                  ->on('provinces')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng provinces - ID của tỉnh/thành phố (UUID)');
            $table->timestamps();
            $table->index('province_id', 'idx_districts_province_id');
            $table->index('code', 'idx_districts_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
