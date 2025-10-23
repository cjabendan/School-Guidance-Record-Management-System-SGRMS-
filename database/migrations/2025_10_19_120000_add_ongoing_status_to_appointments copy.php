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

        // Attempt to detect enum values for MySQL
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver === 'mysql') {
            $row = DB::selectOne("SHOW COLUMNS FROM `appointments` LIKE 'status'");
            if ($row && isset($row->Type) && str_starts_with($row->Type, 'enum(')) {
                // parse existing enum values
                preg_match('/^enum\((.*)\)$/i', $row->Type, $matches);
                if (isset($matches[1])) {
                    $values = array_map(function($v){ return trim($v, "'\""); }, explode(',', $matches[1]));
                    if (!in_array('Ongoing', $values)) {
                        $values[] = 'Ongoing';
                        $newEnum = "enum('" . implode("','", $values) . "')";
                        DB::statement("ALTER TABLE `appointments` MODIFY `status` {$newEnum} DEFAULT 'Pending'");
                    }
                }
            }
        } else {
            // For other DBs, no-op. Consider manual migration.
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
                    if (in_array('Ongoing', $values)) {
                        $values = array_values(array_filter($values, function($v){ return $v !== 'Ongoing'; }));
                        $newEnum = "enum('" . implode("','", $values) . "')";
                        DB::statement("ALTER TABLE `appointments` MODIFY `status` {$newEnum} DEFAULT 'Pending'");
                    }
                }
            }
        }
    }
};
