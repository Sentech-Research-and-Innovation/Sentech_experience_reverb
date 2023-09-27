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

        $dateRange = request()->searchFilter['date']; // Assuming date range is in the format ["start_date", "end_date"]
        $startDate = null;
        $endDate = null;

        if ($dateRange !== null) {
            $startDate = new DateTime($dateRange[0]);
            $endDate = new DateTime($dateRange[1]);
        }

        $keyword = request()->searchFilter['keywords'];

        foreach ($data['hits']['hits'] as $hit) {
            $dateStr = $hit['_source']['date'];
            $tweetDate = new DateTime($dateStr);

            // Check if the tweet's date falls within the specified date range (if not null)
            if (($startDate === null || $tweetDate >= $startDate) && ($endDate === null || $tweetDate <= $endDate)) {
                // if ($tweetDate >= $startDate && $tweetDate <= $endDate) {
                if ($keyword !== null) {
                    // Check if keywords are set and the tweet contains them
                    if (stripos($hit['_source']['text'], $keyword) !== false) {
                        $sentiment = $hit['_source']['sentiment'];

                        if ($sentiment === 'positive') {
                            $positiveTweets++;
                        } elseif ($sentiment === 'negative') {
                            $negativeTweets++;
                        } elseif ($sentiment == 'neutral') {
                            $neutralTweets++;
                        }
                    }
                } else {
                    // If no keyword filter is set, count all tweets
                    $sentiment = $hit['_source']['sentiment'];

                    if ($sentiment === 'positive') {
                        $positiveTweets++;
                    } elseif ($sentiment === 'negative') {
                        $negativeTweets++;
                    } elseif ($sentiment == 'neutral') {
                        $neutralTweets++;
                    }
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
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate)) {
                // Check if keywords are provided and the hit contains them
                if (!empty($keywords) && stripos($hit['_source']['text'], $keywords) === false) {
                    continue; // Skip this iteration if keywords don't match
                }

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

        return response()->json($dateMonthGroups, 200); // Updated response() method
    }



    public function tweetsByLocation()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];

        $placeTweetCounts = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];

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

            // Check if the tweet's date falls within the specified date range (if not null)
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate)) {
                // Keyword filtering
                if ($keyword !== null) {
                    $tweetText = $hit['_source']['text'];

                    if (stripos($tweetText, $keyword) === false) {
                        continue; // Skip this iteration if the keyword is not found in the tweet text
                    }
                }

                // Count tweets per place
                if (!isset($placeTweetCounts[$place])) {
                    $placeTweetCounts[$place] = 1;
                } else {
                    $placeTweetCounts[$place]++;
                }
            }
        }

        return $placeTweetCounts;
    }
}
