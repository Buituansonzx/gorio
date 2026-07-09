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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed');
            $table->unsignedInteger('discount_value');      // Giá trị giảm giá (số tiền hoặc phần trăm)
            $table->unsignedInteger('max_discount_amount')->nullable();  // Giới hạn giá trị giảm tối đa (chỉ áp dụng khi discount_type là 'percentage')
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('quantity')->nullable();          // Tổng số lượt phát hành (null = không giới hạn)
            $table->unsignedInteger('usage_limit')->nullable();      // Giới hạn số lần sử dụng cho 1 user
            $table->unsignedInteger('used_count')->default(0);       // Số lần đã sử dụng
            $table->decimal('min_order_amount', 12, 2)->nullable();   // Đơn tối thiểu
            $table->boolean('is_active')->default(true);
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->uuid('voucher_id')->nullable()->after('total');
            $table->unsignedInteger('discount_amount')->default(0)->after('voucher_id');

            $table->foreign('voucher_id')->references('id')->on('vouchers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn('voucher_id');
            $table->dropColumn('discount_amount');
        });
        Schema::dropIfExists('vouchers');

    }
};
