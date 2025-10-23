<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds 'Cancelled' to the appointments.status ENUM.
     */
    public function up()
    {
        if (Schema::hasTable('appointments')) {
            // Adjust ENUM to include Cancelled along with existing values.
            DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Declined','Cancelled','Ongoing','Completed') NOT NULL DEFAULT 'Pending'");
        }
    }

    /**
     * Reverse the migrations.
     * Removes 'Cancelled' from the ENUM (reverts to previous set).
     */
    public function down()
    {
        if (Schema::hasTable('appointments')) {
            DB::statement("ALTER TABLE `appointments` MODIFY `status` ENUM('Pending','Approved','Declined','Ongoing','Completed') NOT NULL DEFAULT 'Pending'");
        }
    }
};
