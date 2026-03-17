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
use App\Observers\LogsModelActivityObserver;

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

        // Centralized activity logging for key modules (CRUD)
        // Add/remove models here to expand coverage.
        \App\Models\User::observe(LogsModelActivityObserver::class);
        \App\Models\Appointments::observe(LogsModelActivityObserver::class);
        \App\Models\AppointmentReschedule::observe(LogsModelActivityObserver::class);
        \App\Models\AppointmentStudent::observe(LogsModelActivityObserver::class);
        \App\Models\CaseModel::observe(LogsModelActivityObserver::class);
        \App\Models\CaseStudent::observe(LogsModelActivityObserver::class);
        \App\Models\CounselingNotes::observe(LogsModelActivityObserver::class);
        \App\Models\ParentStudent::observe(LogsModelActivityObserver::class);
        \App\Models\ParentLinkRequest::observe(LogsModelActivityObserver::class);
        \App\Models\ParentLinkRequestStudent::observe(LogsModelActivityObserver::class);
        \App\Models\Announcements::observe(LogsModelActivityObserver::class);

        Route::aliasMiddleware('role.head', IsHead::class);
        Route::aliasMiddleware('role.counselor', IsCounselor::class);
        Route::aliasMiddleware('role.parent', IsParent::class);
        Route::aliasMiddleware('role.student', IsStudent::class);
        Route::aliasMiddleware('auth', Authenticate::class);
    }
}
