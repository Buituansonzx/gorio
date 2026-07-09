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
        Schema::create('room_checkout_instructions_type', function (Blueprint $table) {
            $table->uuid('room_id');
            $table->foreign('room_id', 'rcit_room_fk') // đặt tên ngắn
            ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
            $table->uuid('checkout_instruction_type_id');
            $table->foreign('checkout_instruction_type_id', 'rcit_type_fk') // đặt tên ngắn
            ->references('id')
                ->on('checkout_instructions_types')
                ->onDelete('cascade');
            $table->text('content')->nullable()->after('checkout_instruction_type_id');
            $table->timestamps();
            $table->primary(['room_id', 'checkout_instruction_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_checkout_instructions');
    }
};
