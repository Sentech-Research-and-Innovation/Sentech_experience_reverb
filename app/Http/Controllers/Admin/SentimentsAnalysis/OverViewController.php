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
        $tweets = Tweet::all(); // Fetch all tweets from your database

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
            $tweetDate = new DateTime($tweet->date); // Assuming 'date' is the attribute name in your Tweet model
            $sentiment = $tweet->sentiment; // Assuming 'sentiment' is the attribute name in your Tweet model

            // Check if the tweet's date falls within the specified date range
            // and if the sentiment type is in the specified sentimentTypes
            if (($tweetDate >= $startDate && $tweetDate <= $endDate) &&
                (empty($keyword) || stripos($tweet->text, $keyword) !== false) &&
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


        // Fetch tweets using Eloquent instead of HTTP call
        $tweets = Tweet::all(); // You may need to apply additional filters here based on your actual model and requirements

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

        foreach ($tweets as $tweet) {
            $sentiment = $tweet->sentiment;
            $date = new DateTime($tweet->date);

            // Check if the tweet's date falls within the specified date range (if not null)
            // and if the sentiment type is in the specified sentimentTypes
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keywords) || stripos($tweet->text, $keywords) !== false) &&
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


        // Fetch tweets using Eloquent instead of HTTP call
        $tweets = Tweet::all(); // You may need to apply additional filters here based on your actual model and requirements

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

            // Check if the tweet's date falls within the specified date range (if not null)
            // and if the sentiment type is in the specified sentimentTypes
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keyword) || stripos($tweet->text, $keyword) !== false) &&
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
