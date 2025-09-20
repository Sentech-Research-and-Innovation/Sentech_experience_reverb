<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SenTalkController;



Route::get('/admin/dashboard',  [DashboardController::class, 'index'])->middleware(['auth']);
Route::post('/admin/activities',  [DashboardController::class, 'show'])->middleware(['auth']);

Route::prefix('sentalk')->group(function () {
    Route::get('/', [SenTalkController::class, 'index']);
    Route::get('/mobile/{id}', [SenTalkController::class, 'show']);
    Route::post('/upload', [SenTalkController::class, 'upload']);
    Route::get('/display', [SenTalkController::class, 'display']);
    Route::get('/download/{id}', [SenTalkController::class, 'download']);
    Route::get('/stats', [SenTalkController::class, 'stats']);
    Route::post('/update/{id}', [SenTalkController::class, 'update']);
    Route::delete('/delete/{id}', [SenTalkController::class, 'delete']);
    Route::post('/feedback', [SentalkController::class, 'feedback']);
    Route::post('/like/{id}', [SentalkController::class, 'like']);
    Route::post('/view/{id}', [SenTalkController::class, 'view']);



});
