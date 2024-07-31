<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;
use App\Http\Controllers\Web\NetworkController;
use App\Http\Controllers\Web\WebController;



Route::get('/', function () {
    return Inertia::render('Web/Index', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
})->name('landing');

// Route::get('/contactus', function () {
//     return Inertia::render('Web/contactus');
// })->name('contactus');


Route::get('/contactus', [WebController::class, 'contactus']);
Route::post('/feedback', [WebController::class, 'feedback']);


Route::get('/web/weather', [WeatherController::class, 'create']);
Route::get('/web/weather/forcast', [WeatherController::class, 'forecast']);



Route::get('/change_password/{token}', function () {
    return Inertia::render('Auth/changePassword');
})->name('changePassword');


Route::get('/web/network/status', [NetworkController::class, 'show']);
Route::get('/web/network/index', [NetworkController::class, 'index']);

Route::get('/web/network/province/cities/{province}', [NetworkController::class, 'provinceCities']);
Route::get('/web/network/alarms/{province}', [NetworkController::class, 'getAlarmsDataByProvince']);




require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {
    require __DIR__ . '/admin/admin.php';
    require __DIR__ . '/admin/sentiments-Analysis.php';
    require __DIR__ . '/admin/predictive-maintenance.php';
    require __DIR__ . '/admin/roles.php';
    require __DIR__ . '/admin/company.php';
    require __DIR__ . '/admin/reports.php';
    require __DIR__ . '/admin/profile.php';
    require __DIR__ . '/admin/dashboard.php';
});
