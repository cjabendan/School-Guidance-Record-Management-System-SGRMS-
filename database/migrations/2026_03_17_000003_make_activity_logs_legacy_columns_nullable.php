<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        // Ensure legacy columns won't block new-style inserts (e.g. auth.login_failed without a user).
        // Use raw SQL to avoid requiring doctrine/dbal for `change()`.
        try {
            if (Schema::hasColumn('activity_logs', 'activity')) {
                DB::statement('ALTER TABLE `activity_logs` MODIFY `activity` varchar(255) NULL');
            }
        } catch (\Throwable) {
            // ignore
        }

        try {
            if (Schema::hasColumn('activity_logs', 'user_id')) {
                DB::statement('ALTER TABLE `activity_logs` MODIFY `user_id` bigint unsigned NULL');
            }
        } catch (\Throwable) {
            // ignore
        }
    }

    public function down(): void
    {
        // No rollback: making columns NOT NULL again could break existing data.
    }
};

