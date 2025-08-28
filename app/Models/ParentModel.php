<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    protected $table = 'parents';
    protected $primaryKey = 'p_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
    ];

    // A parent belongs to one User (for personal info like email/contact_num)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A parent may have multiple students (Many-to-Many via pivot table)
    public function students()
    {
        return $this->belongsToMany(
            Student::class,       // target model
            'parent_student',     // pivot table
            'p_id',
            's_id'
        )->withPivot('relation');
    }

    public function linkRequests()
    {
        return $this->hasMany(ParentLinkRequest::class, 'parent_id');
    }

}
