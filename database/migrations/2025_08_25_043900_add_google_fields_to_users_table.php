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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('email');
            $table->string('google_email')->nullable()->after('google_id');
            $table->text('google_token')->nullable()->after('google_email');
            $table->text('google_refresh_token')->nullable()->after('google_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'google_email', 'google_token', 'google_refresh_token']);
        });
    }
};
