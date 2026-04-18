<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| 1. الصفحات العامة (Landing Pages)
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/support', [PageController::class, 'support'])->name('support');
Route::get('/conditions', [PageController::class, 'conditions'])->name('conditions');
Route::post('/contact/store', [PageController::class, 'storeContact'])->name('contact.store');
Route::post('/ticket/store', [PageController::class, 'storeTicket'])->name('ticket.store');

/*
|--------------------------------------------------------------------------
| 2. صفحات تسجيل الدخول (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgetPassword'])->name('password.request');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 3. منطقة الطالب المحمية (Student Module)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'student.only'])->prefix('student')->group(function () {
    // شاشة اختيار المسار (الرئيسية)
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    // خريطة التعلم لمادة معينة
    Route::get('/pathway/{subject}', [StudentController::class, 'pathway'])->name('student.pathway');
    // غرفة المشاهدة لدرس معين
    Route::get('/viewing-room/{lesson}', [StudentController::class, 'viewingRoom'])->name('student.view');
    // الأسئلة والتقييم
    Route::get('/quiz/{lesson}', [StudentController::class, 'quiz'])->name('student.quiz');
    Route::post('/student/quiz/submit', [StudentController::class, 'submitQuiz'])->name('student.quiz.submit');
    // الإنجازات والملف الشخصي
    Route::get('/achievements', [StudentController::class, 'achievements'])->name('student.achievements');
    Route::get('/profile', [StudentController::class, 'profile'])->name('student.profile');
    Route::post('/profile/update', [StudentController::class, 'updateProfile'])->name('student.profile.update');

    Route::post('/student/video/complete', [StudentController::class, 'recordVideoWatch'])->name('video.complete');
});
