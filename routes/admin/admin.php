<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Admin\GetNotifications;




Route::get('/admin/notifications', [GetNotifications::class, 'index'])->middleware('auth');

// Route::get('/admin/dashboard/stats', [DashboardController::class, 'getDashboardStats']);
Route::get('/admin/dashboard/stats', function(){

    return response()->json([
            'pending_companies' => Company::count(),
            'company_requests' => Company::count(), // or however you define this
            'system_users' => User::count(),
            // // // 'customer_feedback' => Feedback::count(), // adjust model if it's named differently
            'customer_feedback' => User::count(),
            // 'test' => 'Dashboard stats working!',
        ]);
    
    });
