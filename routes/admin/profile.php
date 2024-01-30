<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Profile\ProfileController;


Route::get('/profile/index', [ProfileController::class, 'index']);
Route::post('/profile/update', [ProfileController::class, 'update']);
Route::post('/profile/update/password', [ProfileController::class, 'updatePassword']);
