<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'hosts',
            'orders',
            'favorites',
            'reviews',
            'notification_user',
            'oauth_access_tokens',
            'oauth_auth_codes',
            'oauth_device_codes',
            'vouchers',
            'user_devices',
        ];

        foreach ($tables as $table) {
            if ($table === 'vouchers') {
                Schema::table($table, function (Blueprint $t) {
                    $t->uuid('created_by_uuid')->nullable()->after('created_by');
                });

                DB::table($table)->update([
                    'created_by_uuid' => DB::raw('(SELECT uuid FROM users WHERE users.id = vouchers.created_by)')
                ]);

                continue; // bỏ qua user_id logic vì bảng này dùng created_by
            }
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            Schema::table($table, function (Blueprint $t) {
                $t->uuid('user_uuid')->nullable()->after('user_id');
            });

            // Copy user_id -> user_uuid
            DB::table($table)->update([
                'user_uuid' => DB::raw('(SELECT uuid FROM users WHERE users.id = ' . $table . '.user_id)')
            ]);

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'hosts', 'orders', 'favorites', 'reviews', 'notification_user',
            'oauth_access_tokens', 'oauth_auth_codes', 'oauth_device_codes','vouchers','user_devices'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) {
                    if (Schema::hasColumn($t->getTable(), 'user_uuid')) {
                        $t->dropColumn('user_uuid');
                    }
                    if (Schema::hasColumn($t->getTable(), 'created_by_uuid')) {
                        $t->dropColumn('created_by_uuid');
                    }
                });
            }
        }
    }

};
