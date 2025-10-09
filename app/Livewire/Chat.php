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
    public $selectedUserId = null;
    public $showUserProfile = false;
    public $authRole;
    public $isBlocked = false;
    public $hasBlocked = false;
    public $showInactiveUserMessage = false;
    public $showPrivacyDropdown = false;
    public $searchModeInProfile = false;
    public $profileSearchQuery = '';
    public $profileSearchResults = [];

    private const ACTIVE_CONVO_SESSION_KEY = 'active_chat_user_id';
    public const USER_PROFILE_STATE_KEY = 'sgrms_user_profile_visible_';



    public function mount($selectedUserId = null)
    {
        $this->loginID = Auth::id();
        $this->authId = $this->loginID;
        $this->authRole = Auth::user()->role;

        $this->loadChatList();

        // Use selectedUserId from route if provided
        if ($selectedUserId) {
            $restoredUser = $this->users->firstWhere('id', $selectedUserId);
            if ($restoredUser) {
                $this->selectedUser = $restoredUser;
                Session::put(self::ACTIVE_CONVO_SESSION_KEY, $restoredUser->id);
            } else {
                $this->selectedUser = $this->users->first();
            }
        } else {
            $activeUserId = Session::get(self::ACTIVE_CONVO_SESSION_KEY);
            $restoredUser = $activeUserId ? $this->users->firstWhere('id', $activeUserId) : null;
            $this->selectedUser = $restoredUser ?? $this->users->first();
        }

        if ($this->selectedUser) {
            Session::put(self::ACTIVE_CONVO_SESSION_KEY, $this->selectedUser->id);
            $this->loadMessages();
            $this->markMessagesAsRead();
            $this->loadBlockStatus();
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

    private function formatTimeShort($timestamp)
    {
        if (!$timestamp) {
            return null;
        }

        // 1. Calculate the difference in seconds from the current time
        $diffInSeconds = $timestamp->diffInSeconds(\Illuminate\Support\Carbon::now());

        // 2. Apply the custom rule: if less than 60 seconds, force it to '1m'
        if ($diffInSeconds < 60) {
            return '1 m';
        }

        // 3. For 60 seconds (1 minute) and above, use Carbon's diffForHumans
        //    and then apply the standard abbreviations.
        $diff = $timestamp->diffForHumans([
            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
            'options' => \Carbon\Carbon::JUST_NOW | \Carbon\Carbon::ONE_DAY_WORDS,
            'parts' => 1, // Only show the largest unit (e.g., 5 minutes, not 5 minutes 30 seconds)
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
            // Note: 'second(s)' are handled by the rule above, but included for completeness
            'seconds' => 's',
            'second' => 's',
        ];

        // Replace the full words with the abbreviations and remove 'ago' if present
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            str_replace(' ago', '', $diff)
        );
    }
    /**
     * Loads the block status for the selected user relative to the authenticated user.
     */
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

    /**
     * Toggles the block status of the selected user.
     * 
     */
    public function toggleBlockUser()
    {
        if (!$this->selectedUser) return;

        if (!in_array($this->authRole, ['admin', 'counselor'])) {
            return;
        }

        if ($this->hasBlocked && !in_array($this->selectedUser->role, ['parent', 'student'])) {
        } elseif (!$this->hasBlocked && $this->selectedUser->role !== 'parent') {
            return;
        }

        $blocker = Auth::user();
        $blockedId = $this->selectedUser->id;

        if ($this->hasBlocked) {
            // Unblock
            $blocker->blocks()->detach($blockedId);
        } else {
            // Block
            $blocker->blocks()->attach($blockedId);
        }

        $this->showPrivacyDropdown = false;

        $this->loadBlockStatus();
        $this->loadChatList();
        $this->dispatch('chat:updated');
    }

    /**
     * Toggles the visibility of the privacy dropdown in the chat header.
     */
    public function togglePrivacyDropdown()
    {
        $this->showPrivacyDropdown = !$this->showPrivacyDropdown;
    }


    private function loadChatList()
    {

        $blockedUserIds = Auth::user()->blocks()->pluck('blocked_id');
        $blockedByMeUserIds = Auth::user()->blockedBy()->pluck('blocker_id');
        $allBlockedIds = $blockedUserIds->merge($blockedByMeUserIds)->unique();

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

        // This removes users who have blocked me.
        $userQuery->whereNotIn('id', Auth::user()->blockedBy()->pluck('blocker_id'));


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
        $this->showInactiveUserMessage = false;
        $this->selectedUser = User::where('id', $id)->where('status', 'active')->first();

        if (!$this->selectedUser) {
            $this->showInactiveUserMessage = true;
            return;
        }

        if ($this->authRole === 'parent' && !in_array($this->selectedUser->role, ['admin', 'counselor'])) {
            $this->selectedUser = null;
            return;
        }

        Session::put(self::ACTIVE_CONVO_SESSION_KEY, $id);

        $this->markMessagesAsRead();
        $this->loadMessages();
        $this->loadBlockStatus();
        $this->loadChatList();
        $this->dispatch('chat:selected');
    }

    public function loadMessages()
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


    public function startNewChat()
    {
        $this->newChatMode = true;
        $this->selectedUser = null;
        $this->messages = collect();
        $this->showUserProfile = false;

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
            $userQuery = User::where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            })
                ->where('status', 'active')
                ->where('id', '!=', $this->authId);

            if ($this->authRole === 'parent') {

                $userQuery->whereIn('role', ['admin', 'counselor']);
            } elseif (in_array($this->authRole, ['admin', 'counselor'])) {

                $userQuery->whereIn('role', ['admin', 'counselor', 'parent']);
            } else {
                // If not a standard role, block search
                $userQuery->whereRaw('1 = 0');
            }

            $this->newChatSearchResults = $userQuery->take(10)->get();
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

        $userQuery = User::whereIn('id', $chatUserIds)
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            });

        // ⭐ NEW: Parents can only search in conversations with Admin/Counselor
        if ($this->authRole === 'parent') {
            $userQuery->whereIn('role', ['admin', 'counselor']);
        }

        $this->conversationSearchResults = $userQuery->take(20)->get();
    }


    public function selectNewChatUser($userId)
    {

        $this->newChatMode = false;
        $this->showInactiveUserMessage = false;

        $this->selectedUser = User::where('id', $userId)->where('status', 'active')->first();

        if (!$this->selectedUser) {
            $this->showInactiveUserMessage = true;
            return;
        }

        if ($this->authRole === 'parent' && !in_array($this->selectedUser->role, ['admin', 'counselor'])) {
            $this->selectedUser = null;
            return;
        }

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
        $this->loadBlockStatus();
        $this->dispatch('chat:selected');
    }

    public function submit()
    {
        if ($this->authRole === 'parent' && !in_array($this->selectedUser->role, ['admin', 'counselor'])) {
            return;
        }

        if (!$this->newMessage || !$this->selectedUser || $this->isBlocked || $this->hasBlocked) return;

        $message = ChatMessage::create([
            'sender_id'     => $this->authId,
            'receiver_id'   => $this->selectedUser->id,
            'message'       => $this->newMessage,
            'is_read'       => false,
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

        // Check if the sender is blocked by me (I am 'receiver_id'). If so, ignore the message.
        if (Auth::user()->blocks()->where('blocked_id', $messageObj->sender_id)->exists()) {
            // If I have blocked the sender, ignore the notification/message
            return;
        }

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


    public function toggleSearchMode()
    {
        // Toggles the view between user profile and conversation search
        $this->searchModeInProfile = !$this->searchModeInProfile;

        // Reset search results/query when exiting search mode
        if (!$this->searchModeInProfile) {
            $this->profileSearchQuery = '';
            $this->profileSearchResults = [];
        }
    }


    public function setUserProfileState($isVisible)
    {
        $this->showUserProfile = $isVisible;
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

        // Use REGEXP for exact word match, case-insensitive
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


    public function goToMessage($messageId)
    {

        $this->dispatch('scrollToMessage', messageId: $messageId);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
