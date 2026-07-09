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
        Schema::create('sms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('content');
            $table->string('sender')->after('content');
            $table->enum('status', ['pending', 'order_not_found', 'processing', 'done'])->default('pending')->after('content');
            $table->string('description')->nullable()->after('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
