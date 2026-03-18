<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('activity_logs')) {
            return;
        }

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('action', 191);

            $table->unsignedBigInteger('actor_user_id')->nullable()->index();
            $table->string('actor_role', 50)->nullable()->index();
            $table->string('actor_role_id', 64)->nullable()->index();

            $table->string('subject_type', 191)->nullable()->index();
            $table->string('subject_id', 64)->nullable()->index();
            $table->string('subject_table', 191)->nullable()->index();

            $table->json('data')->nullable();
            $table->json('meta')->nullable();

            $table->timestamp('created_at')->useCurrent()->index();

            // Soft foreign key if users table exists; don't require it.
            // (Some deployments may not have FK constraints enabled.)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

