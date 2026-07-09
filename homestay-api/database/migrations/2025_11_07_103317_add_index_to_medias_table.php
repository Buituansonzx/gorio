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
        Schema::table('medias', function (Blueprint $table) {
            // Thêm composite index cho room_id và sort_index
            $table->index(['room_id', 'sort_index'], 'idx_medias_room_sort');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medias', function (Blueprint $table) {
            // Drop index khi rollback
            $table->dropIndex('idx_medias_room_sort');
        });
    }
};
