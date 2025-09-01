<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'link',
        'category',
        'image',
        'date_posted',
        'start_datetime',   // new
        'end_datetime',     // new
        'status',
        'created_at',
        'updated_at'
    ];

    // Relationship: An Announcement belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper: check if an event is ongoing
    public function isOngoing()
    {
        if ($this->category !== 'event') return false;
        return now()->between($this->start_datetime, $this->end_datetime);
    }

    // Helper: check if an event is expired
    public function isExpired()
    {
        if ($this->category !== 'event') return false;
        return now()->gt($this->end_datetime);
    }
}
