<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\User;
use App\Models\UserModels\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DateTime;

class OverViewController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Sentiments/Overview/index');
    }

    public function overallSentiments()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $total = count($data['hits']['hits']);

        $dateRange = request()->searchFilter['date'];
        $startDate = new DateTime($dateRange[0]);
        $endDate = new DateTime($dateRange[1]);

        $keyword = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        foreach ($data['hits']['hits'] as $hit) {
            $dateStr = $hit['_source']['date'];
            $tweetDate = new DateTime($dateStr);
            $sentiment = $hit['_source']['sentiment'];

            // Check if the tweet's date falls within the specified date range
            // and if the sentiment type is in the specified sentimentTypes
            if (($tweetDate >= $startDate && $tweetDate <= $endDate) &&
                (empty($keyword) || stripos($hit['_source']['text'], $keyword) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                if ($sentiment === 'positive') {
                    $positiveTweets++;
                } elseif ($sentiment === 'negative') {
                    $negativeTweets++;
                } elseif ($sentiment === 'neutral') {
                    $neutralTweets++;
                }
            }
        }

        $response = [
            "positiveTweets" => $positiveTweets,
            "negativeTweets" => $negativeTweets,
            "neutralTweets" => $neutralTweets,
            "totalTweets" => $total,
        ];

        return response()->json($response, 200);
    }



    public function sentimentsTimeline()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);
        $hits = $data['hits']['hits'];

        $dateMonthGroups = [];
        $filterDate = request()->searchFilter['date'];
        $keywords = request()->searchFilter['keywords']; // Get the keywords parameter
        $sentimentTypes = request()->searchFilter['sentimentTypes']; // Get the sentimentTypes parameter

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);

            // Check if the tweet's date falls within the specified date range (if not null)
            // and if the sentiment type is in the specified sentimentTypes
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keywords) || stripos($hit['_source']['text'], $keywords) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                $formattedDate = $date->format('Y-m');

                if (!isset($dateMonthGroups[$formattedDate])) {
                    $dateMonthGroups[$formattedDate] = [
                        'year' => $date->format('Y'),
                        'month' => $date->format('F'),
                        'sentiments' => [
                            'positive' => 0,
                            'neutral' => 0,
                            'negative' => 0,
                        ],
                    ];
                }

                ksort($dateMonthGroups);

                $dateMonthGroups[$formattedDate]['sentiments'][$sentiment]++;
            }
        }

        return response()->json($dateMonthGroups, 200);
    }



    public function tweetsByLocation()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];

        $placeTweetCounts = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        foreach ($hits as $hit) {
            $place = $hit['_source']['place'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $sentiment = $hit['_source']['sentiment'];

            // Check if the tweet's date falls within the specified date range (if not null)
            // and if the sentiment type is in the specified sentimentTypes
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keyword) || stripos($hit['_source']['text'], $keyword) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                // Count tweets per place
                if (!isset($placeTweetCounts[$place])) {
                    $placeTweetCounts[$place] = 1;
                } else {
                    $placeTweetCounts[$place]++;
                }
            }
        }

        // Sort the places by tweet counts in descending order
        arsort($placeTweetCounts);

        // Take only the top 10 places, and group the rest under "Other"
        $topPlaces = array_slice($placeTweetCounts, 0, 10);
        $otherCount = array_sum(array_slice($placeTweetCounts, 10));

        // Add "Other" category to the result
        $topPlaces['Other'] = $otherCount;

        return $topPlaces;
    }
}
