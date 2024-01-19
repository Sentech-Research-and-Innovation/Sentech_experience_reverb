<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Prediction;
use PDF2;
use Carbon\Carbon;

class PredictiveMaintenanceReportsController extends Controller
{
    public function index()
    {


        $siteNamesSearch = request()->searchData['searchFilter']['siteNames'];
        $startDate = request()->searchData['searchFilter']['date'][0];
        $endDate = request()->searchData['searchFilter']['date'][1];


        $data = [
            'siteNames' => $siteNamesSearch,
            'start_date' =>  $this->dateFormat($startDate),
            'end_date' =>  $this->dateFormat($endDate),
        ];

        return $counts = Prediction::whereIn('siteName', $siteNamesSearch)
            ->groupBy('siteName')
            ->selectRaw('siteName, count(*) as total')
            ->pluck('total', 'siteName');
        // {"JOHANNESBURG":1085,"PORT ELIZABETH":763,"CONSTANTIABERG":826}

        // {
        //     "siteNames": [
        //         "PORT ELIZABETH",
        //         "CONSTANTIABERG",
        //         "JOHANNESBURG"
        //     ],
        //     "start_date": "2023-01-01T08:54:00.000Z",
        //     "end_date": "2023-12-26T08:54:00.000Z"
        // }

        $pdf = PDF2::loadView('reports/index', compact('data'))->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-right', 0)->setOption('margin-left', 0);
        return $pdf->download('test0012' . '.pdf');
    }

    private function dateFormat($date)
    {
        $carbonDate = Carbon::parse($date);
        return $carbonDate->format('Y-m-d');
    }
}
