<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id('p_id'); // Primary key
            $table->string('guardian_name', 100);
            $table->string('relationship', 50);
            $table->string('contact_num', 20);
            $table->string('email', 50);
            // Optional: timestamps if you want created_at and updated_at fields
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};

