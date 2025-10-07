<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;

// Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginController;

// Global/Shared Controllers
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AnnouncementsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifyController;

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

// Parent Controllers
use App\Http\Controllers\Parents\ParentDashboardController;
use App\Http\Controllers\Parents\ParentChildController;
use App\Http\Controllers\Parents\ParentRequestController;
use App\Http\Controllers\Parents\ParentMessageController;

// Chatbot Controller
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\RagController;

Route::post('/rag/docs', [RagController::class, 'store']);
Route::post('/chatbot/generate', [ChatbotController::class, 'generateResponse'])->name('chatbot.generate');

/*
|--------------------------------------------------------------------------
| Public Routes (Landing, Announcements, Auth)
|--------------------------------------------------------------------------
*/

Route::get('/', [StaffController::class, 'index']);

// Announcements
Route::prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/', [AnnouncementsController::class, 'index'])->name('index');
    Route::get('/view/{id}', [AnnouncementsController::class, 'view'])->name('view');
});

// Authentication
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']); 

Route::get('register', [RegisterController::class, 'showForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Email Verification
Route::get('/activate/{token}', [RegisterController::class, 'activate'])->name('activate');
Route::get('/verify-email', [RegisterController::class, 'showVerificationEmail'])->name('verify');
Route::get('/success-verification', [RegisterController::class, 'showSuccessEmail'])->name('success');
Route::post('/verification/resend', [RegisterController::class, 'resendActivationLink'])->name('verification.resend');

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Role-Protected Dashboards & Notifications)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    
    // Dashboard Routes with specific role middleware for protection
    Route::get('/Head/dashboard', [HeadDashboardController::class, 'dashboard'])
        ->middleware('role.head')
        ->name('Head.dashboard');
        
    Route::get('/Counselor/dashboard', [CounselorDashboardController::class, 'dashboard'])
        ->middleware('role.counselor') 
        ->name('Counselor.dashboard');
        
    Route::get('/Parent/dashboard', [ParentDashboardController::class, 'dashboard'])
        ->middleware('role.parent')
        ->name('Parent.dashboard');

    // Notifications
    Route::get('/notify/fetch', [NotifyController::class, 'fetchNotifications'])->name('notify.fetch');
    Route::post('/notify/mark-as-read', [NotifyController::class, 'markAsRead'])->name('notify.markAsRead');
    Route::get('/Head/notify/notification', [NotifyController::class, 'index'])
        ->middleware('role.head')
        ->name('Head.notify.notification');
});


/*
|--------------------------------------------------------------------------
| Head (Administrator) Routes
|--------------------------------------------------------------------------
| Uses 'auth' and 'role.head' middleware to prevent unauthorized access.
*/

