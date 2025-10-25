<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


// done rename

class IsStudent
{
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::check() && Auth::user()->role === 'student') {
            return $next($request);
        }

        if (Auth::check()) {
            $userRole = Auth::user()->role;

            $roleRouteMap = [
                'admin'     => 'Head',
                'counselor' => 'Counselor',
                'parent'    => 'Parent',
                'student'   => 'Student',
            ];

            $routePrefix = $roleRouteMap[$userRole] ?? ucfirst($userRole);

            return redirect()->route("{$routePrefix}.dashboard")->with('error', 'You do not have permission to view that page.');
        }


        return redirect('/')->with('error', 'Please log in.');
    }
}
