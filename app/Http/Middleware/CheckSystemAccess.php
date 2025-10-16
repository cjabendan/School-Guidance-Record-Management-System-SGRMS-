<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

class CheckSystemAccess
{
    public function handle($request, Closure $next)
    {
        $maintenance = SystemSetting::getValue('maintenance_mode', 'off');
        $registration = SystemSetting::getValue('registration', 'on');

        // Maintenance mode check
        if ($maintenance === 'on' && (!Auth::check() || Auth::user()->role !== 'admin')) {
            return response()->view('maintenance');
        }

        // Registration check
        if ($registration === 'off' && $request->is('register') || $request->is('register/*')) {
            return response()->view('maintenance', [
                'message' => 'Registration is currently disabled by the administrator.'
            ]);
        }

        return $next($request);
    }
}
