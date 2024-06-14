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



        $tweets = Tweet::all();

        $hourGroups = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = trim(request()->searchFilter['keywords']);
        $sentimentTypes = request()->searchFilter['sentimentTypes'];


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
            $hour = (int)$date->format('H');


            if ($filterDate !== null) {
                $startDate = new DateTime($filterDate[0]);
                $endDate = new DateTime($filterDate[1]);
                if ($date < $startDate || $date > $endDate) {
                    continue;
                }
            }

            $tweetText = $tweet->text;
            $user = $tweet->user;


            if (!empty($keyword) && (stripos($tweetText, $keyword) === false && stripos($user, $keyword) === false)) {
                continue;
            }


            if (!in_array($sentiment, $sentimentTypes)) {
                continue;
            }


            $hourSentimentCounts[$hour][$sentiment]++;
        }

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
