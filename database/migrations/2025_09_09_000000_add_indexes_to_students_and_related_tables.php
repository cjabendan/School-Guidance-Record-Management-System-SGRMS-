<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToStudentsAndRelatedTables extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index('s_id');
            $table->index('user_id');
            $table->index('y_id');
            $table->index('status');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('status');
        });
        Schema::table('year_levels', function (Blueprint $table) {
            $table->index('e_id');
        });
        Schema::table('case_students', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('case_id');
        });
        Schema::table('cases', function (Blueprint $table) {
            $table->index('archived');
            $table->index('severity');
            $table->index('filed_date');
        });
        // Add more indexes for other tables as needed
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['s_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['y_id']);
            $table->dropIndex(['status']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['status']);
        });
        Schema::table('year_levels', function (Blueprint $table) {
            $table->dropIndex(['e_id']);
        });
        Schema::table('case_students', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['case_id']);
        });
        Schema::table('cases', function (Blueprint $table) {
            $table->dropIndex(['archived']);
            $table->dropIndex(['severity']);
            $table->dropIndex(['filed_date']);
        });
        // Drop more indexes for other tables as needed
    }
}
