<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStudent extends Model
{
    use HasFactory;

    protected $table = 'appointment_students';

    protected $fillable = [
        'appointment_id',
        'student_user_id',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointments::class, 'appointment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_user_id', 'user_id');
    }
}
