<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\Livewire;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;


class BackupDatabase extends Component
{
    public $message = '';
    public $downloadPath = null;
    public $frequency = 'weekly';
    public $nextBackup = null;
    public $backupStatus = null;

    public function mount()
    {
        $this->frequency = SystemSetting::firstOrCreate(
            ['key' => 'backup_frequency'],
            ['value' => 'weekly']
        )->value;

        $this->calculateNextBackup();
    }



    public function updatedFrequency($value)
    {
        // Save to database
        SystemSetting::updateOrCreate(
            ['key' => 'backup_frequency'],
            ['value' => $value]
        );

        $this->message = "Backup frequency set to {$value}.";
        $this->calculateNextBackup();
    }

    public function calculateNextBackup()
    {
        $now = now();

        if ($this->frequency === 'weekly') {
            $next = $now->copy()->next(Carbon::SUNDAY)->setTime(2, 0, 0);
        } elseif ($this->frequency === 'monthly') {
            $next = $now->copy()->firstOfMonth()->setTime(2, 0, 0)->addMonth();
        } else {
            $next = null;
        }

        $this->nextBackup = $next ? $next->timestamp : null;
    }

    public function backup()
    {
        $this->backupStatus = 'running';
        $this->message = 'Creating database backup...';
        $this->downloadPath = null;

        // Always manual when the button is clicked
        $frequency = 'manual';

        // Clean, readable timestamp: 10-16-25_12-37AM
        $formattedDate = now()->format('m-d-y_h-ia');
        $filename = "{$frequency}_backup_{$formattedDate}.sql";

        $storagePath = storage_path("app/public/backups");
        $path = $storagePath . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($storagePath)) mkdir($storagePath, 0755, true);

        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE');

        $command = "\"$mysqldump\" --host=$dbHost --port=$dbPort --user=$dbUser --password=$dbPass $dbName > \"$path\"";

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            $this->backupStatus = 'failed';
            $this->message = "Backup failed. Please check firewall, MySQL credentials, or path.";
        } else {
            $this->backupStatus = 'completed';
            $this->downloadPath = asset('storage/backups/' . $filename);
            $this->message = "Manual backup complete. You can now download the file.";
        }

        $this->calculateNextBackup();
    }

    public static function autoBackup()
    {
        $instance = new self();

        $frequencySetting = SystemSetting::where('key', 'backup_frequency')->first();
        $frequency = $frequencySetting ? $frequencySetting->value : 'manual';

        $formattedDate = now()->format('m-d-y_h-ia');
        $filename = "{$frequency}_backup_{$formattedDate}.sql";

        $storagePath = storage_path("app/public/backups");
        $path = $storagePath . DIRECTORY_SEPARATOR . $filename;

        if (!is_dir($storagePath)) mkdir($storagePath, 0755, true);

        $mysqldump = 'C:\xampp\mysql\bin\mysqldump.exe';
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE');

        $command = "\"$mysqldump\" --host=$dbHost --port=$dbPort --user=$dbUser --password=$dbPass $dbName > \"$path\"";

        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            Log::error("Auto backup failed at {$formattedDate}");
        } else {
            Log::info("{$frequency} backup completed: {$filename}");
        }
    }

    public function resetBackup()
    {
        $this->downloadPath = null;
        $this->backupStatus = null;
        $this->message = '';
    }

    public function afterDownload($path)
    {
        $this->dispatch('download-file', url: $path);
        $this->resetBackup();   
    }


    #[On('resetBackupNow')]
    public function resetBackupNow()
    {
        $this->resetBackup();
    }


    public function render()
    {
        return view('livewire.settings.backup-database');
    }
}
