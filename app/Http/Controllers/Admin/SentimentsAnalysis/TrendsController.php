<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\WordCloud;
use Inertia\Inertia;
use DateTime;

class TrendsController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sentiments/Trends/index');
    }

    public function tweetsContent()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

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

        foreach ($data['hits']['hits'] as $hit) {

            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $sentiment = $hit['_source']['sentiment'];

            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keyword) || stripos($hit['_source']['text'], $keyword) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                // Update sentiment counts based on tweet's sentiment
                if ($sentiment === 'positive') {
                    $positiveTweets++;
                } elseif ($sentiment === 'negative') {
                    $negativeTweets++;
                } elseif ($sentiment == 'neutral') {
                    $neutralTweets++;
                }

                $tweetsContent[] = [
                    "tweet" => $hit['_source']["text"],
                    "sentiment" => $hit['_source']["sentiment"],
                    "date" => $hit['_source']["date"],
                ];
            }
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
