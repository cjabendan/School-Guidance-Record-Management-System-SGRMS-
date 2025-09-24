<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use App\Events\MessageSent;

class Chat extends Component
{
    public $users;
    public $newMessage;
    public $messages;
    public $authId;
    public $loginID;
    public $newChatMode = false;
    public $searchQuery = '';
    public $searchResults = [];
    public $selectedUser = null;

    public function mount()
    {
        $this->loginID = Auth::id();
        $this->authId = $this->loginID;

        $this->loadChatList();

        $this->selectedUser = $this->users->first();
        if ($this->selectedUser) {
            $this->loadMessages();
        }
    }

    private function loadChatList()
    {
        // Get all chat partners with their last message
        $chatPartners = ChatMessage::query()
            ->where(function ($q) {
                $q->where('sender_id', $this->authId)
                    ->orWhere('receiver_id', $this->authId);
            })
            ->selectRaw('
            CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as user_id,
            MAX(created_at) as last_message_at
        ', [$this->authId])
            ->groupBy('user_id')
            ->orderByDesc('last_message_at')
            ->limit(20)
            ->pluck('user_id');

        // Fetch the users
        $this->users = User::whereIn('id', $chatPartners)
            ->whereIn('role', ['admin', 'counselor', 'parent'])
            ->where('status', 'active')
            ->get()
            ->map(function ($user) {
                // Grab last message for each user
                $user->lastMessage = ChatMessage::where(function ($q) use ($user) {
                    $q->where('sender_id', $this->authId)
                        ->where('receiver_id', $user->id);
                })
                    ->orWhere(function ($q) use ($user) {
                        $q->where('sender_id', $user->id)
                            ->where('receiver_id', $this->authId);
                    })
                    ->latest()
                    ->first();

                return $user;
            })
            ->sortByDesc(fn($u) => optional($u->lastMessage)->created_at)
            ->values();
    }

    public function selectUser($id)
    {
        $this->newChatMode = false;
        $this->selectedUser = User::find($id);

        // mark all messages from this user as read
        ChatMessage::where('sender_id', $this->selectedUser->id)
            ->where('receiver_id', $this->authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $this->loadMessages();
        $this->dispatch('chat:selected');
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::query()
            ->where(function ($q) {
                $q->where('sender_id', $this->authId)
                    ->where('receiver_id', $this->selectedUser->id);
            })
            ->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', $this->authId);
            })
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();
    }

    public function startNewChat()
    {
        $this->newChatMode = true;
        $this->selectedUser = null;
        $this->messages = collect(); // reset messages
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) > 1) {
            $this->searchResults = User::where('first_name', 'like', "%{$this->searchQuery}%")
                ->whereIn('role', ['admin', 'counselor', 'parent'])
                ->orWhere('last_name', 'like', "%{$this->searchQuery}%")
                ->take(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function selectNewChatUser($userId)
    {
        $this->selectedUser = User::find($userId);

        // Check for existing messages between auth and selected user
        $existingMessages = ChatMessage::query()
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $this->authId)
                    ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->where('receiver_id', $this->authId);
            })
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        $this->messages = $existingMessages->isNotEmpty() ? $existingMessages : collect();
        $this->newChatMode = false;
        $this->dispatch('chat:selected');
    }

    public function submit()
    {
        if (!$this->newMessage || !$this->selectedUser) return;

        $message = ChatMessage::create([
            'sender_id'   => $this->authId,
            'receiver_id' => $this->selectedUser->id,
            'message'     => $this->newMessage,
            'is_read'     => false,
        ]);

        $this->messages->push($message);

        // ensure selectedUser is added to chat list if not already there
        if (!$this->users->contains('id', $this->selectedUser->id)) {
            $this->users->push($this->selectedUser);
        }

        // Update chat list
        $this->selectedUser->setRelation('lastMessage', $message);
        $this->users = $this->users
            ->sortByDesc(fn($u) => optional($u->lastMessage)->created_at)
            ->values();

        $this->loadChatList();
        $this->newMessage = '';
        $this->dispatch('chat:messageSent');
        broadcast(new MessageSent($message));
    }

    public function updatedNewMessage($value)
    {
        $user = Auth::user();
        $this->dispatch(
            "userTyping",
            userID: $this->loginID,
            profileImage: $user->profile_image,
            selectedUserID: $this->selectedUser->id
        );
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($message)
    {
        $messageObj = ChatMessage::find($message['id']);
        if ($message['sender_id'] == $this->selectedUser->id) {
            $this->messages->push($messageObj);
            $this->dispatch('chat:messageSent');
        }
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
