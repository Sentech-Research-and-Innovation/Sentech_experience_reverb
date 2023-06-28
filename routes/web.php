<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\System\MenuItem;
use App\Http\Controllers\LoanWizard\PersonalInformationController;
use App\Http\Controllers\LoanWizard\EmploymentInformationController;
use App\Http\Controllers\LoanWizard\LoanApplicationController;
use App\Http\Controllers\LoanWizard\AdministrationOrderController;

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

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified',
//])->group(function () {
//    Route::get('/dashboard', function () {
//        return Inertia::render('Dashboard');
//    })->name('dashboard');
//});



MenuItem::inertia();

Route::prefix('/loan-wizard')->group(function () {

    Route::prefix('/training-check')->group(function () {
        Route::post('/add', [LoanApplicationController::class, 'store'])->name('loan.store');
    });

    Route::prefix('/personal-information')->group(function () {
        Route::post('/add', [PersonalInformationController::class, 'store'])->name('personal.store');
    });

    Route::prefix('/employment-information')->group(function () {
        Route::post('/add', [EmploymentInformationController::class, 'store'])->name('employment.store');
    });

    Route::prefix('/administration-order')->group(function () {
        Route::post('/add', [AdministrationOrderController::class, 'store'])->name('admin-order.store');
    });
});
// /loan-wizard/personal-information/add