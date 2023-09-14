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
use  App\Http\Controllers\Organizations\OrganizationsController;
use App\Http\Controllers\Web\WeatherController;


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

Route::get('/', function () {
    return Inertia::render('Web/Index');
});


Route::get('/dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth'])->name('dashboard');



Route::get('/admin/roles', [RolesController::class, 'index'])->name('roles.index')->middleware('role_has_permission:roles-read');

Route::post('/admin/roles/create', [RolesController::class, 'store'])->name('roles.create')->middleware('role_has_permission:roles-create');

Route::post('/admin/roles/show', [RolesController::class, 'show'])->name('roles.show')->middleware('role_has_permission:roles-read');

Route::post('/admin/roles/update', [RolesController::class, 'update'])->name('roles.update')->middleware('role_has_permission:roles-update');
Route::post('/admin/roles/delete', [RolesController::class, 'delete'])->name('roles.delete')->middleware('role_has_permission:roles-delete');

Route::get('/admin/roles/getRoles', [RolesController::class, 'getRoles'])->name('roles.getRoles')->middleware('role_has_permission:roles-read');

Route::get('/admin/user/role/{userId}', [AsignRolesController::class, 'show'])->name('roles.show.user')->middleware('role_has_permission:roles-read');


Route::post('/admin/user/role/update/{userId}', [AsignRolesController::class, 'update'])->name('roles.user.update');

Route::get('/admin/permissions', [PersmissionsController::class, 'index'])->name('permissions.index');

Route::get('/admin/getUsers', [AsignRolesController::class, 'index'])->name('roles.getUsers')->middleware('auth');

Route::post('/admin/user/create', [UserController::class, 'create'])->name('roles.user.create');

Route::get('/organizantions', [OrganizationsController::class, 'index'])->middleware(['auth']);
Route::post('/organizantions/create', [OrganizationsController::class, 'create'])->middleware(['auth']);

Route::get('/web/weather', [WeatherController::class, 'create']);


//MenuItem::inertia();


require __DIR__ . '/auth.php';

require __DIR__ . '/groupe-routes/sentiments-Analysis.php';

require __DIR__ . '/groupe-routes/predictive-maintenance.php';
