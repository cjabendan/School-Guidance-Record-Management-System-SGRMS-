<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentLinkRequestStudent extends Model
{
    protected $table = 'parent_link_request_students';
    protected $primaryKey = 'pls_id';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'student_id',
        'student_name',
        'status',
    ];

    public function linkRequest()
    {
        return $this->belongsTo(ParentLinkRequest::class, 'request_id', 'request_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 's_id');
    }
}
