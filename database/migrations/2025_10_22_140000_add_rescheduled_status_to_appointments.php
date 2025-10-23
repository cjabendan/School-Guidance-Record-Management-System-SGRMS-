<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('appointments')) return;

        // Add 'Rescheduled' to the appointments.status enum (MySQL specific)
        DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Declined','Cancelled','Rescheduled','Ongoing','Completed') NOT NULL DEFAULT 'Pending'");
    }

    public function down()
    {
        if (!Schema::hasTable('appointments')) return;

        // Remove 'Rescheduled' from the enum (revert to previous set)
        DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Declined','Cancelled','Ongoing','Completed') NOT NULL DEFAULT 'Pending'");
    }
};
