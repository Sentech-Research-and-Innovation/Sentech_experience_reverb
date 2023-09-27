<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\PredictiveMaintenance\PredictiveMaintenanceController;



Route::group(['prefix' => '/admin/predictive-maintenance'], function () {
    Route::get('/national-sites', [PredictiveMaintenanceController::class, 'nationalSites']);
    Route::get('/predictions', [PredictiveMaintenanceController::class, 'predictions']);
    Route::get('/device-config', [PredictiveMaintenanceController::class, 'deviceConfig']);
    Route::get('/alarm-list', [PredictiveMaintenanceController::class, 'alarmList']);
    Route::get('/index', [PredictiveMaintenanceController::class, 'index']);
    Route::get('/main', [PredictiveMaintenanceController::class, 'predictive_main']);
});
