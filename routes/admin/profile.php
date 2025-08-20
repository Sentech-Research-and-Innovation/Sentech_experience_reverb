<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Profile\ProfileController;


Route::get('/profile/index', [ProfileController::class, 'index']);
Route::post('/profile/update', [ProfileController::class, 'update'])->middleware('auth');
Route::post('/profile/update/password', [ProfileController::class, 'updatePassword']);
Route::get('/profile/{id}', [ProfileController::class, 'show']);
Route::post('/upload-profile-image', [ProfileController::class, 'uploadProfileImage']);
Route::delete('/delete-profile-image', [ProfileController::class, 'deleteProfileImage']);
