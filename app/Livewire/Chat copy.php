<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class Chat extends Component
{
    public $users;
    public $newMessage;
    public $messages;
    public $authId;
    public $loginID;
    public $newChatMode = false;
    public $filter = 'recent';
    public $conversationSearch = '';
    public $searchQuery = '';
    public $conversationSearchResults = [];
    public $newChatSearchResults = [];
    public $selectedUser = null;
    public $showUserProfile = false;

    public $isBlocked = false; 
    public $hasBlocked = false;

    public $showPrivacyDropdown = false;

    private const ACTIVE_CONVO_SESSION_KEY = 'active_chat_user_id';
    public const USER_PROFILE_STATE_KEY = 'sgrms_user_profile_visible_';

    public function mount()
    {
        $this->loginID = Auth::id();
        $this->authId = $this->loginID;

        $this->loadChatList();

        $activeUserId = Session::get(self::ACTIVE_CONVO_SESSION_KEY);
        $restoredUser = null;

        if ($activeUserId) {

            $restoredUser = $this->users->firstWhere('id', $activeUserId);
        }

        $this->selectedUser = $restoredUser ?? $this->users->first();

        if ($this->selectedUser) {
            Session::put(self::ACTIVE_CONVO_SESSION_KEY, $this->selectedUser->id);
            $this->loadMessages();
            $this->markMessagesAsRead();
            $this->dispatch('requestProfilePaneState', [
                'localStorageKey' => self::USER_PROFILE_STATE_KEY . $this->selectedUser->id,
            ]);
        } else {
            Session::forget(self::ACTIVE_CONVO_SESSION_KEY);
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadChatList();
        $this->conversationSearch = '';
        $this->conversationSearchResults = [];
    }


    private function loadChatList()
    {
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
            ->pluck('user_id');

        $userQuery = User::whereIn('id', $chatPartners)
            ->where('status', 'active');

        if ($this->filter === 'counselor') {
            $userQuery->where('role', 'counselor');
        } elseif ($this->filter === 'unread') {

            $userQuery->whereIn('id', function ($query) {
                $query->select('sender_id')
                    ->from('chat_messages')
                    ->where('receiver_id', $this->authId)
                    ->where('is_read', false);
            });
        }

        $this->users = $userQuery->get()
            ->map(function ($user) {
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
            ->values()
            ->take(20);
    }

    private function markMessagesAsRead()
    {
        if ($this->selectedUser) {
            ChatMessage::where('sender_id', $this->selectedUser->id)
                ->where('receiver_id', $this->authId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }
    }


    public function selectUser($id)
    {
        $this->newChatMode = false;
        $this->selectedUser = User::find($id);

        Session::put(self::ACTIVE_CONVO_SESSION_KEY, $id);

        $this->markMessagesAsRead();
        $this->loadMessages();

        $this->loadChatList();
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
            ->get();
    }


    public function startNewChat()
    {
        $this->newChatMode = true;
        $this->selectedUser = null;
        $this->messages = collect();

        Session::forget(self::ACTIVE_CONVO_SESSION_KEY);
        $this->searchQuery = '';
        $this->conversationSearch = '';
        $this->newChatSearchResults = [];
        $this->conversationSearchResults = [];
        $this->filter = 'recent';
    }

    public function updatedSearchQuery()
    {
        if ($this->newChatMode && strlen($this->searchQuery) > 1) {
            $query = trim($this->searchQuery);
            $this->newChatSearchResults = User::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
                ->whereIn('role', ['admin', 'counselor', 'parent'])
                ->where('status', 'active')
                ->where('id', '!=', $this->authId) // Exclude self
                ->take(10)
                ->get();
        } else {
            $this->newChatSearchResults = [];
        }
    }

    public function updatedConversationSearch()
    {
        $this->searchConversations();
    }

    public function searchConversations()
    {
        $query = trim($this->conversationSearch);

        if (strlen($query) <= 1) {
            $this->conversationSearchResults = collect();
            return;
        }

        $chatUserIds = ChatMessage::query()
            ->where('sender_id', $this->authId)
            ->orWhere('receiver_id', $this->authId)
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as user_id', [$this->authId])
            ->groupBy('user_id')
            ->pluck('user_id');

        $this->conversationSearchResults = User::whereIn('id', $chatUserIds)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->take(20)
            ->get();
    }


    public function selectNewChatUser($userId)
    {
        $this->selectedUser = User::find($userId);

        Session::put(self::ACTIVE_CONVO_SESSION_KEY, $userId);

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
            ->values();

        $this->messages = $existingMessages->isNotEmpty() ? $existingMessages : collect();
        $this->newChatMode = false;
        $this->searchQuery = '';
        $this->conversationSearch = '';
        $this->newChatSearchResults = collect();
        $this->conversationSearchResults = collect();
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

        $this->messages->prepend($message);

        if (!$this->users->contains('id', $this->selectedUser->id)) {
            $this->users->push($this->selectedUser);
        }

        $this->selectedUser->setRelation('lastMessage', $message);
        $this->users = $this->users
            ->sortByDesc(fn($u) => optional($u->lastMessage)->created_at)
            ->values();

        Session::put(self::ACTIVE_CONVO_SESSION_KEY, $this->selectedUser->id);

        $this->loadChatList();
        $this->newMessage = '';
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
        $this->loadChatList();

        if ($message['sender_id'] == $this->selectedUser->id) {
            $this->messages->prepend($messageObj);
            $this->dispatch('chat:messageSent');
        }

        if (!$this->selectedUser && $this->users->isNotEmpty()) {
            $this->selectedUser = $this->users->first();
            Session::put(self::ACTIVE_CONVO_SESSION_KEY, $this->selectedUser->id);
            $this->loadMessages();
            $this->markMessagesAsRead();
            $this->dispatch('chat:selected');
        }
    }


    public function toggleUserProfile()
    {
        $this->showUserProfile = !$this->showUserProfile;
        $this->dispatch('saveProfilePaneState', [
            'isVisible' => $this->showUserProfile,
            'localStorageKey' => self::USER_PROFILE_STATE_KEY . ($this->selectedUser ? $this->selectedUser->id : ''),
        ]);
    }

    public function updatedSelectedUser()
    {
        $this->dispatch('requestProfilePaneState', [
            'localStorageKey' => self::USER_PROFILE_STATE_KEY . ($this->selectedUser ? $this->selectedUser->id : ''),
        ]);
    }

    public function setUserProfileState($isVisible)
    {
        $this->showUserProfile = $isVisible;
    }
    public function render()
    {
        return view('livewire.chat');
    }
}
