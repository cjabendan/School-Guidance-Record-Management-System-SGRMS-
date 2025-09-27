<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\IsHead;
use App\Http\Middleware\IsCounselor;
use App\Http\Middleware\IsParent;
use App\Http\Middleware\IsStudent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'Parent' => ParentModel::class,
            'Student' => \App\Models\Student::class,
        ]);

        Route::aliasMiddleware('role.head', IsHead::class);
        Route::aliasMiddleware('role.counselor', IsCounselor::class);
        Route::aliasMiddleware('role.parent', IsParent::class);
        Route::aliasMiddleware('role.student', IsStudent::class);
        Route::aliasMiddleware('auth', Authenticate::class);
    }
}
