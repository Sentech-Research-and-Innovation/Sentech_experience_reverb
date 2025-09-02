<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SenTalkController;



Route::get('/admin/dashboard',  [DashboardController::class, 'index'])->middleware(['auth']);
Route::post('/admin/activities',  [DashboardController::class, 'show'])->middleware(['auth']);

Route::prefix('sentalk')->group(function () {
    Route::get('/', [SenTalkController::class, 'index']);
    Route::post('/upload', [SenTalkController::class, 'upload']);
    Route::get('/{id}/display', [SenTalkController::class, 'display']);
    Route::get('/download/{id}', [SenTalkController::class, 'download']);
    Route::get('/stats', [SenTalkController::class, 'stats']);
});
