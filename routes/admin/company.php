<?php

use Illuminate\Support\Facades\Route;
use  App\Http\Controllers\Organizations\OrganizationsController;


Route::get('/organizantions/approved', [OrganizationsController::class, 'approved'])->middleware('role_has_permission:companies-read_approved');
Route::get('/organizantions/request', [OrganizationsController::class, 'request'])->middleware('role_has_permission:companies-read_requests');
Route::get('/organizantions/pending', [OrganizationsController::class, 'pending'])->middleware('role_has_permission:companies-read_pending');
Route::post('/organizantions/create', [OrganizationsController::class, 'create'])->middleware('role_has_permission:companies-create_company');
Route::post('/organizantions/approve/{company_id}', [OrganizationsController::class, 'approveCompany'])->middleware('role_has_permission:companies-approve_requests');
