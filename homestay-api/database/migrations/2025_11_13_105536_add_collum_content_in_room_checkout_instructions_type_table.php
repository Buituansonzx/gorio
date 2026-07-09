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
        Schema::table('room_checkout_instructions_type', function (Blueprint $table) {
            $table->text('content')->nullable()->after('checkout_instruction_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_checkout_instructions_type', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
};
