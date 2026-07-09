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
        Schema::create('amenity_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 100)->unique()->comment('Unique identifier for the amenity group (e.g., kitchen, bathroom, living_room)');
            $table->json('name')->comment('Name of the amenity group (e.g., Kitchen, Bathroom, Living Room)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenity_group');
    }
};
