<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;

use Inertia\Inertia;
use DateTime;

class TimeLinesController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Sentiments/Timelines/index');
    }

    public function tweetsByHour()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];
        $hourGroups = [];
        $filterDate = request()->searchFilter['date'];
        $keywords = (array)request()->searchFilter['keywords']; // Ensure keywords is an array

        // Initialize an array to count occurrences of each hour
        $hourCounts = array_fill(0, 24, 0);

        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $tweetText = $hit['_source']['text'];
            $date = new DateTime($dateStr);
            $hour = (int)$date->format('H'); // Get the hour component

            // Check if the tweet's date falls within the specified date range (if not null)
            if ($filterDate !== null) {
                $startDate = new DateTime($filterDate[0]);
                $endDate = new DateTime($filterDate[1]);
                if ($date < $startDate || $date > $endDate) {
                    continue; // Skip this tweet if it's outside the date range
                }
            }

            // Keyword filtering logic
            $keywordFound = false;
            foreach ($keywords as $keyword) {
                if (stripos($tweetText, $keyword) !== false) {
                    $keywordFound = true;
                    break; // Exit the loop if at least one keyword is found
                }
            }


            if (!empty($keywords) && !$keywordFound) {
                continue; // Skip this iteration if no keyword is found
            }

            // Count the occurrence of each hour
            $hourCounts[$hour]++;
        }

        // Create hourGroups based on the counts
        for ($hour = 0; $hour < 24; $hour++) {
            $hourGroups[] = [
                'hour' => $hour,
                'sentiments' => [
                    'positive' => 0,
                    'neutral' => 0,
                    'negative' => 0,
                ],
            ];
        }

        // Fill sentiments based on counts
        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $hour = (int)$date->format('H'); // Get the hour component

            if (isset($hourGroups[$hour])) {
                $hourGroups[$hour]['sentiments'][$sentiment]++;
            }
        }

        // Separate hours and data
        $hours = [];
        $dataByHour = [];

        foreach ($hourGroups as $hourGroup) {
            $hours[] = $hourGroup['hour'];
            $dataByHour[] = $hourGroup['sentiments'];
        }

        $response = [
            'hours' => $hours,
            'data' => $dataByHour,
        ];

        return response()->json($response, 200);
    }



    public function tweetsLikes()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=2000');
        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];

        $dateTotals = [];
        $filterDate = request()->searchFilter['date'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        foreach ($hits as $hit) {
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);

            // Check if the tweet's date falls within the specified date range (if not null)
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate)) {
                $formattedDate = $date->format('Y-m-d');

                if (!isset($dateTotals[$formattedDate])) {
                    $dateTotals[$formattedDate] = [
                        'totalTweets' => 0,
                        'totalLikes' => 0,
                    ];
                }

                // Increment total tweets
                $dateTotals[$formattedDate]['totalTweets']++;

                // Increment total likes
                $totalLikes = $hit['_source']['likes'];
                $dateTotals[$formattedDate]['totalLikes'] += $totalLikes;
            }
        }

        return response()->json($dateTotals);
    }
}
