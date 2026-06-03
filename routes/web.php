<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PushController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('feed.index'));

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class,'showLogin'])->name('login');
    Route::post('/login', [AuthController::class,'login'])->name('login.submit');
});
Route::post('/logout', [AuthController::class,'logout'])->name('logout');

// App
Route::middleware('auth')->group(function () {
    // Feed
    Route::get('/feed', [PostController::class,'index'])->name('feed.index');

    // Modules
    Route::view('/modules','modules.index')->name('modules.index');

    // Posts
    Route::get('/posts/create',        [PostController::class,'create'])->name('posts.create');
    Route::post('/posts',              [PostController::class,'store'])->name('posts.store');
    Route::get('/posts/{post}',        [PostController::class,'show'])->name('posts.show');
    Route::patch('/posts/{post}/status',[PostController::class,'updateStatus'])->name('posts.status');
    Route::post('/posts/{post}/report', [PostController::class,'report'])->name('posts.report');

    // Responses
    Route::post('/posts/{post}/responses',        [ResponseController::class,'store'])->name('responses.store');
    Route::patch('/responses/{response}/accept',  [ResponseController::class,'accept'])->name('responses.accept');
    Route::patch('/responses/{response}/decline', [ResponseController::class,'decline'])->name('responses.decline');

    // Reviews
    Route::post('/posts/{post}/reviews', [ReviewController::class,'store'])->name('reviews.store');

    // Chat
    Route::get('/chat',                     [ChatController::class,'index'])->name('chat.index');
    Route::post('/chat/start/{post}',       [ChatController::class,'start'])->name('chat.start');
    Route::get('/chat/{conversation}',      [ChatController::class,'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send',[ChatController::class,'send'])->name('chat.send');
    Route::get('/chat/{conversation}/poll', [ChatController::class,'poll'])->name('chat.poll');
    Route::get('/chat/{conversation}/stream',[ChatController::class,'stream'])->name('chat.stream');

    // Profile
    Route::get('/profile',               [ProfileController::class,'show'])->name('profile.show');
    Route::patch('/profile',             [ProfileController::class,'updateProfile'])->name('profile.update');
    Route::patch('/profile/location',    [ProfileController::class,'updateLocation'])->name('profile.location');
    Route::post('/profile/avatar',       [ProfileController::class,'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/verify',       [ProfileController::class,'verifyIdentity'])->name('profile.verify');
    Route::post('/profile/onboarded',    [ProfileController::class,'completeOnboarding'])->name('profile.onboarded');

    // Push Notifications
    Route::post('/push/subscribe',   [PushController::class,'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushController::class,'unsubscribe'])->name('push.unsubscribe');
});

// Admin
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',               [AdminController::class,'dashboard'])->name('dashboard');
    Route::get('/users',          [AdminController::class,'users'])->name('users');
    Route::post('/users/{user}/ban', [AdminController::class,'banUser'])->name('users.ban');
    Route::get('/posts',          [AdminController::class,'posts'])->name('posts');
    Route::delete('/posts/{post}',[AdminController::class,'deletePost'])->name('posts.delete');
    Route::get('/reports',        [AdminController::class,'reports'])->name('reports');
    Route::patch('/reports/{report}',[AdminController::class,'reviewReport'])->name('reports.review');
});
