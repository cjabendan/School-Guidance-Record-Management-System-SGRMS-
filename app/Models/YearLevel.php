<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YearLevel extends Model
{
    protected $table = 'year_levels';
    protected $primaryKey = 'y_id';
    public $timestamps = false;
    protected $fillable = ['e_id', 'year_level', 'updated_at'];
}
