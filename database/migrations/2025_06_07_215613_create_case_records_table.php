<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('case_records', function (Blueprint $table) {
            $table->id('case_id'); // Primary key
            $table->string('case_type', 100);
            $table->text('description');
            $table->string('reported_by', 100);
            $table->string('referred_by', 100);
            $table->dateTime('referral_date');
            $table->string('reason_for_referral', 100);
            $table->string('presenting_problem', 100);
            $table->text('observe_behavior');
            $table->string('family_background', 100);
            $table->string('academic_history', 100);
            $table->string('social_relationships', 100);
            $table->string('medical_history', 500);
            $table->string('counselor_assessment', 500);
            $table->string('recommendations', 500);
            $table->string('follow_up_plan', 500);
            $table->date('filed_date')->default(DB::raw('CURDATE()'));
            $table->time('filed_time')->default(DB::raw('CURTIME()'));
            $table->string('status', 50);
            $table->unsignedBigInteger('student_id');

            // Optional foreign key if `students` table exists
            // $table->foreign('student_id')->references('s_id')->on('students')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_records');
    }
};
