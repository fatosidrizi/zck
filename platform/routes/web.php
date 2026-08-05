<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NgoController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PublicCallController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Static pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSend'])->name('contact.send')->middleware('throttle:5,1');

// News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Public Calls
Route::get('/public-calls', [PublicCallController::class, 'index'])->name('public-calls.index');
Route::get('/public-calls/{slug}', [PublicCallController::class, 'show'])->name('public-calls.show');

// NGOs
Route::get('/ngos', [NgoController::class, 'index'])->name('ngos.index');
Route::get('/ngos/{slug}', [NgoController::class, 'show'])->name('ngos.show');

// Communities
Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
Route::get('/communities/{slug}', [CommunityController::class, 'show'])->name('communities.show');

// Events
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// NGO Registration
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store')->middleware('throttle:3,1');

// Discrimination Reports
Route::get('/report', [ReportController::class, 'create'])->name('reports.create');
Route::post('/report', [ReportController::class, 'store'])->name('reports.store')->middleware('throttle:5,1');
Route::get('/track-report', [ReportController::class, 'track'])->name('reports.track');

// Auth: Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
    Route::get('/signup', [AuthController::class, 'showRegister'])->name('user.register');
    Route::post('/signup', [AuthController::class, 'register'])->name('user.register.post')->middleware('throttle:3,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Auth: Authenticated only
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Sitemap
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
