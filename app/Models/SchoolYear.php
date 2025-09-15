<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $table = 'school_year';
    protected $fillable = [
        'year_label',
        'is_active',
        'start_date',
        'end_date',
    ];
}
