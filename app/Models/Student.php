<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 's_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        's_id',
        'user_id',
        'father_name',
        'mother_name',
        'guardian_name',
        'relationship',
        'guardian_contact',
        'guardian_email',
        'religion',
        'civil_status',
    ];

    // ✅ A student belongs to one user (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // yearLevel relationship removed (y_id no longer exists)

    // ✅ Many-to-Many with parents (pivot has relation)
    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'parent_student', 's_id', 'p_id')
            ->withPivot('relation');
    }

    // ✅ Reverse of ParentModel->children
    public function parentLinks()
    {
        return $this->hasMany(ParentStudent::class, 's_id', 's_id');
    }

    // ✅ Link requests (extra feature you added)
    public function linkRequests()
    {
        return $this->hasManyThrough(
            ParentLinkRequest::class,
            ParentLinkRequestStudent::class,
            'student_id',   
            'request_id',  
            's_id',      
            'request_id'  
        );
    }

    public function appointments()
    {
     
        return $this->belongsToMany(
            Appointments::class,
            'appointment_students',
            'student_user_id',   
            'appointment_id',  
            's_id',              
            'appointment_id'     
        )
            ->using(\App\Models\AppointmentStudent::class)
            ->withPivot('student_user_id');
    }

    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'student_id', 's_id');
    }

    public function studentSchoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function schoolYear()
    {
        return $this->hasOne(StudentSchoolYear::class, 'student_id', 's_id')->latestOfMany();
    }
}
