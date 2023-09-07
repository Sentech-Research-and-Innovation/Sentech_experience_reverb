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
        $keywords = request()->searchFilter['keywords'];


        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $hour = $date->format('H'); // Get the hour component

            if ($filterDate !== null) {
                // Check if the date matches the filter date
                $filterDateTime = new DateTime($filterDate);
                if ($date->format('Y-m-d') != $filterDateTime->format('Y-m-d')) {
                    continue; // Skip this tweet if the dates don't match
                }
            }

            if (!empty($keywords) && stripos($hit['_source']['text'], $keywords) === false) {
                continue; // Skip this iteration if keywords don't match
            }

            if (!isset($hourGroups[$hour])) {
                $hourGroups[$hour] = [
                    'hour' => intval($hour),
                    'sentiments' => [
                        'positive' => 0,
                        'neutral' => 0,
                        'negative' => 0,
                    ],
                ];
            }
            $hourGroups[$hour]['sentiments'][$sentiment]++;
        }

        ksort($hourGroups);
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

        foreach ($hits as $hit) {
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
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

        return response()->json($dateTotals);
    }
}
