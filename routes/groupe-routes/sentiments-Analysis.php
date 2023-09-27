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
    Route::get('/', [OverViewController::class, 'index']);
    Route::post('/sentimentsTimeline', [OverViewController::class, 'sentimentsTimeline']);
    Route::post('/tweets-by-location', [OverViewController::class, 'tweetsByLocation']);
    Route::post('/overall-sentiments', [OverViewController::class, 'overallSentiments']);
});


Route::group(['prefix' => '/admin/sentiments/timelines'], function () {
    Route::get('/', [TimeLinesController::class, 'index']);
    Route::post('/tweets-by-hour', [TimeLinesController::class, 'tweetsByHour']);
    Route::get('/tweets-and-likes', [TimeLinesController::class, 'tweetsLikes']);
});


Route::group(['prefix' => '/admin/sentiments/trends'], function () {
    Route::get('/', [TrendsController::class, 'index']);
    Route::post('/tweetsContent', [TrendsController::class, 'tweetsContent']);
    Route::get('/wordclouds', [TrendsController::class, 'wordCloudList']);
});


Route::group(['prefix' => '/admin/sentiments/others'], function () {
    Route::get('/', [OtherController::class, 'index']);
    Route::post('/mapCoorddinates', [OtherController::class, 'map']);
});
