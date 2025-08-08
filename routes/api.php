<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;

use App\Models\Company;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::get('/user', [AuthenticatedSessionController::class, 'user'])->middleware('auth:sanctum');
Route::post('/logout', [AuthenticatedSessionController::class, 'logoutMobile'])->middleware('auth:sanctum');

///adding the clocking system

Route::get('/notifications/unread-count', function () {
    return response()->json([
        'count' => Notification::whereJsonContains('model_ids->to_company_id', 1)->count()
    ]);
});

/////

//asdding something to the dashboard



Route::middleware('auth:sanctum')->group(function () {
    require __DIR__ . '/admin/roles.php';
    require __DIR__ . '/admin/sentiments-Analysis.php';
    require __DIR__ . '/admin/dashboard.php';
    require __DIR__ . '/admin/admin.php';
    require __DIR__ . '/admin/predictive-maintenance.php';
    require __DIR__ . '/admin/company.php';
    require __DIR__ . '/admin/profile.php';
});
