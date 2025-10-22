<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\ChatController;


Route::get('/profile/index', [ProfileController::class, 'index']);
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth');
Route::post('/profile/update/password', [ProfileController::class, 'updatePassword']);
Route::get('/profile/{id}', [ProfileController::class, 'show']);
Route::post('/profile/upload-profile-image', [ProfileController::class, 'uploadProfileImage']);
Route::delete('/profile/delete-profile-image', [ProfileController::class, 'deleteProfileImage']);
Route::post('/profile/upload-cover-image', [ProfileController::class, 'uploadCoverImage']);
Route::delete('/profile/delete-cover-image', [ProfileController::class, 'deleteCoverImage']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/chat/{receiverId}', [ChatController::class, 'getMessages'])->name('admin.chat.getMessages');
    Route::post('/profile/chat/send', [ChatController::class, 'sendMessage'])->name('admin.chat.sendMessage');
});



