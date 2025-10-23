<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentReschedule extends Model
{
    protected $table = 'appointment_reschedules';

    protected $fillable = [
        'appointment_id',
        'requester_id',
        'reason',
        'proposed_datetime',
        'status',
        'admin_notes',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointments::class, 'appointment_id', 'appointment_id');
    }

    public function requester()
    {
        return $this->belongsTo(\App\Models\User::class, 'requester_id');
    }
}
