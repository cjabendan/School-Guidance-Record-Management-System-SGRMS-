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
        Schema::create('counselors', function (Blueprint $table) {
            $table->id('c_id'); // Auto-incrementing primary key named c_id
            $table->string('lname', 50);
            $table->string('fname', 50);
            $table->string('mname', 50);
            $table->string('contact_num', 50);
            $table->string('email', 50)->unique();
            $table->string('c_level', 50);
            $table->string('c_image', 225);
            $table->timestamps(); // optional: adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselors');
    }

};
