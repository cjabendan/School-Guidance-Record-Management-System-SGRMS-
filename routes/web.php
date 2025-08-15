<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Head\CounselorController;
use App\Http\Controllers\Head\ProfilingController;

use App\Http\Controllers\Head\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffController;


Route::get('/', function () {
    return view('landing');
});

Route::get('/', [StaffController::class, 'index']);

Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});
Route::get('register', [RegisterController::class, 'showForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::prefix('Head')->group(function () {
    Route::get('/dashboard', [HeadController::class, 'dashboard'])->name('Head.dashboard');
    Route::get('/appointments', [HeadController::class, 'appointments'])->name('Head.appointments');
    Route::get('/counselors', [ProfilingController::class, 'counselors'])->name('Head.profiling.counselors');
    Route::get('/students', [ProfilingController::class, 'students'])->name('Head.profiling.students');
    Route::get('/parents', [HeadController::class, 'parents'])->name('Head.profilings.parents');
    Route::get('/reports', [HeadController::class, 'reports'])->name('Head.reports');
    Route::get('/settings', [HeadController::class, 'settings'])->name('Head.settings');

    Route::post('/addcounsel', [CounselorController::class, 'store'])->name('addcounsel');
    Route::get('/counselors/{id}', [CounselorController::class, 'show']);
    Route::post('/updatecounsel', [CounselorController::class, 'update'])->name('updatecounsel');


    Route::get('/students/next-id', [StudentController::class, 'getNextStudentId']);
    Route::post('/students/add', [StudentController::class, 'addStudent'])->name('students.add');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/{id_num}/json', [StudentController::class, 'showAjax']);
    Route::post('Head/students/{id_num}/edit', [StudentController::class, 'editStudent']);
});

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
