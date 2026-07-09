<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bảng users: xóa id cũ, rename uuid -> id, set PK
        if (Schema::hasTable('users')) {
            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->dropPrimary(); // drop PK cũ
                });
            } catch (\Throwable $e) {}

            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->dropColumn('id');       // xóa cột id int
                    $t->renameColumn('uuid', 'id'); // đổi uuid -> id
                    $t->primary('id');           // set PK mới
                });
            } catch (\Throwable $e) {}
        }

        // 2. Bảng liên quan: rename user_uuid -> user_id
        $relatedTables = [
            'hosts',
            'orders',
            'favorites',
            'reviews',
            'notification_user',
            'oauth_access_tokens',
            'oauth_auth_codes',
            'oauth_device_codes',
            'vouchers', // created_by
            'user_devices'
        ];

        foreach ($relatedTables as $table) {
            if (!Schema::hasTable($table)) continue;

            $oldCol = ($table === 'vouchers') ? 'created_by' : 'user_id';
            $tempCol = ($table === 'vouchers') ? 'created_by_uuid' : 'user_uuid';

            // Xóa cột cũ nếu tồn tại
            if (Schema::hasColumn($table, $oldCol)) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($oldCol) {
                        $t->dropColumn($oldCol);
                    });
                } catch (\Throwable $e) {}
            }

            // Rename cột tạm thành cột chính
            if (Schema::hasColumn($table, $tempCol)) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($tempCol, $oldCol) {
                        $t->renameColumn($tempCol, $oldCol);
                    });
                } catch (\Throwable $e) {}
            }
        }

        // 3. Tạo FK mới trỏ sang users.id
        foreach ($relatedTables as $table) {
            if (!Schema::hasTable($table)) continue;

            $col = ($table === 'vouchers') ? 'created_by' : 'user_id';

            if (!Schema::hasColumn($table, $col)) continue;

            try {
                Schema::table($table, function (Blueprint $t) use ($col) {
                    $t->foreign($col)->references('id')->on('users')->onDelete('cascade');
                });
            } catch (\Throwable $e) {}
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $relatedTables = [
            'hosts',
            'orders',
            'favorites',
            'reviews',
            'notification_user',
            'oauth_access_tokens',
            'oauth_auth_codes',
            'oauth_device_codes',
            'vouchers',
            'user_devices'
        ];

        // Drop FK
        foreach ($relatedTables as $table) {
            $col = ($table === 'vouchers') ? 'created_by' : 'user_id';
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, $col)) continue;

            try {
                Schema::table($table, function (Blueprint $t) use ($col, $table) {
                    $t->dropForeign([$col]);
                });
            } catch (\Throwable $e) {}
        }

        // Bảng users revert PK
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'id')) {
            try {
                Schema::table('users', function (Blueprint $t) {
                    $t->dropPrimary();
                    $t->bigIncrements('id')->first();
                    $t->primary('id');
                });
            } catch (\Throwable $e) {}
        }

        // Bảng liên quan revert tên cột
        foreach ($relatedTables as $table) {
            $uuidCol = ($table === 'vouchers') ? 'created_by' : 'user_uuid';
            $newCol  = ($table === 'vouchers') ? 'created_by' : 'user_id';

            if (Schema::hasTable($table) && Schema::hasColumn($table, $newCol)) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($newCol, $uuidCol) {
                        $t->renameColumn($newCol, $uuidCol);
                    });
                } catch (\Throwable $e) {}
            }
        }
    }
};
