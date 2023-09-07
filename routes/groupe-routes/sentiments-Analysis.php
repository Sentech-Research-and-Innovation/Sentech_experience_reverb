<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SentimentsAnalysis\OverViewController;
use App\Http\Controllers\Admin\SentimentsAnalysis\TimeLinesController;
use App\Http\Controllers\Admin\SentimentsAnalysis\TrendsController;

use App\Http\Controllers\Admin\SentimentsAnalysis\OtherController;




Route::get('/admin/sentiments/overview', [OverViewController::class, 'index']);
Route::post('/admin/sentiments/overview/sentimentsTimeline', [OverViewController::class, 'sentimentsTimeline']);
Route::post('/admin/sentiments/overview/tweets-by-location', [OverViewController::class, 'tweetsByLocation']);
Route::post('/admin/sentiments/overview/overall-sentiments', [OverViewController::class, 'overallSentiments']);


Route::get('/admin/sentiments/timelines', [TimeLinesController::class, 'index']);
Route::post('/admin/sentiments/timelines/tweets-by-hour', [TimeLinesController::class, 'tweetsByHour']);
Route::get('/admin/sentiments/timelines/tweets-and-likes', [TimeLinesController::class, 'tweetsLikes']);

Route::get('/admin/sentiments/trends', [TrendsController::class, 'index']);
Route::post('/admin/sentiments/trends/tweetsContent', [TrendsController::class, 'tweetsContent']);
Route::get('/admin/sentiments/trends/wordclouds', [TrendsController::class, 'wordCloudList']);


Route::get('/admin/sentiments/others', [OtherController::class, 'index']);
Route::post('/admin/sentiments/mapCoorddinates', [OtherController::class, 'map']);
