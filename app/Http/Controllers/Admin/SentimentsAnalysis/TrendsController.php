<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\WordCloud;
use Inertia\Inertia;
use DateTime;

use App\Models\Sentiment as Tweet;

class TrendsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sentiments/Trends/index');
    }

    public function tweetsContent()
    {

        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $tweetsContent = [];

        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        $query = Tweet::query();

        if (!empty($keyword)) {
            $query->where('text', 'like', '%' . $keyword . '%');
        }

        if (!empty($sentimentTypes)) {
            $query->whereIn('sentiment', $sentimentTypes);
        }

        if ($startDate !== null) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate !== null) {
            $query->where('date', '<=', $endDate);
        }

        $tweets = $query->get();

        foreach ($tweets as $tweet) {
            // Update sentiment counts based on tweet's sentiment
            if ($tweet->sentiment === 'POSITIVE') {
                $positiveTweets++;
            } elseif ($tweet->sentiment === 'NEGATIVE') {
                $negativeTweets++;
            } elseif ($tweet->sentiment == 'NEUTRAL') {
                $neutralTweets++;
            }

            $tweetsContent[] = [
                "text" => $tweet->text,
                "sentiment" => $tweet->sentiment,
                "date" => $tweet->date,
            ];
        }

        $response = [
            "positiveTweets" => $positiveTweets,
            "negativeTweets" => $negativeTweets,
            "neutralTweets" => $neutralTweets,
            "tweetsContent" => $tweetsContent
        ];

        return response()->json($response, 200);
    }

    public function wordCloudList()
    {

        $words = WordCloud::get();

        return response()->json($words, 200);
    }
}
