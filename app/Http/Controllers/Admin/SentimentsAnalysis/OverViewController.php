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

use App\Models\Sentiment as Tweet;


class OverViewController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Sentiments/Overview/index');
    }

    public function overallSentiments()
    {
        $tweets = Tweet::all();

        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $total = count($tweets);

        $dateRange = request()->searchFilter['date'];
        $startDate = new DateTime($dateRange[0]);
        $endDate = new DateTime($dateRange[1]);

        $keyword = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        foreach ($tweets as $tweet) {
            $tweetDate = new DateTime($tweet->date);
            $sentiment = $tweet->sentiment;


            if (($tweetDate >= $startDate && $tweetDate <= $endDate) &&
                (empty($keyword) || stripos($tweet->text, $keyword) !== false  ||
                    stripos($tweet->user, $keyword) !== false)  &&
                (in_array($sentiment, $sentimentTypes))
            ) {
                if ($sentiment === 'POSITIVE') {
                    $positiveTweets++;
                } elseif ($sentiment === 'NEGATIVE') {
                    $negativeTweets++;
                } elseif ($sentiment === 'NEUTRAL') {
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



        $tweets = Tweet::all();

        $dateMonthGroups = [];
        $filterDate = request()->searchFilter['date'];
        $keywords = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        foreach ($tweets as $tweet) {
            $sentiment = $tweet->sentiment;
            $date = new DateTime($tweet->date);

            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keywords) || stripos($tweet->text, $keywords) !== false || stripos($tweet->user, $keywords) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {
                $formattedDate = $date->format('Y-m');

                if (!isset($dateMonthGroups[$formattedDate])) {
                    $dateMonthGroups[$formattedDate] = [
                        'year' => $date->format('Y'),
                        'month' => $date->format('F'),
                        'sentiments' => [
                            'POSITIVE' => 0,
                            'NEUTRAL' => 0,
                            'NEGATIVE' => 0,
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



        $tweets = Tweet::all();

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

        foreach ($tweets as $tweet) {
            $place = $tweet->place;
            $date = new DateTime($tweet->date);
            $sentiment = $tweet->sentiment;

            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keyword) || stripos($tweet->text, $keyword) !== false || stripos($tweet->user, $keyword) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                if (!isset($placeTweetCounts[$place])) {
                    $placeTweetCounts[$place] = 1;
                } else {
                    $placeTweetCounts[$place]++;
                }
            }
        }

        arsort($placeTweetCounts);

        $topPlaces = array_slice($placeTweetCounts, 0, 10);
        $otherCount = array_sum(array_slice($placeTweetCounts, 10));

        $topPlaces['Other'] = $otherCount;

        return $topPlaces;
    }
}
