<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotSession extends Model
{
    protected $fillable = ['user_id', 'session_id', 'messages'];
    protected $casts = ['messages' => 'array'];
}
