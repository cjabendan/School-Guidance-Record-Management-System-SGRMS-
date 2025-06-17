<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id')->nullable();
            $table->enum('action', ['created', 'approved', 'rescheduled', 'canceled', 'completed']);
            $table->date('old_date')->nullable();
            $table->time('old_time')->nullable();
            $table->date('new_date')->nullable();
            $table->time('new_time')->nullable();
            $table->text('reason')->nullable();
            $table->string('action_by', 100)->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Optional: Add foreign key if desired
            // $table->foreign('appointment_id')->references('id')->on('appointment')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_history');
    }
};

