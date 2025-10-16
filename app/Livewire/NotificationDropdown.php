<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationDropdown extends Component
{
    public $showDropdown = false;
    public $notifications = [];
    public $unreadCount = 0;
    public $showAll = false;

    protected $listeners = [
        'notificationReceived' => 'notificationReceived',
        'notificationRead' => 'notificationRead',
        'refreshNotifications' => 'refreshNotifications',
    ];

    public function notificationRead($id)
    {
        foreach ($this->notifications as &$notif) {
            if ($notif['id'] == $id) {
                $notif['is_read'] = 1;
                break;
            }
        }

        // Sort: unread first, then read
        $this->notifications = collect($this->notifications)
            ->sortBy('is_read')
            ->values()
            ->toArray();

        $this->unreadCount = collect($this->notifications)->where('is_read', 0)->count();
    }

    // Add polling for real-time updates (every 3 seconds)
    public function getPollingInterval()
    {
        return 3000; // ms
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        // Always refresh notifications respecting showAll
        $this->refreshNotifications();
    }

    public function mount()
    {
        $this->showAll = false;
        $this->refreshNotifications();
    }

    public function notificationReceived($notification)
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        $userId = Auth::id();
        if ($this->showAll) {
            $this->notifications = Notification::where('user_id', $userId)
                ->orderBy('is_read', 'asc')
                ->orderBy('timestamp', 'desc')
                ->get()
                ->toArray();
        } else {
            $this->notifications = Notification::where('user_id', $userId)
                ->orderBy('is_read', 'asc')
                ->orderBy('timestamp', 'desc')
                ->limit(10)
                ->get()
                ->toArray();
        }

        $this->unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->count();
    }

    public function showAllNotifications()
    {
        $this->showAll = true;
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }

    // Livewire polling method
    public function pollNotifications()
    {
        // Always respect showAll when polling
        $this->refreshNotifications();
    }
}
