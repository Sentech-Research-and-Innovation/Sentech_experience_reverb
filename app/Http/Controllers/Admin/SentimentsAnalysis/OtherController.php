<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\WordCloud;
use Inertia\Inertia;
use DateTime;

use App\Models\Sentiment as Tweet;

class OtherController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sentiments/Others/index');
    }

    public function map()
    {


        $mapData = [];
        $filterDate = request()->searchFilter['date'];
        $keyword = request()->searchFilter['keywords'];
        $sentimentTypes = request()->searchFilter['sentimentTypes'];

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        $query = Tweet::query();

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('text', 'like', '%' . $keyword . '%')
                    ->orWhere('user', 'like', '%' . $keyword . '%');
            });
        }


        if (!empty($sentimentTypes)) {
            $query->whereIn('sentiment', $sentimentTypes);
        }

        if ($startDate !== null) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate !== null) {
            $query->where('date', '<=', $endDate);
        }

        $tweets = $query->get();

        foreach ($tweets as $tweet) {
            $loc = $tweet->location_point;
            $name = $tweet->sentiment;
            $fill = "";

            if ($name == "NEGATIVE") {
                $fill = "rgba(255, 69, 96, 0.85)";
            } elseif ($name == "POSITIVE") {
                $fill = "rgb(0, 227, 150)";
            } elseif ($name == "NEUTRAL") {
                $fill = "rgb(119, 93, 208)";
            }

            $mapData[] = [
                "name" => $name,
                "coords" => [$loc, $loc],
                "style" => ["fill" => $fill]
            ];
        }

        return response()->json($mapData, 200);
    }
}
