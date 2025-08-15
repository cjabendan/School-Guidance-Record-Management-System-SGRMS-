<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admins extends Model
{

    protected $table = 'admins';
    protected $fillable = ['user_id', 'role'];

    // Relationship: Admin belongs to one User (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
