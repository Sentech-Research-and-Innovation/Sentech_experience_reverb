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
        $keyword = trim(request()->searchFilter['keywords']); // Get the keyword as a string
        $sentimentTypes = request()->searchFilter['sentimentTypes']; // Get the sentimentTypes parameter

        // Initialize an array to count occurrences of each hour and sentiment type
        $hourSentimentCounts = [];
        for ($hour = 0; $hour < 24; $hour++) {
            $hourSentimentCounts[$hour] = [
                'positive' => 0,
                'neutral' => 0,
                'negative' => 0,
            ];
        }

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
