<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use DateTime;
use App\Models\Sentiment as Tweet;


class TimeLinesController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Sentiments/Timelines/index');
    }

    public function tweetsByHour()
    {


        // Fetch tweets using Eloquent instead of HTTP call
        $tweets = Tweet::all(); // You may need to apply additional filters here based on your actual model and requirements

        $hourGroups = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = trim(request()->searchFilter['keywords']); // Get the keyword as a string
        $sentimentTypes = request()->searchFilter['sentimentTypes']; // Get the sentimentTypes parameter

        // Initialize an array to count occurrences of each hour and sentiment type
        $hourSentimentCounts = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourSentimentCounts[$hour] = [
                'POSITIVE' => 0,
                'NEUTRAL' => 0,
                'NEGATIVE' => 0,
            ];
        }

        foreach ($tweets as $tweet) {
            $sentiment = $tweet->sentiment;
            $date = new DateTime($tweet->date);
            $hour = (int)$date->format('H'); // Get the hour component

            // Check if the tweet's date falls within the specified date range (if not null)
            if ($filterDate !== null) {
                $startDate = new DateTime($filterDate[0]);
                $endDate = new DateTime($filterDate[1]);
                if ($date < $startDate || $date > $endDate) {
                    continue; // Skip this tweet if it's outside the date range
                }
            }

            $tweetText = $tweet->text;

            // Keyword filtering logic
            if (!empty($keyword) && stripos($tweetText, $keyword) === false) {
                continue; // Skip this tweet if it does not contain the specified keyword
            }

            if (!in_array($sentiment, $sentimentTypes)) {
                continue; // Skip this tweet if its sentiment type is not in the specified types
            }

            // Count the occurrence of each sentiment type for each hour
            $hourSentimentCounts[$hour][$sentiment]++;
        }

        // Prepare the response data
        $hours = [];
        $dataByHour = [];

        foreach ($hourSentimentCounts as $hour => $sentiments) {
            $hours[] = $hour;
            $dataByHour[] = $sentiments;
        }

        $response = [
            'hours' => $hours,
            'data' => $dataByHour,
        ];

        return response()->json($response, 200);
    }
}
