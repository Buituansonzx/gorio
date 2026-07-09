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
        Schema::create('medias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('disk')->default('public');
            $table->string('path');                // uploads/2025/09/original.jpg
            $table->integer('width');
            $table->integer('height');
            $table->string('mime');
            // sizeName => ['width','height','jpg'=>['path','bytes','mime'], 'webp'=>[...]]
            $table->json('variants')->nullable();

            // Các trường có thể nullable dựa trên migration change_nullable
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('path')->nullable();
            $table->string('disk')->nullable();
            $table->string('file_hash')->nullable();
            $table->json('conversions_disk')->nullable();
            $table->uuid('uuid')->nullable();
            $table->json('generated_conversions')->nullable();
            $table->json('custom_properties')->nullable();
            $table->json('responsive_images')->nullable();
            $table->unsignedBigInteger('order_column')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
