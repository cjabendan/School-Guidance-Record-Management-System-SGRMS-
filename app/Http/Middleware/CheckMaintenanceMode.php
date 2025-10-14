<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $maintenance = SystemSetting::getValue('maintenance_mode', 'off');

        if ($maintenance === 'on' && (!Auth::check() || Auth::user()->role !== 'admin')) {
            return response()->view('maintenance');
        }

        return $next($request);
    }
}
