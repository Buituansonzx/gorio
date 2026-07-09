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
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            // Try to drop by column reference first; if that fails, attempt by conventional FK name
            try {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['user_id']);
                });
            } catch (\Throwable $e) {
                try {
                    Schema::table($table, function (Blueprint $t) use ($table) {
                        $t->dropForeign("{$table}_user_id_foreign");
                    });
                } catch (\Throwable $e2) {
                    // Last resort: attempt to lookup constraint name from information_schema (MySQL)
                    try {
                        $driver = DB::getDriverName();
                        if (in_array($driver, ['mysql', 'pgsql'])) {
                            $conn = DB::connection()->getDoctrineSchemaManager();
                            $foreignKeys = $conn->listTableForeignKeys($table);
                            foreach ($foreignKeys as $fk) {
                                $localCols = $fk->getLocalColumns();
                                if (in_array('user_id', $localCols)) {
                                    $name = $fk->getName();
                                    Schema::table($table, function (Blueprint $t) use ($name) {
                                        $t->dropForeign($name);
                                    });
                                    break;
                                }
                            }
                        }
                    } catch (\Throwable $ignore) {
                        // ignore any errors - migration is best-effort safe-drop
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create foreign keys referencing users.id with ON DELETE CASCADE where applicable
        $recreate = [
            'hosts' => ['user_id' => ['nullable' => false, 'cascade' => true]],
            'orders' => ['user_id' => ['nullable' => false, 'cascade' => true]],
            'favorites' => ['user_id' => ['nullable' => false, 'cascade' => true]],
            'reviews' => ['user_id' => ['nullable' => false, 'cascade' => true]],
            'notification_user' => ['user_id' => ['nullable' => false, 'cascade' => true]],
            'oauth_access_tokens' => ['user_id' => ['nullable' => true, 'cascade' => false]],
            'oauth_auth_codes' => ['user_id' => ['nullable' => true, 'cascade' => false]],
            'oauth_device_codes' => ['user_id' => ['nullable' => true, 'cascade' => false]],
            'vouchers' => ['created_by' => ['nullable' => false, 'cascade' => true]],
            'user_devices' => ['user_id' => ['nullable' => false, 'cascade' => true]],
        ];

        foreach ($recreate as $table => $cols) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'user_id')) {
                continue;
            }

            try {
                Schema::table($table, function (Blueprint $t) use ($cols) {
                    foreach ($cols as $col => $opts) {
                        // Skip if FK already exists (avoid exception)
                        try {
                            $t->foreign($col)->references('id')->on('users')->onDelete($opts['cascade'] ? 'cascade' : 'restrict');
                        } catch (\Throwable $e) {
                            // ignore
                        }
                    }
                });
            } catch (\Throwable $e) {
                // ignore recreation errors
            }
        }
    }
};
