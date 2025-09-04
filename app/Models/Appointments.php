<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'requester_id',
        'requester_type',
        'student_id',
        'counselor_id',
        'appointment_type_id',
        'reason',
        'appointment_datetime',
        'location',
        'status',
        'rescheduled_count',
        'last_rescheduled_at',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
        'last_rescheduled_at' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(AppointmentType::class, 'appointment_type_id');
    }

    public function notes()
    {
        return $this->hasMany(CounselingNotes::class, 'appointment_id');
    }

    // Accessor for requester's full name
    public function getRequesterNameAttribute()
    {
        $user = $this->requester;
        return $user ? trim($user->first_name . ' ' . $user->last_name) : 'N/A';
    }
}
