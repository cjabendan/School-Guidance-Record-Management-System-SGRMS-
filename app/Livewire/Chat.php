<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\ChatMessage;
use App\Events\MessageSent;
use App\Events\UserBlockedStatus;

class Chat extends Component
{
    // === Public Properties ===
    public $users;
    public $messages;
    public $newMessage;
    public $loginID;
    public $authId;
    public $authRole;
    public $selectedUser = null;
    public $selectedUserId = null;
    public $scrollToMessageId = null;
    public $messageIdToHighlight = null;
    // UI States
    public $newChatMode = false;
    public $filter = 'recent';
    public $conversationSearch = '';
    public $searchQuery = '';
    public $conversationSearchResults = [];
    public $newChatSearchResults = [];
    public $showUserProfile = false;
    public $showInactiveUserMessage = false;



    // Profile Search
    public $profileSearchQuery = '';
    public $profileSearchResults = [];

    // Block States
    public $isBlocked = false;
    public $hasBlocked = false;

    // === Constants ===
    private const ACTIVE_CONVO_SESSION_KEY = 'active_chat_user_id';
    public const USER_PROFILE_STATE_KEY = 'sgrms_user_profile_visible_';

    // Chat time

    private function formatTimeShort($timestamp)
    {
        if (!$timestamp) {
            return null;
        }

        $diffInSeconds = $timestamp->diffInSeconds(\Illuminate\Support\Carbon::now());

        if ($diffInSeconds < 60) {
            return '1 m';
        }

        $diff = $timestamp->diffForHumans([
            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
            'options' => \Carbon\Carbon::JUST_NOW | \Carbon\Carbon::ONE_DAY_WORDS,
            'parts' => 1,
        ]);

        // Simple mapping to shorten the output string
        $replacements = [
            'years' => 'y',
            'year' => 'y',
            'months' => 'mon',
            'month' => 'mon',
            'weeks' => 'w',
            'week' => 'w',
            'days' => 'd',
            'day' => 'd',
            'hours' => 'hr',
            'hour' => 'hr',
            'minutes' => 'm',
            'minute' => 'm',
            'seconds' => 's',
            'second' => 's',
        ];


        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            str_replace(' ago', '', $diff)
        );
    }

    // Conversation Filter
    public function setFilter($filter)
    {
        $this->filter = $filter;

        if ($filter === 'staff') {
            $this->users = User::whereIn('role', ['counselor', 'admin'])->get();
        }
        $this->loadChatList();
        $this->conversationSearch = '';
        $this->conversationSearchResults = [];
    }


    // === Lifecycle ===
    public function mount($selectedUserId = null)
    {
        $this->loginID = Auth::id();
        $this->authId = $this->loginID;
        $this->authRole = Auth::user()->role;

        $this->loadChatList();

        $user = $this->resolveInitialUser($selectedUserId);

        if ($user) {
            $this->setActiveConversation($user);
        } else {
            Session::forget(self::ACTIVE_CONVO_SESSION_KEY);
        }
    }

    private function resolveInitialUser($selectedUserId)
    {
        // 1. Route parameter
        if ($selectedUserId) {
            $user = $this->users->firstWhere('id', $selectedUserId);
            if ($user) return $user;
        }

        // 2. Session
        $activeUserId = Session::get(self::ACTIVE_CONVO_SESSION_KEY);
        if ($activeUserId) {
            $user = $this->users->firstWhere('id', $activeUserId);
            if ($user) return $user;
        }

        // 3. Fallback
        return $this->users->first();
    }

    // === Conversation Management ===
    private function setActiveConversation(User $user)
    {
        $this->selectedUser = $user;
        Session::put(self::ACTIVE_CONVO_SESSION_KEY, $user->id);

        $this->loadMessages();
        $this->markMessagesAsRead();
        $this->loadBlockStatus();
        $this->loadChatList();

        $this->dispatch('chat:selected');
        $this->dispatch('requestProfilePaneState', [
            'localStorageKey' => self::USER_PROFILE_STATE_KEY . $user->id,
        ]);
    }

    public function selectUser($id)
    {
        $this->newChatMode = false;
        $this->showInactiveUserMessage = false;

        $user = User::where('id', $id)->where('status', 'active')->first();

        if (!$user) {
            $this->showInactiveUserMessage = true;
            $this->selectedUser = null;
            return;
        }

        if (in_array($this->authRole, ['parent', 'student']) && !in_array($user->role, ['admin', 'counselor'])) {
            $this->selectedUser = null;
            return;
        }

        $this->setActiveConversation($user);
    }

    private function loadMessages()
    {
        if (!$this->selectedUser) return;

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

    private function markMessagesAsRead()
    {
        if (!$this->selectedUser) return;

        ChatMessage::where('sender_id', $this->selectedUser->id)
            ->where('receiver_id', $this->authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    // === Chat List ===
    private function loadChatList()
    {
        $authId = $this->authId;

        $blockedIds = Auth::user()->blocks()->pluck('blocked_id');
        $blockedByIds = Auth::user()->blockedBy()->pluck('blocker_id');
        $allBlocked = $blockedIds->merge($blockedByIds)->unique();

        // step 1: collect all chat partners and their last message id
        $chatPartnerData = ChatMessage::query()
            ->selectRaw("CASE WHEN sender_id = {$authId} THEN receiver_id ELSE sender_id END as user_id, MAX(id) as last_message_id")
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                    ->orWhere('receiver_id', $authId);
            })
            ->groupBy('user_id')
            ->get();

        $partnerIds = $chatPartnerData->pluck('user_id');
        $lastMessageIds = $chatPartnerData->pluck('last_message_id');

        // step 2: load users
        $query = User::whereIn('id', $partnerIds)
            ->where('status', 'active');


        if ($this->filter === 'staff') {
            $query->where('role', 'counselor');
        } elseif ($this->filter === 'unread') {
            $query->whereIn('id', function ($q) use ($authId) {
                $q->select('sender_id')
                    ->from('chat_messages')
                    ->where('receiver_id', $authId)
                    ->where('is_read', false);
            });
        }

        // step 3: preload last messages
        $lastMessages = ChatMessage::whereIn('id', $lastMessageIds)
            ->get()
            ->keyBy(function ($msg) use ($authId) {
                return $msg->sender_id == $authId
                    ? $msg->receiver_id
                    : $msg->sender_id;
            });

        // step 4: combine and sort
        $this->users = $query->get()
            ->map(function ($user) use ($lastMessages) {
                $user->lastMessage = $lastMessages->get($user->id);
                return $user;
            })
            ->sortByDesc(fn($u) => optional($u->lastMessage)->created_at)
            ->values()
            ->take(20);
    }

    // === Search ===
    public function updatedSearchQuery()
    {
        if (!$this->newChatMode || strlen($this->searchQuery) <= 1) {
            $this->newChatSearchResults = [];
            return;
        }

        $query = trim($this->searchQuery);
        $userQuery = User::where(function ($q) use ($query) {
            $q->where('first_name', 'like', "%{$query}%")
                ->orWhere('last_name', 'like', "%{$query}%");
        })
            ->where('status', 'active')
            ->where('id', '!=', $this->authId);

        if (in_array($this->authRole, ['parent', 'student'])) {
            $userQuery->whereIn('role', ['admin', 'counselor']);
        } elseif (in_array($this->authRole, ['admin', 'counselor'])) {
            $userQuery->whereIn('role', ['admin', 'counselor', 'parent', 'student']);
        } else {
            $userQuery->whereRaw('1=0');
        }

        $this->newChatSearchResults = $userQuery->take(10)->get();
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

        $userQuery = User::whereIn('id', $chatUserIds)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            });

        if (in_array($this->authRole, ['parent', 'student'])) {
            $userQuery->whereIn('role', ['admin', 'counselor']);
        }

        $this->conversationSearchResults = $userQuery->take(20)->get();
    }

    // Select User with existing conversation:
    public function selectNewChatUser($userId)
    {
        $this->newChatMode = false;
        $this->showInactiveUserMessage = false;

        $user = User::where('id', $userId)
            ->where('status', 'active')
            ->first();

        if (!$user) {
            $this->showInactiveUserMessage = true;
            $this->selectedUser = null;
            return;
        }

        if (in_array($this->authRole, ['parent', 'student']) && !in_array($user->role, ['admin', 'counselor'])) {
            $this->selectedUser = null;
            return;
        }

        $this->setActiveConversation($user);

        // Clear new chat search & conversation states
        $this->messages = $this->messages ?? collect();
        $this->searchQuery = '';
        $this->conversationSearch = '';
        $this->newChatSearchResults = collect();
        $this->conversationSearchResults = collect();
    }


    // === START NEW CONVERSATION ===

    public function startNewChat()
    {
        $this->newChatMode = true;
        $this->selectedUser = null;
        $this->messages = collect();
        $this->showUserProfile = false;

        // Clear session
        Session::forget(self::ACTIVE_CONVO_SESSION_KEY);

        // Reset search & filters
        $this->searchQuery = '';
        $this->conversationSearch = '';
        $this->newChatSearchResults = [];
        $this->conversationSearchResults = [];
        $this->filter = 'recent';
    }


    // === Blocking ===
    public function loadBlockStatus()
    {
        if (!$this->selectedUser) {
            $this->isBlocked = false;
            $this->hasBlocked = false;
            return;
        }

        $authBlockerIds = Auth::user()->blockedBy()->pluck('blocker_id');
        $authBlockedIds = Auth::user()->blocks()->pluck('blocked_id');

        $this->isBlocked = $authBlockerIds->contains($this->selectedUser->id);
        $this->hasBlocked = $authBlockedIds->contains($this->selectedUser->id);
    }

    public function toggleBlockUser()
    {
        if (!$this->selectedUser) return;
        if (!in_array($this->authRole, ['admin', 'counselor'])) return;
        if (!$this->hasBlocked && !in_array($this->selectedUser->role, ['parent', 'student'])) return;

        $blocker = Auth::user();
        $blockedId = $this->selectedUser->id;
        $isNowBlocked = false;

        if ($this->hasBlocked) {
            $blocker->blocks()->detach($blockedId);
        } else {
            $blocker->blocks()->attach($blockedId);
            $isNowBlocked = true;
        }

        $this->loadBlockStatus();
        $this->loadChatList();
        $this->dispatch('chat:updated');

        broadcast(new UserBlockedStatus($blockedId, $this->authId, $isNowBlocked));
    }

    // === Message Sending ===
    public function submit()
    {
        if (
            in_array($this->authRole, ['parent', 'student']) &&
            !in_array($this->selectedUser->role, ['admin', 'counselor'])
        ) return;

        if (!$this->newMessage || !$this->selectedUser || $this->isBlocked || $this->hasBlocked) return;

        $message = ChatMessage::create([
            'sender_id' => $this->authId,
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
            'is_read' => false,
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

    // === UI Toggles ===
    public function toggleUserProfile()
    {
        $this->showUserProfile = !$this->showUserProfile;
        $this->dispatch('saveProfilePaneState', [
            'isVisible' => $this->showUserProfile,
            'localStorageKey' => self::USER_PROFILE_STATE_KEY . ($this->selectedUser->id ?? ''),
        ]);
    }

    public function searchInConversation()
    {
        if (!$this->selectedUser || empty($this->profileSearchQuery)) {
            $this->profileSearchResults = [];
            return;
        }

        $query = trim($this->profileSearchQuery);
        $authId = $this->authId;
        $selectedUserId = $this->selectedUser->id;

        $regexp = '[[:<:]]' . preg_quote($query, '/') . '[[:>:]]';

        $this->profileSearchResults = ChatMessage::query()
            ->where(function ($q) use ($authId, $selectedUserId) {
                $q->where(function ($q2) use ($authId, $selectedUserId) {
                    $q2->where('sender_id', $authId)
                        ->where('receiver_id', $selectedUserId);
                })
                    ->orWhere(function ($q2) use ($authId, $selectedUserId) {
                        $q2->where('sender_id', $selectedUserId)
                            ->where('receiver_id', $authId);
                    });
            })
            ->whereRaw("LOWER(message) REGEXP ?", [strtolower($regexp)])
            ->latest()
            ->take(10)
            ->get();

        $this->dispatch('profileSearchCompleted');
    }

    // Load Messages of selected chat

    public function loadAllMessages()
    {
        if (!$this->selectedUser) return;

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
            ->get(); // Load all messages
    }


    public function goToMessage($messageId)
    {

        $this->loadAllMessages();

        $this->messageIdToHighlight = $messageId;

    //  $this->profileSearchQuery = '';
    //  $this->profileSearchResults = [];

        $this->scrollToMessageId = $messageId;

        $this->dispatch('exitSearchMode');
    }

    // === Realtime Events ===
    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification',
            "echo-private:chat.{$this->loginID},UserBlockedStatus" => 'handleRealtimeBlock',
            'chatDeleted' => 'handleChatDeleted',
        ];
    }

    public function newChatMessageNotification($message)
    {
        $messageObj = ChatMessage::find($message['id']);
        if (Auth::user()->blocks()->where('blocked_id', $messageObj->sender_id)->exists()) return;

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
            $this->loadBlockStatus();
            $this->dispatch('chat:selected');
        }
    }

    public function handleRealtimeBlock(array $event)
    {
        if ($this->selectedUser && $this->selectedUser->id == $event['blockerId']) {
            $this->loadBlockStatus();
            $this->loadChatList();
            $this->dispatch('chat:messageSent');
        }
    }

    // === Delete Chat ===
    public function requestDeleteChat()
    {
        if (!$this->selectedUser) {
            return;
        }

        $this->dispatch('openDeleteChatModal', userId: $this->selectedUser->id);
    }

    public function handleChatDeleted($userId)
    {
        // Reset the selected user and reload chat list
        if ($this->selectedUser && $this->selectedUser->id == $userId) {
            $this->selectedUser = null;
            Session::forget(self::ACTIVE_CONVO_SESSION_KEY);
        }

        $this->loadChatList();
        $this->messages = collect();

        // Auto-select the first (most recent) user from the updated chat list
        if ($this->users->isNotEmpty()) {
            $this->setActiveConversation($this->users->first());
        }
    }

    // === Render ===
    public function render()
    {
        return view('livewire.chat');
    }
}
