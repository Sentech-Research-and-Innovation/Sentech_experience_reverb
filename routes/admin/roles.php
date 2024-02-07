<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PersmissionsController;
use  App\Http\Controllers\Admin\AsignRolesController;
use  App\Http\Controllers\Admin\UserController;


Route::group(['prefix' => '/admin/roles'], function () {
    Route::get('/', [RolesController::class, 'index'])->name('roles.index')->middleware('role_has_permission:roles-read');
    Route::post('/create', [RolesController::class, 'store'])->name('roles.create')->middleware('role_has_permission:roles-create');
    Route::post('/show', [RolesController::class, 'show'])->name('roles.show')->middleware('role_has_permission:roles-read');
    Route::post('/update', [RolesController::class, 'update'])->name('roles.update')->middleware('role_has_permission:roles-update');
    Route::post('/delete', [RolesController::class, 'delete'])->name('roles.delete')->middleware('role_has_permission:roles-delete');
    Route::get('/getRoles', [RolesController::class, 'getRoles'])->name('roles.getRoles')->middleware('role_has_permission:roles-read');
});

Route::group(['prefix' => '/admin/user'], function () {
    Route::get('/role/{userId}', [AsignRolesController::class, 'show'])->name('roles.show.user')->middleware('role_has_permission:roles-read');
    Route::post('/role/update/{userId}', [AsignRolesController::class, 'update'])->middleware('role_has_permission:roles-update');

    Route::post('/create', [UserController::class, 'create'])->name('roles.user.create')->middleware('auth');
});

Route::get('/admin/permissions', [PersmissionsController::class, 'index'])->name('permissions.index')->middleware('auth');

Route::get('/admin/getUsers', [AsignRolesController::class, 'index'])->name('roles.getUsers')->middleware('auth');
