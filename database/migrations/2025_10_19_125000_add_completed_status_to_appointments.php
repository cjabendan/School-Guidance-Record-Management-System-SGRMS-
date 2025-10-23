<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('appointments')) return;

        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $row = DB::selectOne("SHOW COLUMNS FROM `appointments` LIKE 'status'");
            if ($row && isset($row->Type) && str_starts_with($row->Type, 'enum(')) {
                preg_match('/^enum\((.*)\)$/i', $row->Type, $matches);
                if (isset($matches[1])) {
                    $values = array_map(function($v){ return trim($v, "'\""); }, explode(',', $matches[1]));
                    if (!in_array('Completed', $values)) {
                        $values[] = 'Completed';
                        $newEnum = "enum('" . implode("','", $values) . "')";
                        DB::statement("ALTER TABLE `appointments` MODIFY `status` {$newEnum} DEFAULT 'Pending'");
                    }
                }
            }
        }
    }

    public function down()
    {
        if (!Schema::hasTable('appointments')) return;

        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $row = DB::selectOne("SHOW COLUMNS FROM `appointments` LIKE 'status'");
            if ($row && isset($row->Type) && str_starts_with($row->Type, 'enum(')) {
                preg_match('/^enum\((.*)\)$/i', $row->Type, $matches);
                if (isset($matches[1])) {
                    $values = array_map(function($v){ return trim($v, "'\""); }, explode(',', $matches[1]));
                    if (in_array('Completed', $values)) {
                        $values = array_values(array_filter($values, function($v){ return $v !== 'Completed'; }));
                        $newEnum = "enum('" . implode("','", $values) . "')";
                        DB::statement("ALTER TABLE `appointments` MODIFY `status` {$newEnum} DEFAULT 'Pending'");
                    }
                }
            }
        }
    }
};
