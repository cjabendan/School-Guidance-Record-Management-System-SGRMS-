<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentType extends Model
{
    use HasFactory;

    protected $table = 'appointment_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type_name',
        'description',
    ];

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'appointment_type_id');
    }
}
