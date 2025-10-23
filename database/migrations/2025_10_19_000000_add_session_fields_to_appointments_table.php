<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('appointment_datetime');
            }
            if (!Schema::hasColumn('appointments', 'ended_at')) {
                $table->timestamp('ended_at')->nullable()->after('started_at');
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'ended_at')) {
                $table->dropColumn('ended_at');
            }
            if (Schema::hasColumn('appointments', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });
    }
};
