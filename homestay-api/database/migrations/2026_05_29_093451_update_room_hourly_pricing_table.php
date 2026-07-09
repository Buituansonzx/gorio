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
            $table->decimal('mon_min_hour_price', 15, 2)->default(0)->after('min_hours_price');
            $table->decimal('mon_buffer_price', 15, 2)->default(0)->after('mon_min_hour_price');
            $table->decimal('tue_min_hour_price', 15, 2)->default(0)->after('mon_buffer_price');
            $table->decimal('tue_buffer_price', 15, 2)->default(0)->after('tue_min_hour_price');
            $table->decimal('wed_min_hour_price', 15, 2)->default(0)->after('tue_buffer_price');
            $table->decimal('wed_buffer_price', 15, 2)->default(0)->after('wed_min_hour_price');
            $table->decimal('thu_min_hour_price', 15, 2)->default(0)->after('wed_buffer_price');
            $table->decimal('thu_buffer_price', 15, 2)->default(0)->after('thu_min_hour_price');
            $table->decimal('fri_min_hour_price', 15, 2)->default(0)->after('thu_buffer_price');
            $table->decimal('fri_buffer_price', 15, 2)->default(0)->after('fri_min_hour_price');
            $table->decimal('sat_min_hour_price', 15, 2)->default(0)->after('fri_buffer_price');
            $table->decimal('sat_buffer_price', 15, 2)->default(0)->after('sat_min_hour_price');
            $table->decimal('sun_min_hour_price', 15, 2)->default(0)->after('sat_buffer_price');
            $table->decimal('sun_buffer_price', 15, 2)->default(0)->after('sun_min_hour_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_hourly_pricing', function (Blueprint $table) {
            $table->dropColumn('mon_min_hour_price');
            $table->dropColumn('mon_buffer_price');
            $table->dropColumn('tue_min_hour_price');
            $table->dropColumn('tue_buffer_price');
            $table->dropColumn('wed_min_hour_price');
            $table->dropColumn('wed_buffer_price');
            $table->dropColumn('thu_min_hour_price');
            $table->dropColumn('thu_buffer_price');
            $table->dropColumn('fri_min_hour_price');
            $table->dropColumn('fri_buffer_price');
            $table->dropColumn('sat_min_hour_price');
            $table->dropColumn('sat_buffer_price');
            $table->dropColumn('sun_min_hour_price');
            $table->dropColumn('sun_buffer_price');
        });
    }
};
