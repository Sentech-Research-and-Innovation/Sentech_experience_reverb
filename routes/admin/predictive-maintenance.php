<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PredictiveMaintenance\PredictiveMaintenanceController;

Route::group(['prefix' => '/admin/predictive-maintenance'], function () {
    Route::get('/index', [PredictiveMaintenanceController::class, 'index'])->middleware(['auth'])->name('predictive-maintenance.index');
    Route::post('/predictions', [PredictiveMaintenanceController::class, 'getPredictions'])->middleware('auth');
    Route::get('/predictions/detailed-view', [PredictiveMaintenanceController::class, 'detailedView'])->middleware('auth');
    Route::post('/predictions/detailed-view-data', [PredictiveMaintenanceController::class, 'detailedViewData'])->middleware('auth');
    Route::post('/predictions/alarm-flag', [PredictiveMaintenanceController::class, 'alarmFlag'])->middleware('auth');
});
