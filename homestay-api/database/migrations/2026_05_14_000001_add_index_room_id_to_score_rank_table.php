<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('score_rank', function (Blueprint $table) {
            $table->index('room_id', 'idx_score_rank_room_id');
        });
    }

    public function down(): void
    {
        Schema::table('score_rank', function (Blueprint $table) {
            $table->dropIndex('idx_score_rank_room_id');
        });
    }
};
