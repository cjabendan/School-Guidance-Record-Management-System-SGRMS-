<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'p_id';
    public $timestamps = false;

    protected $fillable = [
      
        'relationship',
    ];

    // Relationship: Guardian` belongs to one User (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: A parent may have multiple students
    public function students()
    {
        // Assuming students table has a parent_id column referencing parents.p_id
        return $this->hasMany(Student::class, 'parent_id', 'p_id');
    }
}
