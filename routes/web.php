<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\StaffController;
use App\Http\Controllers\AnnouncementsController;

use App\Http\Controllers\Head\HeadDashboardController;
use App\Http\Controllers\Head\HeadCounselorController;
use App\Http\Controllers\Head\HeadStudentController;
use App\Http\Controllers\Head\HeadParentController; 
use App\Http\Controllers\Head\HeadReportController;
use App\Http\Controllers\Head\HeadAppointmentController;
use App\Http\Controllers\Head\HeadSettingsController;

use App\Http\Controllers\Counselor\CounselorDashboardController;


// ========================= Landing Page - Staffs ============================= //
Route::get('/', [StaffController::class, 'index']);

// ========================= Landing Page - Announcements ============================= //
Route::get('/announcements', [AnnouncementsController::class, 'index'])->name('announcements.index');

// =========================== Authentication Routes ===================================== //
Route::middleware('web')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::get('register', [RegisterController::class, 'showForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Verification routes
Route::get('/verify', [RegisterController::class, 'showVerificationForm'])->name('verify.code');
Route::post('/verify', [RegisterController::class, 'verifyCode'])->name('verify.code.submit');

Route::get('/verify', function () {
    return view('auth.verify_code');
})->name('verify.code');


Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// =========================== Dashboard Routes ===================================== //
Route::middleware('auth')->group(function () {
    Route::get('/Head/dashboard', [HeadDashboardController::class, 'dashboard'])->name('Head.dashboard');
    Route::get('/Counselor/dashboard', [CounselorDashboardController::class, 'dashboard'])->name('Counselor.dashboard');
});

// ========================== Administrator (Head) Routes ================================ //
Route::prefix('Head')->name('Head.')->group(function () {

    // Counselors
    Route::get('/counselors', [HeadCounselorController::class, 'index'])->name('counselors.index');
    Route::post('/counselors', [HeadCounselorController::class, 'store'])->name('counselors.store');
    Route::get('/counselors/{id}', [HeadCounselorController::class, 'show'])->name('counselors.show');
    Route::put('/counselors/{id}', [HeadCounselorController::class, 'update'])->name('counselors.update');

    // Students
    Route::get('/students', [HeadStudentController::class, 'index'])->name('students.index');
    Route::get('/students/next-id', [HeadStudentController::class, 'getNextStudentId'])->name('students.next-id');
    Route::post('/students', [HeadStudentController::class, 'addStudent'])->name('students.store');
    Route::post('/students/import', [HeadStudentController::class, 'import'])->name('students.import');
    Route::get('/students/{id_num}/json', [HeadStudentController::class, 'showAjax'])->name('students.show-json');
    Route::put('/students/{id_num}', [HeadStudentController::class, 'editStudent'])->name('students.update');

    // Parents
    Route::get('/parents', [HeadParentController::class, 'index'])->name('parents.index');

    // Reports, appointments, settings
    Route::get('/reports', [HeadReportController::class, 'index'])->name('reports.index');
    Route::get('/appointments', [HeadAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/settings', [HeadSettingsController::class, 'index'])->name('settings.index');
});

// ============================== Counselor Routes ==================================== //
Route::prefix('Counselor')->name('Counselor.')->group(function () {
    
});
