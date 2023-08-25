<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\SentimentsAnalysis\OverViewController;
use App\Http\Controllers\Admin\SentimentsAnalysis\TimeLinesController;


Route::get('/admin/sentiments/overview', [OverViewController::class, 'index']);
Route::get('/admin/sentiments/overview/sentimentsTimeline', [OverViewController::class, 'sentimentsTimeline']);
Route::get('/admin/sentiments/overview/tweets-by-location', [OverViewController::class, 'tweetsByLocation']);
Route::get('/admin/sentiments/overview/overall-sentiments', [OverViewController::class, 'overallSentiments']);


Route::get('/admin/sentiments/timelines', [TimeLinesController::class, 'index']);
Route::get('/admin/sentiments/timelines/tweets-by-hour', [TimeLinesController::class, 'tweetsByHour']);
Route::get('/admin/sentiments/timelines/tweets-and-likes', [TimeLinesController::class, 'tweetsLikes']);
