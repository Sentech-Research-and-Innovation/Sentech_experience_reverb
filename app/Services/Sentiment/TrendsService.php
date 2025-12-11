<?php

namespace App\Services\Sentiment;

use DateTime;
use App\Models\Sentiment as Tweet;

class TrendsService
{


    public function tweetsContent($searchFilter)
    {
        $positiveTweets = 0;
        $negativeTweets = 0;
        $neutralTweets = 0;
        $tweetsContent = [];

        $filterDate = $searchFilter['date'] ?? null;
        $keyword = $searchFilter['keywords'] ?? null;
        $sentimentTypes = $searchFilter['sentimentTypes'] ?? null;

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        // Initialize the query builder
        $tweets = Tweet::query();

        $tweets->select('*')
            ->groupBy('text');

        // Apply keyword filter
        if (!empty($keyword)) {
            $tweets->where(function ($q) use ($keyword) {
                $q->where('text', 'like', '%' . $keyword . '%')
                    ->orWhere('user', 'like', '%' . $keyword . '%');
            });
        }

        // Apply sentiment types filter
        if (!empty($sentimentTypes)) {
            $tweets->whereIn('sentiment', $sentimentTypes);
        }

        // Apply date range filter
        if ($startDate !== null) {
            $tweets->where('date', '>=', $startDate);
        }

        if ($endDate !== null) {
            $tweets->where('date', '<=', $endDate);
        }

        // Apply ordering and limit
        $tweets = $tweets->orderBy('date', 'desc')->limit(100)->get();

        // Process tweets
        if(!empty($sentimentTypes)){
             foreach ($tweets as $tweet) {
            // Update sentiment counts
            if ($tweet->sentiment === 'POSITIVE') {
                $positiveTweets++;
            } elseif ($tweet->sentiment === 'NEGATIVE') {
                $negativeTweets++;
            } elseif ($tweet->sentiment === 'NEUTRAL') {
                $neutralTweets++;
            }

            // Build tweet content
            $tweetsContent[] = [
                "text" => $tweet->text,
                "sentiment" => $tweet->sentiment,
                "date" => $tweet->date,
                "user" => $tweet->user
            ];
        }

        }

        // Return response
        return [
            "positiveTweets" => $positiveTweets,
            "negativeTweets" => $negativeTweets,
            "neutralTweets" => $neutralTweets,
            "tweetsContent" => $tweetsContent
        ];
    }
}
