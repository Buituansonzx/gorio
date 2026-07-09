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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('code')-> unique()
                  ->comment('Mã đơn hàng, duy nhất cho mỗi đơn hàng');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Khóa ngoại tới bảng users - ID của người đặt hàng');

            $table->uuid('room_id')
                  ->comment('Khóa ngoại tới bảng rooms - ID của phòng được đặt');

            $table->dateTime('check_in')
                  ->comment('Thời gian nhận phòng');

            $table->dateTime('check_out')
                  ->comment('Thời gian trả phòng');

            $table->unsignedInteger('number_of_guests')
                  ->default(1)
                  ->comment('Số lượng khách đặt phòng, mặc định là 1');

            $table->string('guest_name', 255)
                  ->nullable()
                  ->comment('Tên khách hàng đặt phòng');

            $table->string('guest_phone', 255)
                  ->nullable()
                  ->comment('Số điện thoại của khách hàng đặt phòng');

            $table->unsignedInteger('total')
                  ->nullable()
                  ->comment('Tổng giá phòng đã đặt, tính theo đơn vị tiền tệ của hệ thống');

            $table->string('note')
                  ->nullable()
                  ->comment('Ghi chú của khách hàng về đơn hàng, có thể để trống nếu không có ghi chú');

            $table->integer('status')->default('1')->comment(' Trạng thái đơn hàng: -1 = đã hủy, 1 =đã xác nhận');
            $table->timestamp('expires_at')->after('status')->comment('Thời gian giữ phòng');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE orders MODIFY status ENUM('pending','confirmed','cancelled','expired') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
