<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationBoard extends Component
{
    public $new;
    public $today;
    public $earlier;
    public $showAll = false;

    public function markAsReadAndShowModal($id)
    {
        $user = Auth::user();
        $notification = Notification::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($notification) {
            // Mark notification as read if unread
            if (!$notification->is_read) {
                $notification->is_read = 1;
                $notification->save();
            }

            // Fetch related announcement if any
            $announcement = $notification->relatedAnnouncement ?? null;

            // Prepare safe data for modal
            $title = $announcement->title ?? $notification->message ?? 'Notification';
            $description = $announcement->description ?? $notification->message ?? 'No description available.';
            $category = $announcement->category ?? 'General';
            $time = $notification->timestamp
                ? Carbon::parse($notification->timestamp)->format('F d, Y g:i A')
                : 'No date available';

            // Dispatch event to JS (pass individual parameters)
            $this->dispatch(
                'openNotificationModal',
                title: $title,
                description: $description,
                time: $time,
                category: $category
            );

            // Refresh the notification list
            $this->refreshNotifications();
        }
    }

    public function getListeners()
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        return [
            'notificationReceived' => 'refreshNotifications',
        ];
    }

    public function mount()
    {
        $this->showAll = false;
        $this->loadNotifications();
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function showAllNotifications()
    {
        $this->showAll = true;
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            $this->new = collect();
            $this->today = collect();
            $this->earlier = collect();
            return;
        }

        if ($this->showAll) {
            // Show all notifications grouped
            $this->new = Notification::where('user_id', $user->id)
                ->where('is_read', 0)
                ->orderBy('timestamp', 'desc')
                ->get();

            $this->today = Notification::where('user_id', $user->id)
                ->where('is_read', 1)
                ->whereDate('timestamp', now()->toDateString())
                ->orderBy('timestamp', 'desc')
                ->get();

            $this->earlier = Notification::where('user_id', $user->id)
                ->where('is_read', 1)
                ->whereDate('timestamp', '<', now()->toDateString())
                ->orderBy('timestamp', 'desc')
                ->get();
        } else {
            // Show only 10 notifications (new + today + earlier, up to 10)
            $all = Notification::where('user_id', $user->id)
                ->orderBy('is_read', 'asc')
                ->orderBy('timestamp', 'desc')
                ->limit(10)
                ->get();

            $this->new = $all->where('is_read', 0)->where('timestamp', '>=', now()->startOfDay());
            $this->today = $all->where('is_read', 1)->where('timestamp', '>=', now()->startOfDay());
            $this->earlier = $all->where('timestamp', '<', now()->startOfDay());
        }
    }

    public function render()
    {
        return view('livewire.notification-board');
    }
}
