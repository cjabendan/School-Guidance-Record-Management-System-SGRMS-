<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_logs', 'action')) {
                $table->string('action', 191)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'actor_user_id')) {
                $table->unsignedBigInteger('actor_user_id')->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'actor_role')) {
                $table->string('actor_role', 50)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'actor_role_id')) {
                $table->string('actor_role_id', 64)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'subject_type')) {
                $table->string('subject_type', 191)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'subject_id')) {
                $table->string('subject_id', 64)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'subject_table')) {
                $table->string('subject_table', 191)->nullable()->index();
            }
            if (! Schema::hasColumn('activity_logs', 'data')) {
                $table->json('data')->nullable();
            }
            if (! Schema::hasColumn('activity_logs', 'meta')) {
                $table->json('meta')->nullable();
            }
            if (! Schema::hasColumn('activity_logs', 'created_at')) {
                $table->timestamp('created_at')->nullable()->index();
            }
        });

        // Backfill from legacy columns when present.
        $hasActivity = Schema::hasColumn('activity_logs', 'activity');
        $hasTimestamp = Schema::hasColumn('activity_logs', 'timestamp');
        $hasUserId = Schema::hasColumn('activity_logs', 'user_id');

        if ($hasActivity && Schema::hasColumn('activity_logs', 'action')) {
            DB::table('activity_logs')
                ->whereNull('action')
                ->update(['action' => DB::raw('activity')]);
        }

        if ($hasTimestamp && Schema::hasColumn('activity_logs', 'created_at')) {
            DB::table('activity_logs')
                ->whereNull('created_at')
                ->update(['created_at' => DB::raw('timestamp')]);
        }

        if ($hasUserId && Schema::hasColumn('activity_logs', 'actor_user_id')) {
            DB::table('activity_logs')
                ->whereNull('actor_user_id')
                ->update(['actor_user_id' => DB::raw('user_id')]);
        }

        // Keep existing legacy columns for backwards compatibility; no destructive changes here.
    }

    public function down(): void
    {
        // Non-destructive migration; we intentionally do not drop columns.
    }
};

