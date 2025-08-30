<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 's_id';
    public $timestamps = false;

    protected $fillable = [
        's_id',
        'user_id',
        'y_id',
        'bod',
        'address',
        'mobile_num',
        'email',
        'section',
        'program',
        's_image',
        'previous_school',
        'status',
        'parent_id',
        'religion',
        'civil_status'
    ];

    // Relationship: A student belongs to one user (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: Many-to-Many with parents
    public function parents()
    {
        return $this->belongsToMany(
            ParentModel::class,
            'parent_student',
            'student_id',
            'parent_id'
        )->withTimestamps();
    }


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
}
