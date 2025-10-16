<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('appointments', 'decline_reason')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->text('decline_reason')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('appointments', 'decline_reason')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('decline_reason');
            });
        }
    }
};
