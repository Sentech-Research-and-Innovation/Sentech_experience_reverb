<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;




Route::get('/admin/notifications', [GetNotifications::class, 'index'])->middleware('auth');
