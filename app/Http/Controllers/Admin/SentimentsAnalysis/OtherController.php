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

        $startDate = null;
        $endDate = null;

        if ($filterDate !== null) {
            $startDate = new DateTime($filterDate[0]);
            $endDate = new DateTime($filterDate[1]);
        }

        foreach ($data['hits']['hits'] as $hit) {
            $dateStr = $hit['_source']['date'];
            $date = new DateTime($dateStr);

            // Check if the tweet's date falls within the specified date range (if not null)
            if (($startDate === null || $date >= $startDate) && ($endDate === null || $date <= $endDate)) {
                if ($keyword !== null) {
                    if (stripos($hit['_source']['text'], $keyword) !== false) {
                        $loc = $hit['_source']['location_point'];
                        $name = $hit['_source']['sentiment'];
                        $fill = "";

                        if ($name == "negative") {
                            $fill = "#ec1c24";
                        }
                        if ($name == "positive") {
                            $fill = "#00c83c";
                        }
                        if ($name == "neutral") {
                            $fill = "#118dff";
                        }

                        $mapData[] = [
                            "name" => $name,
                            "coords" => [$loc['lat'], $loc['lon']],
                            "style" => ["fill" => $fill]
                        ];
                    }
                } else {
                    $loc = $hit['_source']['location_point'];
                    $name = $hit['_source']['sentiment'];
                    $fill = "";

                    if ($name == "negative") {
                        $fill = "#ec1c24";
                    }
                    if ($name == "positive") {
                        $fill = "#00c83c";
                    }
                    if ($name == "neutral") {
                        $fill = "#118dff";
                    }

                    $mapData[] = [
                        "name" => $name,
                        "coords" => [$loc['lat'], $loc['lon']],
                        "style" => ["fill" => $fill]
                    ];
                }
            }
        }

        return response()->json($mapData, 200);
    }
}
