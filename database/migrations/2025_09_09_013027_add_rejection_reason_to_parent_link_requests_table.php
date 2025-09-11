<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('parent_link_requests', function (Blueprint $table) {
            $table->string('rejection_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('parent_link_requests', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
