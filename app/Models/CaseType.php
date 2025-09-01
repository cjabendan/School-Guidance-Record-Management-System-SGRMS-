<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseType extends Model
{
    use HasFactory;

    protected $table = 'case_types';
    protected $primaryKey = 'type_id';
    public $timestamps = false; // No created_at / updated_at columns

    protected $fillable = [
        'type_name',
        'description',
    ];

    // One CaseType has many Cases
    public function cases()
    {
        return $this->hasMany(CaseModel::class, 'case_type_id', 'type_id');
    }
}
  