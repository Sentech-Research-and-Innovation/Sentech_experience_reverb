<?php


namespace App\Http\Controllers\Admin\SentimentsAnalysis;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;
use App\Models\WordCloud;
use Inertia\Inertia;
use DateTime;

class OtherController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Sentiments/Others/index');
    }

    public function map()
    {
        $jsonData = Http::get('http://13.244.120.32:81/twitter/_search?size=10000');
        $data = json_decode($jsonData, true);

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

        foreach ($data['hits']['hits'] as $hit) {
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);
            $sentiment = $hit['_source']['sentiment'];

            // Check if the tweet's date falls within the specified date range (if not null)
            // and if the sentiment type is in the specified sentimentTypes
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate) &&
                (empty($keyword) || stripos($hit['_source']['text'], $keyword) !== false) &&
                (in_array($sentiment, $sentimentTypes))
            ) {

                $loc = $hit['_source']['location_point'];
                $name = $sentiment;
                $fill = "";

                if ($name == "negative") {
                    $fill = "rgba(255, 69, 96, 0.85)";
                } elseif ($name == "positive") {
                    $fill = "rgb(0, 227, 150)";
                } elseif ($name == "neutral") {
                    $fill = "rgb(119, 93, 208)";
                }

                $mapData[] = [
                    "name" => $name,
                    "coords" => [$loc['lat'], $loc['lon']],
                    "style" => ["fill" => $fill]
                ];
            }
        }

        return response()->json($mapData, 200);
    }
}
