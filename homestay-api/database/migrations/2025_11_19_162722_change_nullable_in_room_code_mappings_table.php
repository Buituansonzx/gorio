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
        Schema::table('room_code_mappings', function (Blueprint $table) {
            $table->string('hong_manage_house_id')->nullable()->change();
            $table->string('hong_manage_room_code')->nullable()->change();
            $table->dropUnique('room_code_mappings_hong_manage_house_id_unique');
            $table->dropUnique('room_code_mappings_hong_manage_room_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_code_mappings', function (Blueprint $table) {
            $table->string('hong_manage_house_id')->nullable(false)->change();
            $table->string('hong_manage_room_code')->nullable(false)->change();
            $table->unique('hong_manage_house_id');
            $table->unique('hong_manage_room_code');
        });
    }
};
