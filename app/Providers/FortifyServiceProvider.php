<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Features;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Disable all default Fortify routes (login, register, password reset, confirm-password)
        Fortify::ignoreRoutes();

        // Enable only 2FA
        config(['fortify.features' => [
            Features::twoFactorAuthentication(),
        ]]);

        // Rate limiting for 2FA challenge
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Optional: you can also add login rate limiter if you want to protect your Livewire login
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

     
       
    }
}
