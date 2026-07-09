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
        Schema::create('provinces', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất của tỉnh/thành phố (UUID)');
            $table->string('code')->unique()->comment('Mã tỉnh/thành phố');
            $table->json('name')->comment('Tên tỉnh/thành phố (đa ngôn ngữ)');
            $table->timestamps();
            
            // Index for better query performance
            $table->index('code', 'idx_provinces_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
