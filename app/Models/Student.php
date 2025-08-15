<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 's_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'id_num',
        'educ_level',
        'year_level',
        'section',
        'program',
        'status',
        'parent_id',
        'previous_school_address',
        'religion',
        'civil_status'
    ];

    // Relationship: A student belongs to one user (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: A student belongs to one parent
    public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id'); // adjust model name if needed
    }
}
