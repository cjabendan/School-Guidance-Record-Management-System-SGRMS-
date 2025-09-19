<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_one'); // First participant
            $table->unsignedBigInteger('user_two'); // Second participant
            $table->timestamps();

            // Prevent duplicate conversations
            $table->unique(['user_one', 'user_two']);

            // Foreign keys
            $table->foreign('user_one')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
