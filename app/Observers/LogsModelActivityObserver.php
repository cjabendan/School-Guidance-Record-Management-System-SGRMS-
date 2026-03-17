<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class LogsModelActivityObserver
{
    public function created(Model $model): void
    {
        system_log_model_event('created', $model, [], system_log_snapshot($model));
    }

    public function updated(Model $model): void
    {
        $diff = system_log_diff($model);
        if ($diff === []) {
            return;
        }
        system_log_model_event('updated', $model, $diff, system_log_snapshot($model));
    }

    public function deleted(Model $model): void
    {
        // For soft-deletes, attributes are still available here.
        system_log_model_event('deleted', $model, [], system_log_snapshot($model));
    }

    public function restored(Model $model): void
    {
        system_log_model_event('restored', $model, [], system_log_snapshot($model));
    }
}

