<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CaseStudent extends Pivot
{
    protected $table = 'case_students';
    public $incrementing = false;
    public $timestamps = false;
}







