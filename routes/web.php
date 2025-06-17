<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\Head\CounselorController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;


Route::get('/', function () {
    return view('landing');
});

Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});
Route::get('register', [RegisterController::class, 'showForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::prefix('Head')->group(function () {
    Route::get('/dashboard', [HeadController::class, 'dashboard'])->name('Head.dashboard');
    Route::get('/appointments', [HeadController::class, 'appointments'])->name('Head.appointments');
    Route::get('/counselors', [HeadController::class, 'counselors'])->name('Head.counselors');
    Route::get('/students', [HeadController::class, 'students'])->name('Head.students');
    Route::get('/parents', [HeadController::class, 'parents'])->name('Head.parents');
    Route::get('/reports', [HeadController::class, 'reports'])->name('Head.reports');
    Route::get('/settings', [HeadController::class, 'settings'])->name('Head.settings');

    Route::post('/addcounsel', [CounselorController::class, 'store'])->name('addcounsel');
    Route::get('/counselor/{id}', [CounselorController::class, 'show'])->name('Head.counselor.show');

});

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    $role = session('role');
    switch ($role) {
        case 'Head Guidance':
            return redirect()->route('Head.dashboard');
        case 'Guidance Counselor':
            return redirect()->route('Head.dashboard');
        case 'Parent':
            return redirect()->route('Head.dashboard');
        case 'Student':
            return redirect()->route('Head.dashboard');

        default:
            return redirect('/');
    }
});
