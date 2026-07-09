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
        Schema::table('room_highlight_amenities_images', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_name', 'file_size']);

            $table->renameColumn('image_url', 'path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_highlight_amenities_images', function (Blueprint $table) {
            $table->renameColumn('path', 'image_url');

            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->integer('file_size')->nullable();
        });
    }
};
