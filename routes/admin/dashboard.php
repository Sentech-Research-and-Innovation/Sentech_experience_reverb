<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;



Route::get('/admin/dashboard',  [DashboardController::class, 'index'])->middleware(['auth']);
Route::post('/admin/activities',  [DashboardController::class, 'show'])->middleware(['auth']);
