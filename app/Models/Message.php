<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Conversation;

class Message extends Model
{
    use HasFactory;

    protected $primaryKey = 'msg_id';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'msg',
        'status',
    ];

    /**
     * Conversation this message belongs to
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    /**
     * Sender of the message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    /**
     * Receiver of the message
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
}
