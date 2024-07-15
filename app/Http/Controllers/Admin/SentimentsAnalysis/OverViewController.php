<?php

namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use App\Http\Controllers\Controller;
use App\Services\Sentiment\OverviewService;
use App\Models\Sentiment as Tweet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DateTime;

class OverViewController extends Controller
{
    protected $sentimentService;
    protected $tweets;
    protected $searchFilter;

    public function __construct(OverviewService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
        $this->tweets = Tweet::all();
        $this->searchFilter = request()->searchFilter;
    }

    public function index()
    {
        return Inertia::render('Admin/Sentiments/Overview/index');
    }

    public function overallSentiments()
    {

        $response = $this->sentimentService->overallSentiments($this->tweets, $this->searchFilter);

        return response()->json($response, 200);
    }

    public function sentimentsTimeline()
    {

        $response = $this->sentimentService->sentimentsTimeline($this->tweets, $this->searchFilter);

        return response()->json($response, 200);
    }

    public function tweetsByLocation()
    {

        $response = $this->sentimentService->tweetsByLocation($this->tweets, $this->searchFilter);

        return response()->json($response, 200);
    }
}
