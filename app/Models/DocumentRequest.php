<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRequest extends Model
{
    protected $table = 'document_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false;
    protected $dates = ['requested_at'];


    protected $fillable = [
        'parent_id',
        'status',
        'requested_at',
        'document_type', // e.g., 'Good Moral', 'Certificate of Enrollment'
        'rejection_reason'
    ];

    // Relationship: The parent who made the request
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id', 'p_id');
    }

    // Relationship: Students linked to this request (pivot table)
    public function students()
    {
        return $this->hasMany(DocumentRequestStudent::class, 'request_id', 'request_id');
    }
}
