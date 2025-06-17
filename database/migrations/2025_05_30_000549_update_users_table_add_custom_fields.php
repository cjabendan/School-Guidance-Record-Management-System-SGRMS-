<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove 'name' column if it exists
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }

            // Add new fields only if they don't already exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('student_id');
            }

            if (!Schema::hasColumn('users', 'counselor_id')) {
                $table->unsignedBigInteger('counselor_id')->nullable()->after('parent_id');
            }

            if (!Schema::hasColumn('users', 'account_status')) {
                $table->string('account_status')->nullable()->after('counselor_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the custom fields
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'student_id')) {
                $table->dropColumn('student_id');
            }

            if (Schema::hasColumn('users', 'parent_id')) {
                $table->dropColumn('parent_id');
            }

            if (Schema::hasColumn('users', 'counselor_id')) {
                $table->dropColumn('counselor_id');
            }

            if (Schema::hasColumn('users', 'account_status')) {
                $table->dropColumn('account_status');
            }
            // Restore the 'name' column
            $table->string('name')->after('id');
        });
    }
};
