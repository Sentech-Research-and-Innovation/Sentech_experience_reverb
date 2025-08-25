<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PersmissionsController;
use  App\Http\Controllers\Admin\AsignRolesController;
use  App\Http\Controllers\Admin\UserController;

Route::group(['prefix' => '/admin/roles'], function () {


    Route::get('/', [RolesController::class, 'index'])->middleware('role_has_permission:roles-read');
    Route::post('/create', [RolesController::class, 'store'])->middleware('role_has_permission:roles-create');
    Route::post('/show', [RolesController::class, 'show'])->middleware('role_has_permission:roles-read');
    Route::post('/update', [RolesController::class, 'update'])->middleware('role_has_permission:roles-update');
    Route::post('/delete', [RolesController::class, 'delete'])->middleware('role_has_permission:roles-delete');
    Route::get('/getRoles', [RolesController::class, 'getRoles'])->middleware('role_has_permission:roles-read');
});

Route::group(['prefix' => '/admin/user'], function () {
    // Route::get('/role/{userId}', [AsignRolesController::class, 'show']);
    // Route::post('/role/update/{userId}', [AsignRolesController::class, 'update']);

    Route::get('/role/{userId}', [AsignRolesController::class, 'show']);
    Route::post('/role/update/{userId}', [AsignRolesController::class, 'update'])->middleware('role_has_permission:users-update');

    Route::post('/create', [UserController::class, 'create'])->middleware('role_has_permission:users-create');

    Route::post('/delete/{user_id}', [UserController::class, 'delete'])->middleware('role_has_permission:users-create');

    Route::post('resendEmail/${userId}', [UserController::class, 'resendEmail'])->middleware('role_has_permission:users-create');

});

Route::get('/admin/permissions', [PersmissionsController::class, 'index']);

Route::get('/admin/getActiveUsers', [AsignRolesController::class, 'active_users'])->middleware('role_has_permission:users-read');
Route::get('/admin/getInactiveUsers', [AsignRolesController::class, 'Inactive_users'])->middleware('role_has_permission:users-read');
