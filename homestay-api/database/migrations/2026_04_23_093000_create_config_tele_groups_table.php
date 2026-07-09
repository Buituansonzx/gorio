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
        Schema::create('config_tele_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->comment('Tên nhóm Telegram');
            $table->string('chat_id')->comment('Chat ID của nhóm Telegram');
            $table->string('bot_token')->comment('Bot token của nhóm Telegram');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('config_tele_group_house', function (Blueprint $table) {
            $table->uuid('config_tele_group_id');
            $table->uuid('house_id');
            
            $table->foreign('config_tele_group_id')->references('id')->on('config_tele_groups')->onDelete('cascade');
            $table->foreign('house_id')->references('id')->on('houses')->onDelete('cascade');
            
            $table->primary(['config_tele_group_id', 'house_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_tele_group_house');
        Schema::dropIfExists('config_tele_groups');
    }
};
