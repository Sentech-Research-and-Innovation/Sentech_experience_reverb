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

        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];

        foreach ($data['hits']['hits'] as $hit) {
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $month = $date->format('Y-m-d');

            if ($filterDate !== null) {
                $filterDateObj = new DateTime($filterDate);
                $filterMonth = $filterDateObj->format('Y-m-d');

                if ($month !== $filterMonth) {
                    continue; // Skip this iteration if the months don't match
                }
            }

            if ($keyword !== null) {
                // Check if keywords are set and the tweet contains them
                if (stripos($hit['_source']['text'], $keyword) !== false) {
                    $sentiment = $hit['_source']['sentiment'];

                    if ($sentiment === 'positive') {
                        $positiveTweets++;
                    } else if ($sentiment === 'negative') {
                        $negativeTweets++;
                    } else if ($sentiment == 'neutral') {
                        $neutralTweets++;
                    }
                }
            } else {
                // If no keyword filter is set, count all tweets
                $sentiment = $hit['_source']['sentiment'];

                if ($sentiment === 'positive') {
                    $positiveTweets++;
                } else if ($sentiment === 'negative') {
                    $negativeTweets++;
                } else if ($sentiment == 'neutral') {
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
        $keyword = request()->searchFilter['keywords'];

        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $formattedDate = $date->format('Y-m');

            if ($filterDate !== null) {
                $formattedFilterDate = new DateTime($filterDate);
                $filterMonth = $formattedFilterDate->format('Y-m');

                if ($formattedDate !== $filterMonth) {
                    continue; // Skip this iteration if the months don't match
                }
            }

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


            $dateMonthGroups[$formattedDate]['sentiments'][$sentiment]++;
        }

        return request()->json(200, $dateMonthGroups);
    }

    public function tweetsByLocation()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];

        $placeTweetCounts = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];

        foreach ($hits as $hit) {
            $place = $hit['_source']['place'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $formattedDate = $date->format('Y-m-d');

            if ($filterDate !== null) {
                $formattedFilterDate = new DateTime($filterDate);
                $filterMonth = $formattedFilterDate->format('Y-m-d');

                if ($formattedDate !== $filterMonth) {
                    continue; // Skip this iteration if the months don't match
                }
            }

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

        return $placeTweetCounts;
    }
}
