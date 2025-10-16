<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Controller Imports
|--------------------------------------------------------------------------
*/

// Models
use App\Models\ChatbotSession;

// Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;

// Global / Shared Controllers
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\RagController;

// Head Controllers
use App\Http\Controllers\Head\HeadDashboardController;
use App\Http\Controllers\Head\HeadCounselorController;
use App\Http\Controllers\Head\HeadStudentController;
use App\Http\Controllers\Head\HeadParentController;
use App\Http\Controllers\Head\HeadCaseController;
use App\Http\Controllers\Head\HeadCounselingController;
use App\Http\Controllers\Head\HeadMessageController;
use App\Http\Controllers\Head\HeadRequestController;
use App\Http\Controllers\Head\HeadAppointmentController;
use App\Http\Controllers\Head\HeadAnnouncementController;
use App\Http\Controllers\Head\HeadSettingsController;

// Counselor Controllers
use App\Http\Controllers\Counselor\CounselorDashboardController;
use App\Http\Controllers\Counselor\CounselorMessageController;
use App\Http\Controllers\Counselor\CounselorAppointmentController;

// Parent Controllers
use App\Http\Controllers\Parents\ParentDashboardController;
use App\Http\Controllers\Parents\ParentAppointmentController;
use App\Http\Controllers\Parents\ParentChildController;
use App\Http\Controllers\Parents\ParentRequestController;
use App\Http\Controllers\Parents\ParentMessageController;

// Student Controllers
use App\Http\Controllers\Student\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [StaffController::class, 'index'])->name('landing');

// Announcements
Route::prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/', [AnnouncementsController::class, 'index'])->name('index');
    Route::get('/view/{id}', [AnnouncementsController::class, 'view'])->name('view');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('register', 'showForm')->name('register');
    Route::post('register', 'register');
    Route::get('/activate/{token}', 'activate')->name('activate');
    Route::get('/verify-email', 'showVerificationEmail')->name('verify');
    Route::get('/success-verification', 'showSuccessEmail')->name('success');
    Route::post('/verification/resend', 'resendActivationLink')->name('verification.resend');
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Chatbot and RAG
Route::post('/rag/docs', [RagController::class, 'store']) ->name('rag.store');
Route::post('/chatbot/generate', [ChatbotController::class, 'generateResponse'])
->middleware('normalize.chat')
->name('chatbot.generate');

