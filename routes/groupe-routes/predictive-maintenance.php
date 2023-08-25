<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\PredictiveMaintenance\PredictiveMaintenanceController;


Route::get('/admin/predictive-maintenance/national-sites', [PredictiveMaintenanceController::class, 'nationalSites']);
Route::get('/admin/predictive-maintenance/predictions', [PredictiveMaintenanceController::class, 'predictions']);
Route::get('/admin/predictive-maintenance/device-config', [PredictiveMaintenanceController::class, 'deviceConfig']);
Route::get('/admin/predictive-maintenance/alarm-list', [PredictiveMaintenanceController::class, 'alarmList']);
