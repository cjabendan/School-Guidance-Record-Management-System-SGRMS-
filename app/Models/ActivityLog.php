<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        // Legacy columns
        'activity',
        'timestamp',
        'user_id',

        // New columns (preferred)
        'action',
        'actor_user_id',
        'actor_role',
        'actor_role_id',
        'subject_type',
        'subject_id',
        'subject_table',
        'data',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'meta' => 'array',
        'created_at' => 'datetime',
        'timestamp' => 'datetime',
    ];
}

