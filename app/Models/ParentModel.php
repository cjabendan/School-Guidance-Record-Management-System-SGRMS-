<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'p_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', // BIGINT(50)
        'name',
        'email',
        'number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function parentStudents()
    {
        return $this->hasMany(ParentStudent::class, 'p_id', 'p_id');
    }


    public function children()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'p_id', 's_id');
    }


    public function linkedStudents()
    {
        return $this->hasMany(ParentStudent::class, 'p_id');
    }
    
    public function linkRequests()
    {
        return $this->hasMany(ParentLinkRequest::class, 'parent_id');
    }

    public function latestLinkRequest()
    {
      
        return $this->hasOne(ParentLinkRequest::class, 'parent_id')
            ->latestOfMany('updated_at'); 
    }

    public function cases()
    {
        return $this->hasManyThrough(
            CaseModel::class,   // replace with your actual case model
            ParentStudent::class,
            'p_id',      // Foreign key on parent_student table
            'student_id', // Foreign key on cases table
            'p_id',      // Local key on parents table
            's_id'       // Local key on parent_student table
        );
    }
}
