<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    /**
     * Start or find an existing conversation between two users
     */
    public function startConversation(User $userOne, User $userTwo): Conversation
    {
        // Ensure smaller ID is user_one for consistency
        $min = min($userOne->id, $userTwo->id);
        $max = max($userOne->id, $userTwo->id);

        return Conversation::firstOrCreate(
            ['user_one' => $min, 'user_two' => $max],
            ['created_at' => now()]
        );
    }

    /**
     * Send a message between two users (auto-creates conversation if missing)
     */
    public function sendMessage(User $sender, User $receiver, string $msg, $conversationId = null)
    {
        if (!$conversationId) {
            $conversation = Conversation::where(function ($q) use ($sender, $receiver) {
                $q->where('user_one', $sender->id)->where('user_two', $receiver->id);
            })
                ->orWhere(function ($q) use ($sender, $receiver) {
                    $q->where('user_one', $receiver->id)->where('user_two', $sender->id);
                })
                ->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'user_one' => $sender->id,
                    'user_two' => $receiver->id,
                ]);
            }
            $conversationId = $conversation->id;
        }

        return Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'msg' => $msg,
            'status' => 'unread',
        ]);
    }

    /**
     * Mark all messages from $otherUser as read in a conversation
     */
    public function markConversationAsRead(Conversation $conversation, User $currentUser): void
    {
        $conversation->messages()
            ->where('receiver_id', $currentUser->id)
            ->where('status', 'unread')
            ->update([
                'status'  => 'read',
                'read_at' => now(),
            ]);
    }

    /**
     * Get all conversations for a user (with last message + other user eager loaded)
     */
    public function getUserConversations(User $user)
    {
        return Conversation::with(['lastMessage', 'userOne', 'userTwo'])
            ->withUser($user->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
