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
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\ParentController;

// ========================= Landing Page - Staffs ============================= //
Route::get('/', [StaffController::class, 'index']);


// ========================= Landing Page - Announcements ============================= //
Route::get('/announcements', function () {
    return view('announcement');
});


Route::get('/announcements', [AnnouncementsController::class, 'index'])->name('announcements.index');


// =========================== Authentication Routes ===================================== //
Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});


Route::get('register', [RegisterController::class, 'showForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);


Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


// =============================== Dashboard Routes ==================================== //
Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');


// ==========================  Administrator (Head) Routes ================================ //
Route::prefix('Head')->group(function () {
    Route::get('/dashboard', [HeadController::class, 'dashboard'])->name('Head.dashboard');

    Route::get('/counselors', [HeadController::class, 'index'])->name('Head.counselors');
    Route::post('/addcounsel', [CounselorController::class, 'store'])->name('addcounsel');
    Route::get('/counselors/{id}', [CounselorController::class, 'show']);
    Route::post('/updatecounsel', [CounselorController::class, 'update'])->name('updatecounsel');


    Route::get('/parents', [HeadController::class, 'parents'])->name('Head.profilings.parents');


    Route::get('/students', [StudentController::class, 'index'])->name('Head.profiling.students');
    Route::get('/students/next-id', [StudentController::class, 'getNextStudentId']);
    Route::post('/students/add', [StudentController::class, 'addStudent'])->name('students.add');
    Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('students/{id_num}/json', [StudentController::class, 'showAjax']);
    Route::post('Head/students/{id_num}/edit', [StudentController::class, 'editStudent']);


    Route::get('/counselors', [CounselorController::class, 'index'])->name('Head.profiling.counselors');

    Route::get('/parents', [ParentController::class, 'index'])->name('Head.profiling.parents');


    Route::get('/reports', [HeadController::class, 'reports'])->name('Head.reports');


    Route::get('/appointments', [HeadController::class, 'appointments'])->name('Head.appointments');


    Route::get('/settings', [HeadController::class, 'settings'])->name('Head.settings');
});


// ============================== Counselor Routes ==================================== //
Route::prefix('Counselor')->group(function () {
    Route::get('/dashboard', [CounselorController::class, 'dashboard'])->name('Counselor.dashboard');
});
