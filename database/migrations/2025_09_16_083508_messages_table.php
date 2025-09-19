<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('msg_id'); // Primary key
            $table->unsignedBigInteger('conversation_id'); // Link to conversation table
            $table->unsignedBigInteger('sender_id'); // Who sent the message
            $table->unsignedBigInteger('receiver_id'); // Who receives the message
            $table->text('msg'); // Message content
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent'); // Status
            $table->timestamps();

            // Foreign keys
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
