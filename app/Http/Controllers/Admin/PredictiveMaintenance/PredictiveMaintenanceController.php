<?php


namespace App\Http\Controllers\Admin\PredictiveMaintenance;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

use App\Models\Prediction;
use DateTime;


class PredictiveMaintenanceController extends Controller
{

    public function index()
    {

        $predictions = Prediction::all();

        return Inertia::render('Admin/PredictiveMaintenance/Index', compact('predictions'));
    }

    public function getPredictions()
    {
        $siteNamesSearch = request()->searchData['searchFilter']['siteNames'];
        $startDate = request()->searchData['searchFilter']['date'][0];
        $endDate = request()->searchData['searchFilter']['date'][1];

        // return $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);
        // $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);

        // return  new DateTime($startDate);
        // $endDate = new DateTime($endDate);

        // "2023-03-01T08:54:00.000Z",
        // "2023-08-26T08:54:00.000Z",

        $predictions = Prediction::whereIn('siteName', $siteNamesSearch)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return response()->json($predictions, 200);
    }

    public function detailedView()
    {
        $predictions = Prediction::all();
        return Inertia::render('Admin/PredictiveMaintenance/DetailedView/Index', compact('predictions'));
    }

    public function detailedViewData()
    {
        $predictions = Prediction::all();
        return response()->json($predictions, 200);
    }
}
