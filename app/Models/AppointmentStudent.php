<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AppointmentStudent extends Pivot
{
    // pivot table doesn't have an auto-incrementing PK
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'appointment_students';

    protected $fillable = [     
        'appointment_id',
        'student_user_id',
    ];

    // Relationships (optional helpers)
    public function appointment()
    {
        return $this->belongsTo(Appointments::class, 'appointment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_user_id', 'user_id');
    }
}
