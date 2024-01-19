<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Admin\PrintReportsController;
use  App\Http\Controllers\Admin\Reports\PredictiveMaintenanceReportsController;


Route::get('/reports', [PrintReportsController::class, 'index']);
Route::get('/test', [PrintReportsController::class, 'test']);
//Route::get('/reports', [PrintReportsController::class, 'index'])->middleware(['print']);


Route::post('/admin/reports/predictive-maintenance', [PredictiveMaintenanceReportsController::class, 'index'])->middleware('auth');
