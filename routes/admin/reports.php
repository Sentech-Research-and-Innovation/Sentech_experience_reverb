<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Admin\PrintReportsController;

Route::get('/reports', [PrintReportsController::class, 'index'])->middleware(['print']);
