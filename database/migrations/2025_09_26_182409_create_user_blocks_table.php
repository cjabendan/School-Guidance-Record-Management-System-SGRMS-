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
        Schema::create('user_blocks', function (Blueprint $table) {
            $table->id();
            // The user who initiated the block
            $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');
            // The user who is being blocked
            $table->foreignId('blocked_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Ensure a user can only block another user once
            $table->unique(['blocker_id', 'blocked_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_blocks');
    }
};
