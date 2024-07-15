<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Admin\PrintReportsController;
use  App\Http\Controllers\Admin\Reports\PredictiveMaintenanceReportsController;
use  App\Http\Controllers\Admin\Reports\SentimentsReport;


Route::get('/reports', [PrintReportsController::class, 'index']);
Route::get('/test', [PrintReportsController::class, 'test']);
//Route::get('/reports', [PrintReportsController::class, 'index'])->middleware(['print']);


// Route::get('/admin/reports/predictive-maintenance/print', [PredictiveMaintenanceReportsController::class, 'page']);

Route::post('/admin/reports/predictive-maintenance', [PredictiveMaintenanceReportsController::class, 'index']);
Route::post('/admin/reports/sentiments', [SentimentsReport::class, 'index']);

Route::get('/admin/reports/predictive-maintenance/api', [PredictiveMaintenanceReportsController::class, 'api']);
