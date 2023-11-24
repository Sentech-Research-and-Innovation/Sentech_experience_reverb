<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Organizations\OrganizationsController;

Route::get('/organizantions', [OrganizationsController::class, 'index'])->middleware(['auth']);
Route::post('/organizantions/create', [OrganizationsController::class, 'create'])->middleware(['auth']);
