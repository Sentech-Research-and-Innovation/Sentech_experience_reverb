<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\PredictiveMaintenance\PredictiveMaintenanceController;



Route::group(['prefix' => '/admin/predictive-maintenance'], function () {
    Route::get('/index', [PredictiveMaintenanceController::class, 'index']);
    Route::post('/predictions', [PredictiveMaintenanceController::class, 'getPredictions']);

    Route::get('/predictions/detailed-view', [PredictiveMaintenanceController::class, 'detailedView']);

    Route::post('/predictions/detailed-view-data', [PredictiveMaintenanceController::class, 'detailedViewData']);

    Route::post('/predictions/alarm-flag', [PredictiveMaintenanceController::class, 'alarmFlag']);
});
