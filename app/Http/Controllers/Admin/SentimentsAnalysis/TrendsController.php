<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\WordCloud;
use Inertia\Inertia;
use DateTime;

use App\Services\Sentiment\TrendsService;

use App\Models\Sentiment as Tweet;

class TrendsController extends Controller
{
    protected $sentimentService;
    protected $tweets;
    protected $searchFilter;

    public function __construct(TrendsService $sentimentService)
    {
        $this->sentimentService = $sentimentService;
        $this->tweets = Tweet::all();
        $this->searchFilter = request()->searchFilter;
    }



    public function index()
    {
        return Inertia::render('Admin/Sentiments/Trends/index');
    }

    public function tweetsContent()
    {

        $tweets = Tweet::orderBy('date', 'desc')->limit(100)->get();


        $response = $this->sentimentService->tweetsContent($this->searchFilter);

        return response()->json($response, 200);
    }

    public function wordCloudList()
    {

        $words = WordCloud::get();

        return response()->json($words, 200);
    }
}
