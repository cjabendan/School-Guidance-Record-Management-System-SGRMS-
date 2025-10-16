<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'message',
        'timestamp',
        'is_read',
        'user_id',
        'related_id',
    ];

    public function relatedAnnouncement()
    {
        return $this->belongsTo(\App\Models\Announcements::class, 'related_id');
    }
}
