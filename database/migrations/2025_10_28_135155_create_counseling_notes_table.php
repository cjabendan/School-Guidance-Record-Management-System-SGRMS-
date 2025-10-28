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
        Schema::create('counseling_notes', function (Blueprint $table) {
            $table->id('note_id');
            $table->string('student_id', 50); // FK to students.s_id
            $table->unsignedBigInteger('user_id'); // counselor or creator
            $table->text('observations');
            $table->enum('remarks', ['Alarming', 'Moderate', 'Low']);
            $table->text('recommendations')->nullable();
            $table->boolean('follow_up_needed')->default(false);
            $table->dateTime('follow_up_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('s_id')
                ->on('students')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_notes');
    }
};
