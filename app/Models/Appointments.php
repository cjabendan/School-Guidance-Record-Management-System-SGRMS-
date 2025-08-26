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
        'requester_id', // uses user ID
        'requester_type',
        'student_id',
        'counselor_id', // either admin or counselor (use user ID)
        'appointment_type',
        'appointment_datetime',
        'location',
        'status',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function notes()
    {
        return $this->hasMany(CounselingNotes::class, 'appointment_id');
    }

   
     public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    // Accessor for requester's full name
    public function getRequesterNameAttribute()
    {
        $user = $this->requester;
        return $user ? trim($user->first_name . ' ' . $user->last_name) : 'N/A';
    }
}
