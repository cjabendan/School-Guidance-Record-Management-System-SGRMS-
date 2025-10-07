<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatMessage;

class DashboardChatList extends Component
{
    public $users;

    public function mount()
    {
        $this->loadChatList();
    }

    public function loadChatList()
    {
        $authId = Auth::id();

        $chatPartners = ChatMessage::query()
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                  ->orWhere('receiver_id', $authId);
            })
            ->selectRaw('CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as user_id, MAX(created_at) as last_message_at', [$authId])
            ->groupBy('user_id')
            ->orderByDesc('last_message_at')
            ->pluck('user_id');

        $this->users = User::whereIn('id', $chatPartners)
            ->where('status', 'active')
            ->get()
            ->map(function ($user) use ($authId) {
                $user->lastMessage = ChatMessage::where(function ($q) use ($authId, $user) {
                    $q->where('sender_id', $authId)
                      ->where('receiver_id', $user->id);
                })
                ->orWhere(function ($q) use ($authId, $user) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', $authId);
                })
                ->latest()
                ->first();

                return $user;
            })
            ->sortByDesc(fn($u) => optional($u->lastMessage)->created_at)
            ->values();
    }

    // Poll every 5 seconds for updates
    protected $listeners = [
        'refreshChatList' => 'loadChatList',
    ];

    public function render()
    {
        return view('livewire.dashboard-chat-list');
    }
}