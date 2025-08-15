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
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'contact_num',
        'sex',
        'bod',
        'address',
        'profile_image',
        'password',
        'role',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
    // Uncomment and implement Admin model if it exists
    // public function admin()
    // {
    //     return $this->hasOne(Admin::class, 'user_id');
    // }
}
