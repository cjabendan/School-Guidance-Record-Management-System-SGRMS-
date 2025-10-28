<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingNotes extends Model
{
    use HasFactory;

    protected $primaryKey = 'note_id';
    public $incrementing = true; // or false if note_id is not auto-increment integer
    protected $keyType = 'int';

    protected $fillable = [
        'student_name',
        'user_id',
        'observations',
        'remarks',
        'recommendations',
        'follow_up_needed',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_needed' => 'boolean',
        'follow_up_date' => 'datetime',
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_name', 's_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
