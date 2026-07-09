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
        Schema::create('room_highlight_amenities_images', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment('Mã định danh duy nhất cho ảnh tiện ích nổi bật (UUID)');
            $table->uuid('highlight_amenity_id')->comment('ID tiện ích nổi bật (UUID)');
            $table->string('file_path')->nullable()->after('highlight_amenity_id')->comment('Đường dẫn file ảnh');
            $table->text('image_url')->nullable()->comment('URL ảnh (S3 hoặc public)');
            $table->text('file_name')->nullable()->comment('Tên file ảnh');
            $table->integer('file_size')->nullable();
            $table->string('mime')->nullable();
            $table->integer('width')->nullable()->after('file_size');
            $table->integer('height')->nullable()->after('width');
            $table->string('disk')->default('s3')->after('mime');
            $table->json('variants')->nullable()->after('disk');
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('highlight_amenity_id')->references('id')->on('room_highlight_amenities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_highlight_amenities_images');
    }
};
