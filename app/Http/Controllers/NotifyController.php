<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotifyController extends Controller
{
    // Show ALL notifications
    public function index()
    {
        $userId = Auth::id();

        $notifications = Notification::where('user_id', $userId)
            ->orderBy('timestamp', 'desc')
            ->paginate(5);

    return view('Head.Notify.notification', compact('notifications'));
    }

    // Fetch UNREAD notifications for navbar dropdown
    public function fetchNotifications(Request $request)
    {
        $userId = Auth::id();

        $notifications = Notification::where('user_id', $userId)
            ->where('is_read', 0)
            ->orderBy('timestamp', 'desc')
            ->take(5)
            ->get()
            ->map(function ($n) {
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

                // 👉 Link to notifications index page
                $link = route('Head.notify.notification');

                return [
                    'icon' => $icon,
                    'text' => $n->message,
                    'time' => Carbon::parse($n->timestamp)->diffForHumans(),
                    'link' => $link,
                ];
            });

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('partials.notify', ['notifications' => $notifications])->render();
            return response()->json([
                'html' => $html,
                'debug_notifications' => $notifications,
                'debug_user_id' => $userId
            ]);
        }

        return redirect()->back();
    }
}
