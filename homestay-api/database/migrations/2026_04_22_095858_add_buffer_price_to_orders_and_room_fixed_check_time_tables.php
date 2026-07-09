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
        Schema::table('room_fixed_check_time', function (Blueprint $table) {
            $table->unsignedInteger('mon_buffer_price')->default(0)->after('mon_price');
            $table->unsignedInteger('tue_buffer_price')->default(0)->after('tue_price');
            $table->unsignedInteger('wed_buffer_price')->default(0)->after('wed_price');
            $table->unsignedInteger('thu_buffer_price')->default(0)->after('thu_price');
            $table->unsignedInteger('fri_buffer_price')->default(0)->after('fri_price');
            $table->unsignedInteger('sat_buffer_price')->default(0)->after('sat_price');
            $table->unsignedInteger('sun_buffer_price')->default(0)->after('sun_price');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('buffer_price')->default(0)->after('total');
        });

        Schema::table('room_combo_pricing', function (Blueprint $table) {
            $table->unsignedInteger('mon_buffer_price')->default(0)->after('mon_price');
            $table->unsignedInteger('tue_buffer_price')->default(0)->after('tue_price');
            $table->unsignedInteger('wed_buffer_price')->default(0)->after('wed_price');
            $table->unsignedInteger('thu_buffer_price')->default(0)->after('thu_price');
            $table->unsignedInteger('fri_buffer_price')->default(0)->after('fri_price');
            $table->unsignedInteger('sat_buffer_price')->default(0)->after('sat_price');
            $table->unsignedInteger('sun_buffer_price')->default(0)->after('sun_price');
        });

        Schema::table('room_hourly_pricing', function (Blueprint $table) {
            $table->unsignedInteger('buffer_price')->default(0)->after('next_hour_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_fixed_check_time', function (Blueprint $table) {
            $table->dropColumn([
                'mon_buffer_price',
                'tue_buffer_price',
                'wed_buffer_price',
                'thu_buffer_price',
                'fri_buffer_price',
                'sat_buffer_price',
                'sun_buffer_price',
            ]);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('buffer_price');
        });

        Schema::table('room_combo_pricing', function (Blueprint $table) {
            $table->dropColumn([
                'mon_buffer_price',
                'tue_buffer_price',
                'wed_buffer_price',
                'thu_buffer_price',
                'fri_buffer_price',
                'sat_buffer_price',
                'sun_buffer_price',
            ]);
        });

        Schema::table('room_hourly_pricing', function (Blueprint $table) {
            $table->dropColumn('buffer_price');
        });
    }
};