Route::post('/chatbot/clear', function () {
    $sessionId = session()->getId();
    ChatbotSession::where('session_id', $sessionId)->delete();
    return response()->json(['message' => 'Chat memory cleared.']);
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

use App\Livewire\Settings;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\TwoFactorChallenge;


Route::get('/two-factor-challenge', TwoFactorChallenge::class)->name('two-factor-challenge');

Route::middleware(['auth', 'system.access'])->group(function () {

    // General Settings
    Route::get('/settings', Settings::class)->name('settings');

    Route::get('/confirm-password', ConfirmPassword::class)
        ->name('confirm-password');


    // Notifications
    Route::get('/notify/fetch', [NotifyController::class, 'fetchNotifications'])->name('notify.fetch');
    Route::post('/notify/mark-as-read', [NotifyController::class, 'markAsRead'])->name('notify.markAsRead');
    Route::get('/Head/notify/notification', [NotifyController::class, 'index'])
        ->middleware('role.head')
        ->name('Head.notify.notification');
    Route::get('/Counselor/notify/notification', [NotifyController::class, 'index'])
        ->middleware('role.counselor')
        ->name('Counselor.notify.notification');
    Route::get('/Parent/notify/notification', [NotifyController::class, 'index'])
        ->middleware('role.parent')
        ->name('Parent.notify.notification');
    Route::get('/Student/notify/notification', [NotifyController::class, 'index'])
        ->middleware('role.student')
        ->name('Student.notify.notification');

    // Role-based Dashboards
    Route::get('/Head/dashboard', [HeadDashboardController::class, 'dashboard'])
        ->middleware('role.head')->name('Head.dashboard');

    Route::get('/Counselor/dashboard', [CounselorDashboardController::class, 'dashboard'])
        ->middleware('role.counselor')->name('Counselor.dashboard');

    Route::get('/Parent/dashboard', [ParentDashboardController::class, 'dashboard'])
        ->middleware('role.parent')->name('Parent.dashboard');

    Route::get('/Student/dashboard', [StudentDashboardController::class, 'dashboard'])
        ->middleware('role.student')
        ->name('Student.dashboard');
});


/*
|--------------------------------------------------------------------------
| Head (Administrator) Routes
|--------------------------------------------------------------------------
*/

Route::prefix('Head')->name('Head.')->middleware(['auth', 'role.head'])->group(function () {

    // Dashboard


    // Counselors
    Route::prefix('counselors')->name('counselors.')->group(function () {
        Route::get('/', [HeadCounselorController::class, 'index'])->name('index');
        Route::post('/', [HeadCounselorController::class, 'store'])->name('store');
        Route::get('/next-id', [HeadCounselorController::class, 'getNextCounselorId'])->name('next-id');
        Route::get('/{c_id}/json', [HeadCounselorController::class, 'showAjax']);
        Route::get('/{id}', [HeadCounselorController::class, 'show'])->name('show');
        Route::put('/{id}', [HeadCounselorController::class, 'update'])->name('update');
        Route::post('/{c_id}/archive', [HeadCounselorController::class, 'archive'])->name('archive');
        Route::post('/{c_id}/activate', [HeadCounselorController::class, 'activate'])->name('activate');
    });

    // Students
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [HeadStudentController::class, 'index'])->name('index');
        Route::post('/', [HeadStudentController::class, 'addStudent'])->name('store');
        Route::get('/export', [HeadStudentController::class, 'export'])->name('export');
        Route::get('/next-id', [HeadStudentController::class, 'getNextStudentId'])->name('next-id');
        Route::post('/import', [HeadStudentController::class, 'import'])->name('import');
        Route::get('/{id_num}/json', [HeadStudentController::class, 'showAjax'])->name('show-json');
        Route::put('/{id_num}', [HeadStudentController::class, 'editStudent'])->name('update');
        Route::post('/archive', [HeadStudentController::class, 'archive'])->name('archive');
        Route::post('/archive-disable', [HeadStudentController::class, 'archiveAndDisableStudent'])->name('archive-disable');
        Route::get('/{s_id}/cases', [HeadStudentController::class, 'getStudentCases']);
        Route::get('/search', [HeadCaseController::class, 'searchStudent'])->name('search');
        Route::get('/Head/students/table', [HeadStudentController::class, 'partialTable']);
    });

    // Parents
    Route::prefix('parents')->name('parents.')->group(function () {
        Route::get('/', [HeadParentController::class, 'index'])->name('index');
        Route::post('/add', [HeadParentController::class, 'store'])->name('add');
        Route::get('/{id}/get', [HeadParentController::class, 'get'])->name('get');
        Route::post('/{id}/update', [HeadParentController::class, 'update'])->name('update');
    });

    // Cases
    Route::prefix('cases')->name('cases.')->group(function () {
        Route::get('/', [HeadCaseController::class, 'index'])->name('index');
        Route::post('/add', [HeadCaseController::class, 'store'])->name('store');
        Route::put('/{case}', [HeadCaseController::class, 'update'])->name('update');
        Route::put('/{case}/archive', [HeadCaseController::class, 'archive'])->name('archive');
        Route::post('/import', [HeadCaseController::class, 'import'])->name('import');
        Route::get('/export', [HeadCaseController::class, 'export'])->name('export');
    });

    // Counseling
    Route::get('/counseling', [HeadCounselingController::class, 'index'])->name('counseling.index');

    // Messages
    Route::get('/messages/{user?}', [HeadMessageController::class, 'index'])->name('messages');


    // Requests
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [HeadRequestController::class, 'index'])->name('index');
        Route::get('/{type}/{id}', [HeadRequestController::class, 'show'])->name('show');
        Route::post('/{type}/{id}/approve', [HeadRequestController::class, 'approve'])->name('approve');
        Route::post('/{type}/{id}/reject', [HeadRequestController::class, 'reject'])->name('reject');
    });

    // Appointments
    Route::get('/appointments', [HeadAppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/move', [HeadAppointmentController::class, 'move'])->name('appointments.move');
    Route::post('/appointments/{id}/approve', [HeadAppointmentController::class, 'approve'])->name('appointments.approve');
    Route::post('/appointments/{id}/decline', [HeadAppointmentController::class, 'decline'])->name('appointments.decline');
    Route::post('/appointments/store', [HeadAppointmentController::class, 'store'])->name('appointments.store');
      // Update appointment (reschedule/edit)
    Route::put('/appointments/{id}', [HeadAppointmentController::class, 'update'])->name('appointments.update');
    Route::get('/appointments/{id}/json', [HeadAppointmentController::class, 'json'])->name('appointments.json');
    // Allow moving appointments via drag-and-drop in calendar for Head
    Route::post('/appointments/store', [HeadAppointmentController::class, 'store'])->name('appointments.store');


    // Announcements
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [HeadAnnouncementController::class, 'index'])->name('index');
        Route::post('/', [HeadAnnouncementController::class, 'store'])->name('store');
        Route::put('/{announcement}', [HeadAnnouncementController::class, 'update'])->name('update');
        Route::get('/api/events', [HeadAnnouncementController::class, 'getEvents'])->name('api');
    });
});


