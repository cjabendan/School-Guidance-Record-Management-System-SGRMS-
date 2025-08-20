<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcements extends Model
{
    //
    protected $table = 'announcements';

     protected $fillable = [
        'user_id',
        'title',
        'description',
        'body',
        'link',
        'category',
        'image',
        'date_posted',
        'status',
        'created_at',
        'updated_at'
    ];

    // Relationship: An Announcement belongs to one User (personal info)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
