<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'grade', 'section'];

    public function users()
    {
        return $this->hasMany(User::class, 'student_id'); // optional, if needed
    }
}

