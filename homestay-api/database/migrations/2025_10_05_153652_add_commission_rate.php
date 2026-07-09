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
        //
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('commission_percent')->default(15)->after('description')->comment('Commission percentage');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('commission_percent')->default(15)->after('total')->comment('Commission percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('commission_percent');
        });
    }
};
