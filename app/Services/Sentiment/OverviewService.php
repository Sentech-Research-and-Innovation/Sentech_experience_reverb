<?php

namespace App\Services\Sentiment;

use DateTime;

class OverviewService
{
    public function overallSentiments($tweets, $searchFilter)
    {
        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $total = count($tweets);

        // $startDate = new DateTime($searchFilter['date'][0]);
        // $endDate = new DateTime($searchFilter['date'][1]);

        // If date filter empty → use earliest & latest tweet dates
        if (empty($searchFilter['date'][0]) || empty($searchFilter['date'][1])) {

            $dates = $tweets->map(fn($t) => new DateTime($t->date))->all();

            $startDate = min($dates);
            $endDate = max($dates);
        } else {
            $startDate = new DateTime($searchFilter['date'][0]);
            $endDate = new DateTime($searchFilter['date'][1]);
        }

        $keyword = $searchFilter['keywords'];
        $sentimentTypes = $searchFilter['sentimentTypes'];

        foreach ($tweets as $tweet) {
            $tweetDate = new DateTime($tweet->date);
            $sentiment = $tweet->sentiment;

            if (($tweetDate >= $startDate && $tweetDate <= $endDate) &&
                (empty($keyword) || stripos($tweet->text, $keyword) !== false || stripos($tweet->user, $keyword) !== false) &&
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

        return [
            "positiveTweets" => $positiveTweets,
            "negativeTweets" => $negativeTweets,
            "neutralTweets" => $neutralTweets,
            "totalTweets" => $total,
        ];
    }

    public function sentimentsTimeline($tweets, $searchFilter)
    {
        $dateMonthGroups = [];


        // $startDate = new DateTime($searchFilter['date'][0]);
        // $endDate = new DateTime($searchFilter['date'][1]);
        // If date filter empty → use earliest & latest tweet dates
        if (empty($searchFilter['date'][0]) || empty($searchFilter['date'][1])) {

            $dates = $tweets->map(fn($t) => new DateTime($t->date))->all();

            $startDate = min($dates);
            $endDate = max($dates);
        } else {
            $startDate = new DateTime($searchFilter['date'][0]);
            $endDate = new DateTime($searchFilter['date'][1]);
        }


        $keywords = $searchFilter['keywords'];
        $sentimentTypes = $searchFilter['sentimentTypes'];

        foreach ($tweets as $tweet) {
            $sentiment = $tweet->sentiment;
            $date = new DateTime($tweet->date);

            if (($date >= $startDate && $date <= $endDate) &&
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

                $dateMonthGroups[$formattedDate]['sentiments'][$sentiment]++;
            }
        }

        ksort($dateMonthGroups);

        return $dateMonthGroups;
    }

    public function tweetsByLocation($tweets, $searchFilter)
    {
        $placeTweetCounts = [];

        // $startDate = new DateTime($searchFilter['date'][0]);
        // $endDate = new DateTime($searchFilter['date'][1]);

        // If date filter empty → use earliest & latest tweet dates
        if (empty($searchFilter['date'][0]) || empty($searchFilter['date'][1])) {

            $dates = $tweets->map(fn($t) => new DateTime($t->date))->all();

            $startDate = min($dates);
            $endDate = max($dates);
        } else {
            $startDate = new DateTime($searchFilter['date'][0]);
            $endDate = new DateTime($searchFilter['date'][1]);
        }

        $keyword = $searchFilter['keywords'];
        $sentimentTypes = $searchFilter['sentimentTypes'];

        foreach ($tweets as $tweet) {
            $place = $tweet->place;
            $date = new DateTime($tweet->date);
            $sentiment = $tweet->sentiment;

            if (($date >= $startDate && $date <= $endDate) &&
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
