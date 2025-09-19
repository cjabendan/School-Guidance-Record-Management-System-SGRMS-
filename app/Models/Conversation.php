<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one',
        'user_two',
    ];

    /**
     * Messages in this conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id', 'id')->orderBy('created_at', 'asc');
    }

    /**
     * Get the other participant of the conversation
     */
    public function getOtherParticipant($userId)
    {
        if ($this->user_one == $userId) {
            return User::find($this->user_two);
        }
        return User::find($this->user_one);
    }

    /**
     * Users participating in the conversation
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'users', 'id', 'id')
                    ->whereIn('id', [$this->user_one, $this->user_two]);
    }
}
