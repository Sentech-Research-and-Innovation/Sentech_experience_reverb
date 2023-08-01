<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\System\MenuItem;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\PersmissionsController;
use  App\Http\Controllers\Admin\AsignRolesController;
use  App\Http\Controllers\Admin\UserController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//$x=Hash::make('Password');
//dd($x);
/* Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); */

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'role:Admin'])->name('dashboard');



Route::get('/admin/roles', [RolesController::class, 'index'])->name('roles.index')->middleware('role_has_permission:roles-read');

Route::post('/admin/roles/create', [RolesController::class, 'store'])->name('roles.create')->middleware('role_has_permission:roles-create');

Route::post('/admin/roles/show', [RolesController::class, 'show'])->name('roles.show')->middleware('role_has_permission:roles-read');

Route::post('/admin/roles/update', [RolesController::class, 'update'])->name('roles.update')->middleware('role_has_permission:roles-update');

Route::get('/admin/roles/getRoles', [RolesController::class, 'getRoles'])->name('roles.getRoles')->middleware('role_has_permission:roles-read');

Route::get('/admin/user/role/{userId}', [AsignRolesController::class, 'show'])->name('roles.show')->middleware('role_has_permission:roles-read');


Route::post('/admin/user/role/update/{userId}', [AsignRolesController::class, 'update'])->name('roles.user.update');

Route::get('/admin/permissions', [PersmissionsController::class, 'index'])->name('permissions.index');

Route::get('/admin/getUsers', [AsignRolesController::class, 'index'])->name('roles.getUsers');

Route::post('/admin/user/create', [UserController::class, 'create'])->name('roles.user.create');






// Route::get('/das', function () {
//     return Inertia::render('admin.index');
// })->middleware(['auth',  'verified', 'role:Admin'])->name('admin.index');


require __DIR__ . '/auth.php';

MenuItem::inertia();
