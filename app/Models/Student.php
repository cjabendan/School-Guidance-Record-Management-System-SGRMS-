<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 's_id';
    public $timestamps = false;

    protected $fillable = [
        'id_num', 'suffix', 'lname', 'fname', 'mname', 'sex', 'bod', 'address',
        'mobile_num', 'email', 'educ_level', 'year_level', 'section', 'program',
        's_image', 'previous_school', 'status', 'parent_id'
    ];
}

