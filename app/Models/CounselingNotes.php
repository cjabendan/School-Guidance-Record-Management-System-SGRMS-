<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingNotes extends Model
{
    use HasFactory;

    protected $table = 'counseling_notes';
    protected $primaryKey = 'note_id';

    protected $fillable = [
        'appointment_id',
        'user_id',
        'observations',
        'recommendations',
        'remarks',
        'follow_up_needed',
        'follow_up_date',
    ];

    protected $casts = [
        'follow_up_needed' => 'boolean',
        'follow_up_date' => 'datetime',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointments::class, 'appointment_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
