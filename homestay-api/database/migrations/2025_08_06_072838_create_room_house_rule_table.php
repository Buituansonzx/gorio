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
        Schema::create('room_house_rule', function (Blueprint $table) {
            $table->uuid('room_id')->comment('ID của phòng');
            $table->uuid('house_rule_id')->comment('ID của quy tắc nhà');
            $table->string('value')->comment('Giá trị của quy tắc nhà cho phòng');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('house_rule_id')->references('id')->on('house_rules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_house_rule');
    }
};
