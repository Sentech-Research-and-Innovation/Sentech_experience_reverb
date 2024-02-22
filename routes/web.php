<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Web\NetworkController;



Route::get('/', function () {
    return Inertia::render('Web/Index', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
})->name('landing');

Route::get('/services', function () {
    return Inertia::render('Web/services');
})->name('services');

Route::get('/aboutus', function () {
    return Inertia::render('Web/aboutus');
})->name('aboutus');

Route::get('/news', function () {
    return Inertia::render('Web/news');
})->name('news');

Route::get('/contactus', function () {
    return Inertia::render('Web/contactus');
})->name('contactus');


Route::get('/admin/dashboard',  [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::post('/admin/activities',  [DashboardController::class, 'show'])->middleware(['auth'])->name('activities');


Route::get('/web/weather', [WeatherController::class, 'create']);
Route::get('/admin/weather/forcast', [WeatherController::class, 'forecast'])->middleware('auth');
Route::get('/admin/notifications', [GetNotifications::class, 'index'])->middleware('auth');


Route::get('/change_password/{token}', function () {
    return Inertia::render('Auth/changePassword');
})->name('changePassword');


Route::get('/web/network/status', [NetworkController::class, 'show']);
Route::get('/web/network/index', [NetworkController::class, 'index']);


require __DIR__ . '/auth.php';

require __DIR__ . '/admin/sentiments-Analysis.php';
require __DIR__ . '/admin/predictive-maintenance.php';
require __DIR__ . '/admin/roles.php';
require __DIR__ . '/admin/company.php';
require __DIR__ . '/admin/reports.php';
require __DIR__ . '/admin/profile.php';
