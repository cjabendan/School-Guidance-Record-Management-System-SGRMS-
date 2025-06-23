<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    protected $table = 'counselors';
    protected $primaryKey = 'c_id'; // if your PK is not 'id'
    public $timestamps = false; // set to true if you have created_at/updated_at

    protected $fillable = [
        'lname', 'fname', 'mname', 'email', 'contact_num', 'c_level', 'c_image'
    ];
}
