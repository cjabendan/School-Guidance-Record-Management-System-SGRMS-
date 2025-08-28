<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    protected $table = 'parent_student';
    protected $primaryKey = 'ps_id';
    public $timestamps = false;

    protected $fillable = [
        'p_id',
        's_id',
        'relation'
    ];

    public function parent()
    {
        return $this->belongsTo(Parent::class, 'p_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 's_id', 's_id');
    }
}
