<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'google_id',
        'google_email',
        'google_token',
        'google_refresh_token',
        'contact_num',
        'sex',
        'bod',
        'address',
        'profile_image',
        'password',
        'role',
        'profile_image',
        'status',
        'activation_token',
        'activation_token_expires_at',
    ];

    protected $hidden = [
        'password',
        'google_token',
        'google_refresh_token',
        'remember_token',
        'activation_token',
        'login_token',
    ];

    // === Relationships ===

    // If this user is a student
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // If this user is a counselor
    public function counselor()
    {
        return $this->hasOne(Counselor::class, 'user_id');
    }

    // If this user is a parent

    public function parentProfile()
    {

        return $this->hasOne(ParentModel::class, 'user_id');
    }

    // If this user is an admin
   
     public function admin()
    {
        return $this->hasOne(Admins::class, 'user_id');
     }
}
