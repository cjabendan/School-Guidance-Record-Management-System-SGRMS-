<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show inbox / conversations
     */
    public function inbox()
    {
        $user = Auth::user();

        // Get conversations where user is participant
        $conversations = Conversation::where('user_one', $user->id)
                            ->orWhere('user_two', $user->id)
                            ->with(['messages.sender', 'messages.receiver'])
                            ->get();

        return view('chat.inbox', compact('conversations', 'user'));
    }

    /**
     * Send message to user
     */
    public function send(Request $request, $receiverId)
    {
        $sender = Auth::user();
        $receiver = User::findOrFail($receiverId);

        if (!$this->canMessage($sender, $receiver)) {
            abort(403, "You cannot message this user");
        }

        // Check if conversation exists
        $conversation = Conversation::where(function($q) use ($sender, $receiver){
            $q->where('user_one', $sender->id)->where('user_two', $receiver->id);
        })->orWhere(function($q) use ($sender, $receiver){
            $q->where('user_one', $receiver->id)->where('user_two', $sender->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => $sender->id,
                'user_two' => $receiver->id,
            ]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'msg' => $request->msg,
            'status' => 'sent',
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'msg' => $message->msg,
            'created_at' => $message->created_at,
            'receiver_name' => $receiver->first_name.' '.$receiver->last_name,
            'receiver_image' => asset('storage/'.$receiver->profile_image)
        ]);
    }

    /**
     * Search users for new chat
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $users = User::query()
            ->when($user->role === 'admin', fn($q) => $q->whereIn('role', ['counselor','parent']))
            ->when($user->role === 'counselor', fn($q) => $q->whereIn('role', ['admin','parent']))
            ->when($user->role === 'parent', fn($q) => $q->whereIn('role', ['admin','counselor']))
            ->where(function($q) use ($query){
                $q->where('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%");
            })
            ->where('status', 'active')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Role-based permission
     */
    protected function canMessage($sender, $receiver): bool
    {
        return match($sender->role) {
            'admin' => in_array($receiver->role, ['counselor','parent']),
            'counselor' => in_array($receiver->role, ['admin','parent']),
            'parent' => in_array($receiver->role, ['admin','counselor']),
            default => false,
        };
    }
}
