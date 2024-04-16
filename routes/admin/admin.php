<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;



Route::get('/admin/weather/forcast', [WeatherController::class, 'forecast'])->middleware('auth');
Route::get('/admin/notifications', [GetNotifications::class, 'index'])->middleware('auth');
