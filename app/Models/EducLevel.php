<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducLevel extends Model
{
    protected $table = 'educ_levels';
    protected $primaryKey = 'e_id';
    public $timestamps = false;
    protected $fillable = ['educ_level', 'updated_at'];
}
