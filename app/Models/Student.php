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
        'section',
        'father_name',
        'mother_name',
        'guardian_name',
        'relationship',
        'guardian_contact',
        'guardian_email',
        'program',
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
            'student_id',   // FK on parent_link_request_students
            'request_id',   // FK on parent_link_requests
            's_id',         // Local key on students
            'request_id'    // Local key on parent_link_request_students
        );
    }

    // ✅ Appointments (many-to-many)
    public function appointments()
    {
        return $this->belongsToMany(Appointments::class, 'appointment_students', 'student_user_id', 'appointment_id');
    }

    // ✅ Cases (reverse of ParentModel->cases, assuming you have CaseModel)
    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'student_id', 's_id');
    }
}
