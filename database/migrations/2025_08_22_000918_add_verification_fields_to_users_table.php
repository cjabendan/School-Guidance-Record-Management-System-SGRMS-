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
        $table->string('verification_code', 10)->nullable()->after('token_expires_at');
        $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['verification_code', 'verification_code_expires_at']);
    });
}
};
