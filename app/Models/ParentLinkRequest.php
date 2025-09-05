<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentLinkRequest extends Model
{
    protected $table = 'parent_link_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false; 

    protected $fillable = [
        'parent_id',
        'status',
        'requested_at',
        'email',
        'number'
    ];

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function students()
    {
        return $this->hasMany(ParentLinkRequestStudent::class, 'request_id');
    }
}
