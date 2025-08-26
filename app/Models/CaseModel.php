<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    use HasFactory;

    protected $table = 'cases';
    protected $primaryKey = 'case_id';
    public $timestamps = false; // filed_date/time handled separately

    protected $fillable = [
        'case_type_id',
        'presenting_problem',
        'description',
        'severity',
        'witnesses',
        'investigation_notes',
        'evidence',
        'filed_date',
        'filed_time',
        'reported_by_admin_id',
        'status',
        'action_taken',
        'resolution_notes',
        'resolved_date',
        'follow_up_date',
    ];

    // Case belongs to a CaseType
    public function caseType()
    {
        return $this->belongsTo(CaseType::class, 'case_type_id', 'type_id');
    }

    // Case belongs to an Admin (reporter)
    public function admin()
    {
        return $this->belongsTo(Admins::class, 'reported_by_admin_id', 'a_id');
    }

    // Case has many students (many-to-many)
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'case_students',      // pivot table
            'case_id',            // FK in pivot for this model
            'student_id'          // FK in pivot for related model
        );
    }
}
