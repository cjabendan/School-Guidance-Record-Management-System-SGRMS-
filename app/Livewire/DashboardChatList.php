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
            ->values()
            ->take(20);
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


    protected $listeners = [
        'refreshChatList' => 'loadChatList',
    ];

    public function render()
    {
        return view('livewire.dashboard-chat-list');
    }
}
