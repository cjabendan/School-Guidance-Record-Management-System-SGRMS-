<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Livewire\Settings\BackupDatabase;
use App\Models\SystemSetting;
use Carbon\Carbon;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'backup:run';
    protected $description = 'Run the database backup based on saved frequency';

    public function handle()
    {
        $this->info('Checking backup frequency...');

        // Get the saved frequency from system_settings
        $frequencySetting = SystemSetting::where('key', 'backup_frequency')->first();
        $frequency = $frequencySetting ? $frequencySetting->value : 'manual';

        $now = Carbon::now();

        $shouldRun = false;

        if ($frequency === 'weekly' && $now->isSunday()) {
            $shouldRun = true;
        }

        if ($frequency === 'monthly' && $now->day === 1) {
            $shouldRun = true;
        }

        if ($frequency === 'manual') {
            $this->info('Frequency is manual. Backup not run automatically.');
            return;
        }

        if ($shouldRun) {
            $this->info('Starting automatic backup...');
            BackupDatabase::autoBackup();
            $this->info('Backup finished.');
        } else {
            $this->info('Today is not scheduled for backup. Nothing to do.');
        }
    }
}
