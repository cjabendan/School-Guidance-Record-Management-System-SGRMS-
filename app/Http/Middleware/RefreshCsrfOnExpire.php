<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;

class RefreshCsrfOnExpire
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);

            if ($response->getStatusCode() === 419) {
                Session::flush();
                Session::regenerate(true);

                return redirect()->route('landing')
                    ->with('message', 'Your session has expired. Please log in again.');
            }

            return $response;
            
        } catch (TokenMismatchException $e) {
            Session::flush();
            Session::regenerate(true);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired. Please refresh the page.'], 419);
            }

            return redirect()->route('landing')
                ->with('message', 'Your session expired. Please log in again.');
        }
    }
}
