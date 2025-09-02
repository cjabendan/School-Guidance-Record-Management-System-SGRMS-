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
use App\Http\Controllers\Head\HeadCaseController;
use App\Http\Controllers\Head\HeadAppointmentController;
use App\Http\Controllers\Head\HeadAnnouncementController;
use App\Http\Controllers\Head\HeadSettingsController;

use App\Http\Controllers\Counselor\CounselorDashboardController;

use App\Http\Controllers\Parents\ParentDashboardController;
use Illuminate\Routing\Events\RouteMatched;


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


// Activation link
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('activate');

Route::get('/verify-email', [RegisterController::class, 'showVerificationEmail'])->name('verify');
Route::get('/success-verification', [RegisterController::class, 'showSuccessEmail'])->name('success');
Route::post('/verification/resend', [RegisterController::class, 'resendActivationLink'])->name('verification.resend');


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

     // Cases
    Route::get('/cases', [HeadCaseController::class, 'index'])->name('cases.index');
    Route::post('/addcase', [HeadCaseController::class, 'store'])->name('cases.store');
    Route::put('/cases/{case}', [HeadCaseController::class, 'update'])->name('cases.update');
    Route::put('/cases/{case}/archive', [HeadCaseController::class, 'archive'])->name('cases.archive');

    Route::get('/appointments', [HeadAppointmentController::class, 'index'])->name('appointments.index');

    // Announcements
    Route::get('/announcements', [HeadAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [HeadAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/api/announcements', [HeadAnnouncementController::class, 'getEvents'])->name('announcements.api');


    Route::get('/settings', [HeadSettingsController::class, 'index'])->name('settings.index');
});

// ============================== Counselor Routes ==================================== //
Route::prefix('Counselor')->name('Counselor.')->group(function () {
    
});



// ============================== Parent Routes ==================================== //
Route::prefix('Parent')->name('Parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'dashboard'])->name('dashboard');
});
