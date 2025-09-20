<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class HeadMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('user_one', $user->id)
            ->orWhere('user_two', $user->id)
            ->with(['messages.sender', 'messages.receiver'])
            ->get()
            ->sortByDesc(function ($conv) {
                // Get the latest message's created_at, or a very old date if no messages
                return optional($conv->messages->last())->created_at ?? now()->subYears(100);
            })
            ->values(); // Re-index the collection

        return view('Head.messages', compact('conversations', 'user'));
    }


    /**
     * Search users for new conversation
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();

        $users = User::whereIn('role', ['counselor', 'parent'])
            ->where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                    ->orWhere('last_name', 'like', "%$query%");
            })
            ->limit(5)
            ->get();

        return response()->json($users);
    }


    /**
     * Start or get existing conversation
     */
    public function startConversation(Request $request, $receiverId)
    {
        $user = Auth::user();

        // 1. Check if conversation exists
        $conversation = Conversation::where(function ($q) use ($user, $receiverId) {
            $q->where('user_one', $user->id)->where('user_two', $receiverId);
        })->orWhere(function ($q) use ($user, $receiverId) {
            $q->where('user_one', $receiverId)->where('user_two', $user->id);
        })->with(['messages.sender', 'messages.receiver'])
            ->first();

        // 2. Create new conversation if none exists
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => $user->id,
                'user_two' => $receiverId,
            ]);
        }

        // 3. Optional: send first message if passed in request
        $message = null;
        if ($request->filled('msg')) {
            $message = $conversation->messages()->create([
                'sender_id'   => $user->id,
                'receiver_id' => $receiverId,
                'msg'         => $request->msg,
                'status'      => 'sent',
            ]);

            $conversation->load(['messages.sender', 'messages.receiver']);
        }

        // 4. Get the other user
        $otherUser = User::find($receiverId);

        return response()->json([
            'conversation'  => $conversation,
            'messages'      => $conversation->messages->map(fn($msg) => [
                'id'         => $msg->id,
                'msg'        => $msg->msg,
                'sender_id'  => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'created_at' => $msg->created_at->toIso8601String(), // ✅
            ]),
            'firstMessage'  => $message ? [
                'id'         => $message->id,
                'msg'        => $message->msg,
                'sender_id'  => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->toIso8601String(), // ✅
            ] : null,
            'otherUser'     => $otherUser,
            'currentUserId' => $user->id,
        ]);
    }

    /**
     * Fetch an existing conversation and its messages (for JS)
     */
    public function fetchConversation($conversationId)
    {
        $user = Auth::user();

        $conversation = Conversation::with(['messages.sender', 'messages.receiver'])
            ->findOrFail($conversationId);

        $otherUserId = $conversation->user_one == $user->id
            ? $conversation->user_two
            : $conversation->user_one;

        $otherUser = User::find($otherUserId);

        return response()->json([
            'conversation'  => $conversation,
            'messages'      => $conversation->messages->map(fn($msg) => [
                'id'         => $msg->id,
                'msg'        => $msg->msg,
                'sender_id'  => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'created_at' => $msg->created_at->toIso8601String(), // ✅
            ]),
            'otherUser'     => $otherUser,
            'currentUserId' => $user->id,
        ]);
    }


    /**
     * Fetch sidebar conversation list (for JS)
     */
    public function sidebarList()
{
    $user = Auth::user();
    $conversations = Conversation::where('user_one', $user->id)
        ->orWhere('user_two', $user->id)
        ->with(['messages' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])
        ->get()
        ->sortByDesc(function ($conv) {
            // Still fallback: latest msg (sent or received)
            return optional($conv->messages->last())->created_at ?? now()->subYears(100);
        })
        ->values();

    $result = $conversations->map(function ($conv) use ($user) {
        $other = $conv->user_one == $user->id ? $conv->user_two : $conv->user_one;
        $otherUser = User::find($other);

        $lastMsg = $conv->messages->last();

        // 🛠 Find the last message *received* by this user
        $lastReceived = $conv->messages
            ->where('receiver_id', $user->id)
            ->last();

        return [
            'id' => $conv->id,
            'otherUser' => [
                'id' => $otherUser->id,
                'first_name' => $otherUser->first_name,
                'last_name' => $otherUser->last_name,
                'profile_image' => $otherUser->profile_image,
            ],
            'lastMessage' => $lastMsg ? [
                'id' => $lastMsg->id,
                'msg' => $lastMsg->msg,
                'sender_id' => $lastMsg->sender_id,
                'receiver_id' => $lastMsg->receiver_id,
                'status' => $lastMsg->status,
                'created_at' => $lastMsg->created_at->toIso8601String(),
            ] : null,
            'lastReceived' => $lastReceived ? [
                'id' => $lastReceived->id,
                'msg' => $lastReceived->msg,
                'sender_id' => $lastReceived->sender_id,
                'receiver_id' => $lastReceived->receiver_id,
                'status' => $lastReceived->status,
                'created_at' => $lastReceived->created_at->toIso8601String(),
            ] : null,
        ];
    });

    return response()->json([
        'conversations' => $result,
        'currentUserId' => $user->id,
    ]);
}

   
    /**
     * Send a message in an existing conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $user = Auth::user();

        $conversation = Conversation::with(['messages.sender', 'messages.receiver'])
            ->findOrFail($conversationId);

        // Determine the receiver
        $receiverId = $conversation->user_one == $user->id
            ? $conversation->user_two
            : $conversation->user_one;

        // Save message
        $message = $conversation->messages()->create([
            'sender_id'   => $user->id,
            'receiver_id' => $receiverId,
            'msg'         => $request->msg,
            'status'      => 'sent',
        ]);

        $conversation->load(['messages.sender', 'messages.receiver']);
        $otherUser = User::find($receiverId);

        return response()->json([
            'conversation'  => $conversation,
            'messages'      => $conversation->messages->map(fn($msg) => [
                'id'         => $msg->id,
                'msg'        => $msg->msg,
                'sender_id'  => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'created_at' => $msg->created_at->toIso8601String(), // ✅
            ]),
            'otherUser'     => $otherUser,
            'currentUserId' => $user->id,
        ]);
    }


    /**
     * Mark a message as read
     */


    public function markAsRead($conversationId)
    {
        $user = Auth::user();
        Message::where('conversation_id', $conversationId)
            ->where('receiver_id', $user->id)
            ->where('status', 'sent')
            ->update(['status' => 'read']);

        return response()->json(['success' => true]);
    }
}
