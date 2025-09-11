<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequestStudent extends Model
{
    protected $table = 'drs';
    protected $primaryKey = 'drs_id';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        's_id'
    ];

    // Relationship: The request this pivot belongs to
    public function request()
    {
        return $this->belongsTo(DocumentRequest::class, 'request_id', 'request_id');
    }

    // Relationship: The student this pivot links to
    public function student()
    {
        return $this->belongsTo(Student::class, 's_id', 's_id');
    }
}
