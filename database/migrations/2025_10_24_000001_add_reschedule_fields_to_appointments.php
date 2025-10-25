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
        if (! Schema::hasTable('appointments')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            if (! Schema::hasColumn('appointments', 'reschedule_proposed_datetime')) {
                $table->dateTime('reschedule_proposed_datetime')->nullable()->after('appointment_datetime');
            }

            if (! Schema::hasColumn('appointments', 'reschedule_reason')) {
                $table->text('reschedule_reason')->nullable()->after('reschedule_proposed_datetime');
            }

            if (! Schema::hasColumn('appointments', 'reschedule_requester_id')) {
                $table->unsignedBigInteger('reschedule_requester_id')->nullable()->after('reschedule_reason');
            }

            if (! Schema::hasColumn('appointments', 'rescheduled_count')) {
                $table->unsignedInteger('rescheduled_count')->default(0)->after('status');
            }

            if (! Schema::hasColumn('appointments', 'last_rescheduled_at')) {
                $table->timestamp('last_rescheduled_at')->nullable()->after('rescheduled_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('appointments')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'reschedule_proposed_datetime')) {
                $table->dropColumn('reschedule_proposed_datetime');
            }
            if (Schema::hasColumn('appointments', 'reschedule_reason')) {
                $table->dropColumn('reschedule_reason');
            }
            if (Schema::hasColumn('appointments', 'reschedule_requester_id')) {
                $table->dropColumn('reschedule_requester_id');
            }
            if (Schema::hasColumn('appointments', 'rescheduled_count')) {
                $table->dropColumn('rescheduled_count');
            }
            if (Schema::hasColumn('appointments', 'last_rescheduled_at')) {
                $table->dropColumn('last_rescheduled_at');
            }
        });
    }
};
