<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\SentimentsAnalysis\OverViewController;
use App\Http\Controllers\Admin\SentimentsAnalysis\TimeLinesController;
use App\Http\Controllers\Admin\SentimentsAnalysis\TrendsController;
use App\Http\Controllers\Admin\SentimentsAnalysis\OtherController;

Route::get('/admin/sentiments/all', function () {
    return Inertia::render('Admin/Sentiments/All');
});

Route::group(['prefix' => '/admin/sentiments/overview'], function () {
    Route::get('/', [OverViewController::class, 'index'])->middleware('role_has_permission:sentiment_Analysis-read_overview');
    Route::post('/sentimentsTimeline', [OverViewController::class, 'sentimentsTimeline'])->middleware('role_has_permission:sentiment_Analysis-read_overview');
    Route::post('/tweets-by-location', [OverViewController::class, 'tweetsByLocation'])->middleware('role_has_permission:sentiment_Analysis-read_overview');
    Route::post('/overall-sentiments', [OverViewController::class, 'overallSentiments'])->middleware('role_has_permission:sentiment_Analysis-read_overview');
});

Route::group(['prefix' => '/admin/sentiments/timelines'], function () {
    Route::get('/', [TimeLinesController::class, 'index'])->middleware('role_has_permission:sentiment_Analysis-read_timelines');
    Route::post('/tweets-by-hour', [TimeLinesController::class, 'tweetsByHour'])->middleware('role_has_permission:sentiment_Analysis-read_timelines');
});

Route::group(['prefix' => '/admin/sentiments/trends'], function () {
    Route::get('/', [TrendsController::class, 'index'])->middleware('role_has_permission:sentiment_Analysis-read_trends');
    Route::post('/tweetsContent', [TrendsController::class, 'tweetsContent'])->middleware('role_has_permission:sentiment_Analysis-read_trends');
    Route::get('/wordclouds', [TrendsController::class, 'wordCloudList'])->middleware('role_has_permission:sentiment_Analysis-read_trends');
});

Route::group(['prefix' => '/admin/sentiments/others'], function () {
    Route::get('/', [OtherController::class, 'index'])->middleware('role_has_permission:sentiment_Analysis-read_others');
    Route::post('/mapCoorddinates', [OtherController::class, 'map'])->middleware('role_has_permission:sentiment_Analysis-read_others');
});
