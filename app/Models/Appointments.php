<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';

    protected $fillable = [
        'requester_id',
        'requester_type',
        'counselor_id',
        'appointment_type_id',
        'reason',
        'appointment_datetime',
        'location',
        'status',
        'decline_reason',
        'rescheduled_count',
        'last_rescheduled_at',
        'original_appointment_datetime',
        'reminded',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
        'last_rescheduled_at' => 'datetime',
        'reschedule_proposed_datetime' => 'datetime',
        'original_appointment_datetime' => 'datetime',
    ];

    // Relationships
    public function students()
    {
        // appointment_students table: appointment_id, student_user_id
        // pivot.student_user_id references students.user_id (not students.s_id), so we must tell Eloquent
        // the related model's key is 'user_id' instead of the Student primary key.
        return $this->belongsToMany(
            Student::class,
            'appointment_students',
            'appointment_id',    // this model's pivot key (appointment_students.appointment_id)
            'student_user_id',   // related model pivot key (appointment_students.student_user_id)
            'appointment_id',    // local key on Appointments model
            's_id'               // related key on Student model (students.s_id) — pivot stores s_id
        )
        ->using(\App\Models\AppointmentStudent::class)
        ->withPivot('student_user_id');
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

    public function reschedules()
    {
        // If the historical table exists, use the relationship so history is available
        if (\Schema::hasTable('appointment_reschedules')) {
            return $this->hasMany(\App\Models\AppointmentReschedule::class, 'appointment_id', 'appointment_id')
                        ->orderByDesc('created_at');
        }

        // Otherwise return a lightweight Collection built from fused columns so view code
        // calling `$appointment->reschedules()->first()` still works.
        $collection = collect();

        if ($this->reschedule_reason || $this->reschedule_proposed_datetime || $this->last_rescheduled_at) {
            $item = new \stdClass();
            $item->id = null;
            $item->appointment_id = $this->appointment_id;
            $item->requester_id = $this->reschedule_requester_id ?? $this->requester_id;
            $item->reason = $this->reschedule_reason;
            $item->proposed_datetime = $this->reschedule_proposed_datetime;
            $item->status = $this->status === 'Rescheduled' ? 'Pending' : $this->status;
            $item->admin_notes = null;
            $item->created_at = $this->last_rescheduled_at;
            $item->updated_at = $this->last_rescheduled_at;

            $collection->push($item);
        }

        return $collection;
    }

    // Accessor for requester's full name
    public function getRequesterNameAttribute()
    {
        $user = $this->requester;
        return $user ? trim($user->first_name . ' ' . $user->last_name) : 'N/A';
    }

    
}
