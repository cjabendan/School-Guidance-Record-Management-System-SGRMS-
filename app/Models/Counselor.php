<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    protected $table = 'counselors';
    protected $primaryKey = 'c_id';
    public $timestamps = false;

    protected $fillable = [
        'lname',
        'fname',
        'mname',
        'email',
        'contact_num',
        'c_level',
        'c_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
