<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Organizations\OrganizationsController;

Route::get('/organizantions', [OrganizationsController::class, 'index'])->middleware(['auth']);
Route::get('/organizantions/approved', [OrganizationsController::class, 'approved'])->middleware(['auth']);
Route::get('/organizantions/request', [OrganizationsController::class, 'request'])->middleware(['auth']);
Route::post('/organizantions/create', [OrganizationsController::class, 'create'])->middleware(['auth']);
Route::post('/organizantions/approve/{company_id}', [OrganizationsController::class, 'approveCompany'])->middleware(['auth']);
