<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;
use App\Http\Controllers\Admin\DashboardController;




Route::get('/admin/notifications', [GetNotifications::class, 'index'])->middleware('auth');

Route::get('/admin/dashboard/stats', [DashboardController::class, 'getDashboardStats'])->middleware('auth');

