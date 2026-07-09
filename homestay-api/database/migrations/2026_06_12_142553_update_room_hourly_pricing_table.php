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
        Schema::table('room_hourly_pricing', function (Blueprint $table) {
            $table->integer('mon_min_hour_price')->default(0)->change();
            $table->integer('mon_buffer_price')->default(0)->change();
            $table->integer('tue_min_hour_price')->default(0)->change();
            $table->integer('tue_buffer_price')->default(0)->change();
            $table->integer('wed_min_hour_price')->default(0)->change();
            $table->integer('wed_buffer_price')->default(0)->change();
            $table->integer('thu_min_hour_price')->default(0)->change();
            $table->integer('thu_buffer_price')->default(0)->change();
            $table->integer('fri_min_hour_price')->default(0)->change();
            $table->integer('fri_buffer_price')->default(0)->change();
            $table->integer('sat_min_hour_price')->default(0)->change();
            $table->integer('sat_buffer_price')->default(0)->change();
            $table->integer('sun_min_hour_price')->default(0)->change();
            $table->integer('sun_buffer_price')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
