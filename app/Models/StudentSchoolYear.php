<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSchoolYear extends Model
{
    protected $table = 'student_schoolyear';
    protected $fillable = [
        'student_id',
        'school_year_id',
        'year_level',
        'section',
        'status',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 's_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }
}
