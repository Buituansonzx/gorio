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
        Schema::table('notification_users', function (Blueprint $table) {
            $table->dropPrimary('PRIMARY'); // tên primary key mặc định là 'PRIMARY'

            // Drop the id column
            $table->dropColumn('id');

            // Set composite primary key
            $table->primary(['notification_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notification_users', function (Blueprint $table) {
            // Drop composite primary key
            $table->dropPrimary(['notification_id', 'user_id']);

            // Re-add the id column
            $table->bigIncrements('id')->first();

            // Set id as primary key
            $table->primary('id');
        });
    }
};
