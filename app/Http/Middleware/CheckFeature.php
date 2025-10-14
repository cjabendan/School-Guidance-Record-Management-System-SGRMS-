<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Feature;

class CheckFeature
{
    public function handle($request, Closure $next, $feature)
    {
        if (!Feature::isEnabled($feature)) {
            abort(403, 'Feature disabled');
        }
        return $next($request);
    }
}
