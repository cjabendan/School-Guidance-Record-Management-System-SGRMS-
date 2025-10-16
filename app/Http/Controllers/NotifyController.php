<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification;

class NotifyController extends Controller
{

    public function store(Request $request)
    {
        if ($request->has('announcement') && $request->announcement) {
            // Get all users except admin
            $users = \App\Models\User::whereIn('role', ['counselor', 'parent', 'student'])->get();

            foreach ($users as $user) {
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'message' => $request->message,
                    'timestamp' => now(),
                    'is_read' => 0,
                ]);
                broadcast(new NewNotification($notification, false));
            }

            return response()->json(['success' => true, 'type' => 'announcement']);
        } else {
            $notification = Notification::create([
                'user_id' => $request->user_id,
                'message' => $request->message,
                'timestamp' => now(),
                'is_read' => 0,
            ]);

            broadcast(new NewNotification($notification));

            return response()->json(['success' => true]);
        }
    }


    // Mark notification as read (AJAX)
    public function markAsRead(Request $request)
    {
        $notifId = $request->input('id');
        $userId = Auth::id();
        $notif = Notification::where('id', $notifId)->where('user_id', $userId)->first();
        if ($notif && $notif->is_read == 0) {
            $notif->is_read = 1;
            $notif->save();

            broadcast(new \App\Events\NotificationRead($notif));

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
    
    public function index(Request $request)
    {
        $userId = Auth::id();
        $role = Auth::user()->role ?? null;

        if ($request->has('notify_id')) {
            Notification::where('id', $request->notify_id)
                ->where('user_id', $userId)
                ->update(['is_read' => 1]);
        }

        // Fetch all user notifications sorted by newest first
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->get()
            ->unique('message'); // prevent duplicate messages

        // Grouping
        $new = $notifications->filter(fn($n) => $n->is_read == 0);

        $today = $notifications->filter(fn($n) => 
            $n->is_read == 1 && \Carbon\Carbon::parse($n->timestamp)->isToday()
        );

        $earlier = $notifications->filter(fn($n) => 
            $n->is_read == 1 && \Carbon\Carbon::parse($n->timestamp)->isBefore(\Carbon\Carbon::today())
        );

        // Return view based on role
        if (in_array($role, ['head', 'admin'])) {
            return view('Head.Notify.notification', compact('new', 'today', 'earlier'));
        } elseif ($role === 'counselor') {
            return view('Counselor.Notify.notification', compact('new', 'today', 'earlier'));
        } elseif ($role === 'parent') {
            return view('Parent.Notify.notification', compact('new', 'today', 'earlier'));
        } elseif ($role === 'student') {
            return view('Student.Notify.notification', compact('new', 'today', 'earlier'));
        } else {
            abort(403, 'Unauthorized access');
        }
    }


    // Fetch UNREAD notifications for navbar dropdown
    public function fetchNotifications(Request $request)
    {
        $userId = Auth::id();

        // Get unread notifications first, then read notifications, both sorted by timestamp desc
        $unread = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('timestamp', 'desc')
            ->get();

        $read = Notification::where('user_id', $userId)
            ->where('is_read', 1)
            ->orderBy('timestamp', 'desc')
            ->get();

        $notifications = $unread->concat($read)->map(function ($n) {
            $msg = strtolower($n->message);
            $icon = 'ℹ️';

            if (str_contains($msg, 'request')) {
                $icon = '📩';
            } elseif (str_contains($msg, 'appointment') || str_contains($msg, 'reminder')) {
                $icon = '⏰';
            } elseif (str_contains($msg, 'case') || str_contains($msg, 'violation')) {
                $icon = '⚠️';
            } elseif (str_contains($msg, 'announcement')) {
                $icon = '📢';
            }


            $link = '#';
            $role = Auth::user()->role ?? null;

            if ($role === 'head' || $role === 'admin') {
                $link = route('Head.notify.notification') . '?notify_id=' . $n->id;
            } elseif ($role === 'counselor') {
                $link = route('Counselor.notify.notification') . '?notify_id=' . $n->id;
            } elseif ($role === 'parent') {
                $link = route('Parent.notify.notification') . '?notify_id=' . $n->id;
            } elseif ($role === 'student') {
                $link = route('Student.notify.notification') . '?notify_id=' . $n->id;
            }


            return [
                'id' => $n->id,
                'icon' => $icon,
                'text' => $n->message,
                'time' => Carbon::parse($n->timestamp)->diffForHumans(),
                'link' => $link,
                'is_read' => $n->is_read,
            ];
        });

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('partials.notify', ['notifications' => $notifications])->render();
            return response()->json([
                'html' => $html,
                'debug_notifications' => $notifications,
                'debug_user_id' => $userId,
                'unread_count' => $unread->count(),
            ]);
        }

        return redirect()->back();
    }
}
