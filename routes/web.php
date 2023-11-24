<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\System\MenuItem;

use App\Http\Controllers\Web\WeatherController;


Route::get('/', function () {
    return Inertia::render('Web/Index', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
})->name('landing');


Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth'])->name('dashboard');


Route::get('/web/weather', [WeatherController::class, 'create']);
Route::get('/admin/weather/forcast', [WeatherController::class, 'forecast'])->middleware('auth');


//MenuItem::inertia();


require __DIR__ . '/auth.php';

require __DIR__ . '/admin/sentiments-Analysis.php';
require __DIR__ . '/admin/predictive-maintenance.php';
require __DIR__ . '/admin/roles.php';
require __DIR__ . '/admin/company.php';
