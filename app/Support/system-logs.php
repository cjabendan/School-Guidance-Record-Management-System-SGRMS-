<?php

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (! function_exists('system_log')) {
    /**
     * Create a system activity log entry.
     *
     * @param  string  $action  e.g. "appointments.created", "auth.login", "users.updated"
     * @param  Model|null  $subject  The affected model (optional)
     * @param  array  $data  Domain payload (changes/snapshot/extra)
     * @param  array  $meta  Request/environment context overrides
     */
    function system_log(string $action, ?Model $subject = null, array $data = [], array $meta = []): ?ActivityLog
    {
        // If migration hasn't run yet, do not hard-fail the app.
        try {
            if (! Schema::hasTable('activity_logs')) {
                return null;
            }
        } catch (\Throwable) {
            return null;
        }

        $actor = Auth::user();

        $request = null;
        try {
            $request = app()->runningInConsole() ? null : request();
        } catch (\Throwable) {
            $request = null;
        }

        $cols = [];
        try {
            $cols = Schema::getColumnListing('activity_logs');
        } catch (\Throwable) {
            $cols = [];
        }
        $has = fn (string $col): bool => in_array($col, $cols, true);

        $dataRedacted = system_log_redact($data);
        $metaMerged = array_merge([
            'ip' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'url' => $request?->fullUrl(),
            'method' => $request?->method(),
        ], system_log_redact($meta));

        $row = [];

        if ($has('action')) {
            $row['action'] = $action;
        }
        if ($has('activity')) {
            // Legacy schema (some DBs still require this to be non-null)
            $row['activity'] = $action;
        }

        if ($has('actor_user_id')) {
            $row['actor_user_id'] = $actor?->id;
        }
        if ($has('user_id')) {
            // Legacy schema
            $row['user_id'] = $actor?->id;
        }

        if ($has('actor_role')) {
            $row['actor_role'] = $actor?->role ?? session('role');
        }
        if ($has('actor_role_id')) {
            $row['actor_role_id'] = session('role_id');
        }

        if ($subject) {
            if ($has('subject_type')) {
                $row['subject_type'] = get_class($subject);
            }
            if ($has('subject_id')) {
                $row['subject_id'] = (string) $subject->getKey();
            }
            if ($has('subject_table')) {
                $row['subject_table'] = $subject->getTable();
            }
        }

        if ($has('data')) {
            $row['data'] = $dataRedacted === [] ? null : json_encode($dataRedacted, JSON_UNESCAPED_UNICODE);
        }
        if ($has('meta')) {
            $row['meta'] = $metaMerged === [] ? null : json_encode($metaMerged, JSON_UNESCAPED_UNICODE);
        }

        if ($has('created_at')) {
            // Some deployments add created_at without DB default.
            $row['created_at'] = now();
        }
        if ($has('timestamp')) {
            // Legacy column
            $row['timestamp'] = now();
        }

        // Prevent a logging failure from breaking user flows.
        try {
            $id = DB::table('activity_logs')->insertGetId($row);
            return ActivityLog::query()->find($id);
        } catch (\Throwable $e) {
            try {
                logger()->error('Activity logging failed', [
                    'action' => $action,
                    'err' => $e->getMessage(),
                ]);
            } catch (\Throwable) {
                // ignore
            }
            return null;
        }
    }
}

if (! function_exists('system_log_model_event')) {
    /**
     * Helper for model observers (created/updated/deleted).
     */
    function system_log_model_event(string $event, Model $model, array $changes = [], array $snapshot = []): ?ActivityLog
    {
        $base = class_basename($model);
        $action = strtolower($base) . '.' . $event;

        return system_log($action, $model, [
            'changes' => $changes,
            'snapshot' => $snapshot,
        ]);
    }
}

if (! function_exists('system_log_diff')) {
    /**
     * Build a {field: {from,to}} diff for updated models.
     */
    function system_log_diff(Model $model, array $ignoreKeys = []): array
    {
        $ignore = array_values(array_unique(array_merge([
            'updated_at',
            'created_at',
            'deleted_at',
            'remember_token',
            'password',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'activation_token',
            'login_token',
        ], $ignoreKeys)));

        $dirty = $model->getChanges(); // only changed fields after save
        $diff = [];

        foreach ($dirty as $key => $to) {
            if (in_array($key, $ignore, true)) {
                continue;
            }

            $from = $model->getOriginal($key);
            $diff[$key] = [
                'from' => system_log_redact_value($key, $from),
                'to' => system_log_redact_value($key, $to),
            ];
        }

        return $diff;
    }
}

if (! function_exists('system_log_snapshot')) {
    /**
     * Snapshot a model's attributes with sensitive fields removed.
     */
    function system_log_snapshot(Model $model, array $ignoreKeys = []): array
    {
        $ignore = array_values(array_unique(array_merge([
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'activation_token',
            'login_token',
        ], $ignoreKeys)));

        $attrs = $model->getAttributes();
        foreach ($ignore as $k) {
            unset($attrs[$k]);
        }

        return system_log_redact($attrs);
    }
}

if (! function_exists('system_log_redact')) {
    /**
     * Recursively redact sensitive keys in arrays.
     */
    function system_log_redact(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'current_password',
            'new_password',
            'confirm_password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes',
            'activation_token',
            'login_token',
            'token',
            'secret',
            'otp',
        ];

        $out = [];
        foreach ($data as $k => $v) {
            if (is_string($k) && in_array($k, $sensitiveKeys, true)) {
                $out[$k] = '[REDACTED]';
                continue;
            }

            if (is_array($v)) {
                $out[$k] = system_log_redact($v);
            } else {
                $out[$k] = system_log_redact_value((string) $k, $v);
            }
        }
        return $out;
    }
}

if (! function_exists('system_log_redact_value')) {
    function system_log_redact_value(string $key, mixed $value): mixed
    {
        $lower = strtolower($key);
        if (str_contains($lower, 'password') || str_contains($lower, 'token') || str_contains($lower, 'secret')) {
            return $value === null ? null : '[REDACTED]';
        }
        return $value;
    }
}

