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
    Route::get('/', [OverViewController::class, 'index'])->middleware('auth')->name('overview');
    Route::post('/sentimentsTimeline', [OverViewController::class, 'sentimentsTimeline'])->middleware(['auth']);
    Route::post('/tweets-by-location', [OverViewController::class, 'tweetsByLocation'])->middleware('auth');
    Route::post('/overall-sentiments', [OverViewController::class, 'overallSentiments'])->middleware('auth');
});

Route::group(['prefix' => '/admin/sentiments/timelines'], function () {
    Route::get('/', [TimeLinesController::class, 'index'])->middleware('auth');
    Route::post('/tweets-by-hour', [TimeLinesController::class, 'tweetsByHour'])->middleware('auth');
    Route::get('/tweets-and-likes', [TimeLinesController::class, 'tweetsLikes'])->middleware('auth');
});

Route::group(['prefix' => '/admin/sentiments/trends'], function () {
    Route::get('/', [TrendsController::class, 'index'])->middleware('auth');
    Route::post('/tweetsContent', [TrendsController::class, 'tweetsContent'])->middleware('auth');
    Route::get('/wordclouds', [TrendsController::class, 'wordCloudList'])->middleware('auth');
});

Route::group(['prefix' => '/admin/sentiments/others'], function () {
    Route::get('/', [OtherController::class, 'index'])->middleware('auth');
    Route::post('/mapCoorddinates', [OtherController::class, 'map'])->middleware('auth');
});
