<?php
namespace App\Helpers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationHelper
{
    /**
     * Log a notification with message and user.
     */
    public static function log($message, $userId = null)
    {
        Notification::create([
            'message'   => $message,
            'timestamp' => now(),   
            'is_read'   => 0,
            'user_id'   => $userId ?? (Auth::id() ?? null),
        ]);
    }

    /**
     * Return the correct icon for a given notification message.
     */
    public static function getIcon($message)
    {
        $msg = strtolower($message);

        if (str_contains($msg, 'request')) {
            return '📩';
        } elseif (str_contains($msg, 'appointment') || str_contains($msg, 'reminder')) {
            return '⏰';
        } elseif (str_contains($msg, 'case') || str_contains($msg, 'violation')) {
            return '⚠️';
        } elseif (str_contains($msg, 'announcement')) {
            return '📢'; 
        }

        return 'ℹ️'; 
    }
}