/*
|--------------------------------------------------------------------------
| Counselor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('Counselor')->name('Counselor.')->middleware(['auth', 'role.counselor', 'system.access'])->group(function () {

    Route::get('/messages/{user?}', [CounselorMessageController::class, 'index'])
    ->middleware('feature:chat')
    ->name('messages');

    Route::get('/appointments', [CounselorDashboardController::class, 'appointments'])
    ->middleware('feature:appointment')
    ->name('appointments.index');
    Route::post('/appointments/{id}/move', [CounselorDashboardController::class, 'move'])->name('appointments.move');
    Route::post('/appointments', [CounselorAppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/{id}/approve', [CounselorDashboardController::class, 'approve'])->name('appointments.approve');
    Route::post('/appointments/{id}/decline', [CounselorDashboardController::class, 'decline'])->name('appointments.decline');
    Route::post('/appointments', [\App\Http\Controllers\Counselor\CounselorAppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/appointments/{id}/json', [\App\Http\Controllers\Counselor\CounselorAppointmentController::class, 'json'])->name('appointments.json');
    Route::put('/appointments/{id}', [\App\Http\Controllers\Counselor\CounselorAppointmentController::class, 'update'])->name('appointments.update');
});


/*
|--------------------------------------------------------------------------
| Parent Routes
|--------------------------------------------------------------------------
*/

Route::prefix('Parent')->name('Parent.')->middleware(['auth', 'role.parent', 'system.access'])->group(function () {

    // Child Management
    Route::get('/child', [ParentChildController::class, 'index'])->name('child.index');
    Route::post('/children/link-request', [ParentChildController::class, 'sendLinkRequest'])->name('link.request');
    Route::get('/children/search-students', [ParentChildController::class, 'searchStudents'])->name('search.students');

    // Messages
    Route::get('/messages/{user?}', [ParentMessageController::class, 'index'])
     ->middleware('feature:chat')
     ->name('messages');

    // Requests
    Route::get('/requests', [ParentRequestController::class, 'index'])
    ->middleware('feature:request')
    ->name('requests.index');

    //Appointments
    Route::get('/appointments', [ParentAppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [ParentAppointmentController::class, 'store'])->name('appointments.store');
});


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::prefix('Student')->name('Student.')->middleware(['auth', 'role.student', 'system.access'])->group(function () {
    // Future student-specific routes can be added here
});