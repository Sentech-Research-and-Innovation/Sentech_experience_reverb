<?php

namespace App\Services\Sentiment;

use DateTime;

class TrendsService
{


    public function tweetsContent($tweets, $searchFilter)
    {

        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;

        $tweetsContent = [];

        $filterDate = $searchFilter['date'];
        $keyword = $searchFilter['keywords'];
        $sentimentTypes = $searchFilter['sentimentTypes'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        //  $tweets = Tweet::limit(100);

        // return$tweets = Tweet::all();

        if (!empty($keyword)) {
            $tweets->where(function ($q) use ($keyword) {
                $q->where('text', 'like', '%' . $keyword . '%')
                    ->orWhere('user', 'like', '%' . $keyword . '%');
            });
        }

        if (!empty($sentimentTypes)) {
            $tweets->whereIn('sentiment', $sentimentTypes);
        }

        if ($startDate !== null) {
            $tweets->where('date', '>=', $startDate);
        }

        if ($endDate !== null) {
            $tweets->where('date', '<=', $endDate);
        }

        // $tweets = $tweets->get();

        foreach ($tweets as $tweet) {
            // Update sentiment counts based on tweet's sentiment
            if ($tweet->sentiment === 'POSITIVE') {
                $positiveTweets++;
            } elseif ($tweet->sentiment === 'NEGATIVE') {
                $negativeTweets++;
            } elseif ($tweet->sentiment == 'NEUTRAL') {
                $neutralTweets++;
            }

            $tweetsContent[] = [
                "text" => $tweet->text,
                "sentiment" => $tweet->sentiment,
                "date" => $tweet->date,
                "user" => $tweet->user
            ];
        }

        return $response = [
            "positiveTweets" => $positiveTweets,
            "negativeTweets" => $negativeTweets,
            "neutralTweets" => $neutralTweets,
            "tweetsContent" => $tweetsContent
        ];
    }
}
