<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PredictiveMaintenance\PredictiveMaintenanceController;

Route::group(['prefix' => '/admin/predictive-maintenance'], function () {
    Route::get('/index', [PredictiveMaintenanceController::class, 'index'])->middleware('role_has_permission:predictive_maintenance-read_master_view');
    Route::post('/predictions', [PredictiveMaintenanceController::class, 'getPredictions']);
    Route::get('/predictions/detailed-view', [PredictiveMaintenanceController::class, 'detailedView'])->middleware('role_has_permission:predictive_maintenance-read_detailed_view');
    Route::post('/predictions/alarm-flag', [PredictiveMaintenanceController::class, 'alarmFlag']);
    Route::post('/predictions/filtered', [PredictiveMaintenanceController::class, 'predictionsFiltered']);
});
