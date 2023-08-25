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

        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=2000');

        $data = json_decode($jsonData, true);

        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $total = count($data['hits']['hits']);

        foreach ($data['hits']['hits'] as $hit) {

            $sentiment = $hit['_source']['sentiment'];

            if ($sentiment === 'positive') {
                $positiveTweets++;
            } else if ($sentiment === 'negative') {
                $negativeTweets++;
            } else if ($sentiment == 'neutral') {
                $neutralTweets++;
            }
        }

        $response = ["positiveTweets" => $positiveTweets, "negativeTweets" => $negativeTweets, "neutralTweets" => $neutralTweets, "totalTweets" => $total];

        return request()->json(200, $response);
    }

    public function sentimentsTimeline()
    {

        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=2000');

        $data = json_decode($jsonData, true);


        $hits = $data['hits']['hits'];

        $dateMonthGroups = [];

        foreach ($hits as $hit) {
            $sentiment = $hit['_source']['sentiment'];
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $month = $date->format('Y-m');

            if (!isset($dateMonthGroups[$month])) {
                $dateMonthGroups[$month] = [
                    'year' => $date->format('Y'),
                    'month' => $date->format('F'),
                    'sentiments' => [
                        'positive' => 0,
                        'neutral' => 0,
                        'negative' => 0,
                    ],
                ];
            }
            $dateMonthGroups[$month]['sentiments'][$sentiment]++;
        }

        return request()->json(200, $dateMonthGroups);
    }

    public function tweetsByLocation()
    {

        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');

        $data = json_decode($jsonData, true);

        $hits = $data['hits']['hits'];

        $placeTweetCounts = [];

        foreach ($hits as $hit) {
            // Assuming the location information is under 'place'
            $place = $hit['_source']['place']; // You may need to adjust this based on your data structure

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
