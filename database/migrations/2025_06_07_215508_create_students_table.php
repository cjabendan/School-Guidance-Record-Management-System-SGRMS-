<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('s_id'); // Primary key
            $table->string('id_num', 20);
            $table->string('suffix', 10)->nullable();
            $table->string('lname', 50);
            $table->string('fname', 50);
            $table->string('mname', 50)->nullable();
            $table->enum('sex', ['Male', 'Female']);
            $table->date('bod');
            $table->string('address', 100);
            $table->string('mobile_num', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('educ_level', ['Elementary', 'High School', 'College']);
            $table->string('year_level', 20);
            $table->string('section', 20)->nullable();
            $table->string('program', 100)->nullable();
            $table->string('s_image', 255);
            $table->string('previous_school', 255)->nullable();
            $table->string('status', 20);
            $table->unsignedBigInteger('parent_id')->nullable();

            // Optional foreign key constraint
            // $table->foreign('parent_id')->references('p_id')->on('parents')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