Route::prefix('Head')->name('Head.')->middleware(['auth', 'role.head'])->group(function () {

    // Dashboard
    Route::get('/messages/{user?}', [HeadMessageController::class, 'index'])->name('messages');

    // Counselors
    Route::get('/counselors', [HeadCounselorController::class, 'index'])->name('counselors.index');
    Route::post('/counselors', [HeadCounselorController::class, 'store'])->name('counselors.store');
    Route::get('/counselors/{c_id}/json', [HeadCounselorController::class, 'showAjax']);
    Route::get('/counselors/next-id', [HeadCounselorController::class, 'getNextCounselorId'])->name('counselors.next-id');
    Route::get('/counselors/{id}', [HeadCounselorController::class, 'show'])->name('counselors.show');
    Route::put('/counselors/{id}', [HeadCounselorController::class, 'update'])->name('counselors.update');
    Route::post('/counselors/{c_id}/archive', [HeadCounselorController::class, 'archive'])->name('counselors.archive');
    Route::post('/counselors/{c_id}/activate', [HeadCounselorController::class, 'activate'])->name('counselors.activate');

    // Students
    Route::get('/students', [HeadStudentController::class, 'index'])->name('students.index');
    Route::get('/students/export', [HeadStudentController::class, 'export'])->name('students.export');
    Route::get('/students/next-id', [HeadStudentController::class, 'getNextStudentId'])->name('students.next-id');
    Route::post('/students', [HeadStudentController::class, 'addStudent'])->name('students.store');
    Route::post('/students/import', [HeadStudentController::class, 'import'])->name('students.import');
    Route::get('/students/{id_num}/json', [HeadStudentController::class, 'showAjax'])->name('students.show-json');
    Route::put('/students/{id_num}', [HeadStudentController::class, 'editStudent'])->name('students.update');
    Route::post('/students/archive', [HeadStudentController::class, 'archive'])->name('students.archive');
    Route::post('/students/archive-disable', [HeadStudentController::class, 'archiveAndDisableStudent'])->name('students.archive-disable');
    Route::get('/students/{s_id}/cases', [HeadStudentController::class, 'getStudentCases']);

    // Parents
    Route::get('/parents', [HeadParentController::class, 'index'])->name('parents.index');
    Route::post('/parents/add', [HeadParentController::class, 'store'])->name('parents.add');
    Route::get('/parents/{id}/get', [HeadParentController::class, 'get'])->name('parents.get');
    Route::post('/parents/{id}/update', [HeadParentController::class, 'update'])->name('parents.update');


    // Cases
    Route::get('/cases', [HeadCaseController::class, 'index'])->name('cases.index');
    Route::post('/addcase', [HeadCaseController::class, 'store'])->name('cases.store');
    Route::put('/cases/{case}', [HeadCaseController::class, 'update'])->name('cases.update');
    Route::put('/cases/{case}/archive', [HeadCaseController::class, 'archive'])->name('cases.archive');
    Route::get('/students/search', [HeadCaseController::class, 'searchStudent'])->name('students.search');

    // Cases Import/Export
    Route::post('/cases/import', [HeadCaseController::class, 'import'])->name('cases.import');
    Route::get('/cases/export', [HeadCaseController::class, 'export'])->name('cases.export');

    // Counseling
    Route::get('/counseling', [HeadCounselingController::class, 'index'])->name('counseling.index');


    // Messages
    Route::get('/messages', [HeadMessageController::class, 'index'])->name('messages.index');
    
    // Requests
    Route::get('/requests', [HeadRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{type}/{id}', [HeadRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{type}/{id}/approve', [HeadRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{type}/{id}/reject', [HeadRequestController::class, 'reject'])->name('requests.reject');

    // Appointments
    Route::get('/appointments', [HeadAppointmentController::class, 'index'])->name('appointments.index');

    // Announcements
    Route::get('/announcements', [HeadAnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [HeadAnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/api/announcements', [HeadAnnouncementController::class, 'getEvents'])->name('announcements.api');
    Route::put('/announcements/{announcement}', [HeadAnnouncementController::class, 'update'])->name('announcements.update');

   


});


/*
|--------------------------------------------------------------------------
| Counselor Routes
|--------------------------------------------------------------------------
| Uses 'auth' and 'role.counselor' middleware to prevent unauthorized access.
*/

Route::prefix('Counselor')->name('Counselor.')->middleware(['auth', 'role.counselor'])->group(function () {

    // Messages
    Route::get('/messages', [CounselorMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/search-users', [CounselorMessageController::class, 'searchUsers'])->name('messages.search-users');
    Route::post('/messages/conversations/{receiverId}', [CounselorMessageController::class, 'startConversation'])->name('messages.start-conversation');
    Route::get('/messages/fetch/{conversationId}', [CounselorMessageController::class, 'fetchConversation'])->name('messages.fetch-conversation');
    Route::post('/messages/send/{conversationId}', [CounselorMessageController::class, 'sendMessage'])->name('messages.send-message');
    Route::post('/messages/mark-as-read/{conversationId}', [CounselorMessageController::class, 'markAsRead']);
});


/*
|--------------------------------------------------------------------------
| Parent Routes
|--------------------------------------------------------------------------
| Uses 'auth' and 'role.parent' middleware to prevent unauthorized access.
*/

Route::prefix('Parent')->name('Parent.')->middleware(['auth', 'role.parent'])->group(function () {

    // Child Management
    Route::get('/child', [ParentChildController::class, 'index'])->name('child.index');
    Route::post('/children/link-request', [ParentChildController::class, 'sendLinkRequest'])->name('link.request');
    Route::get('/children/search-students', [ParentChildController::class, 'searchStudents'])->name('search.students');

    // Messages
    Route::get('/messages', [ParentMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/search-users', [ParentMessageController::class, 'searchUsers'])->name('messages.search-users');
    Route::post('/messages/conversations/{receiverId}', [ParentMessageController::class, 'startConversation'])->name('messages.start-conversation');
    Route::get('/messages/fetch/{conversationId}', [ParentMessageController::class, 'fetchConversation'])->name('messages.fetch-conversation');
    Route::post('/messages/send/{conversationId}', [ParentMessageController::class, 'sendMessage'])->name('messages.send-message');
    Route::post('/messages/mark-as-read/{conversationId}', [ParentMessageController::class, 'markAsRead']);

    // Requests
    Route::get('/requests', [ParentRequestController::class, 'index'])->name('requests.index');
});


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
| Add routes here if you implement a separate Student login/dashboard.
*/

// Currently, there are no specific student routes defined here.

// Note: Ensure you update your `AppServiceProvider` to use the correct middleware aliases:
// 'role.head' (instead of 'role.admin'), 'role.counselor', 'role.parent', 'role.student'.


 // Settings
    Route::get('settings', \App\Livewire\Settings::class)->name('settings');