<?php


namespace App\Http\Controllers\Admin\PredictiveMaintenance;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Prediction;
use DateTime;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use Illuminate\Http\Request;

class PredictiveMaintenanceController extends Controller
{

    public function index()
    {


        $predictions = Prediction::orderby('date', 'DESC')->get();
        return Inertia::render('Admin/PredictiveMaintenance/Index', compact('predictions'));
    }

    public function getPredictions()
    {
        $siteNamesSearch = request()->searchData['searchFilter']['siteNames'];

        if (request()->searchData['searchFilter']['date'] !== null) {
            $startDate = request()->searchData['searchFilter']['date'][0];
            $endDate =  Carbon::parse(request()->searchData['searchFilter']['date'][1])->addDay();
        } else {

            $startDate = Prediction::min('date');
            $endDate = Prediction::max('date');
        }


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


    public function predictionsFiltered(Request $request)
    {
        $query = Prediction::query();

        $params = $request->input('params', []);

        // if (isset($params['siteNames'])) {
        //     // Check if siteNames is an array of arrays
        //     if (is_array($params['siteNames'][0])) {
        //         $siteNames = array_merge(...array_map('array_values', $params['siteNames']));
        //     } else {
        //         $siteNames = $params['siteNames'];
        //     }
        //     $query->whereIn('SiteName', $siteNames);
        // }


        if (isset($params['siteNames'])) {
            $siteNames = $params['siteNames'];
            $query->whereIn('SiteName', $siteNames);
        }


        // Flatten and process the measureDescription array if present
        if (isset($params['measureDecription'])) {
            $measureDescriptions = $params['measureDecription'];
            $query->whereIn('MeasureDescription', $measureDescriptions);
        }

        // Flatten and process the deviceName array if present
        if (isset($params['deviceName'])) {
            $deviceNames = $params['deviceName'];
            $query->whereIn('DeviceName', $deviceNames);
        }

        // Flatten and process the classification array if present


        if (isset($params['classification'])) {
            $classifications = $params['classification'];
            $query->whereIn('Classification_x', $classifications);
        }

        // Flatten and process the alarmFlag array if present


        // if (isset($params['alarmFlag'])) {
        //     // Check if siteNames is an array of arrays
        //     if (is_array($params['alarmFlag'][0])) {
        //         $alarmFlags = array_merge(...array_map('array_values', $params['alarmFlag']));
        //     } else {
        //         $alarmFlags = $params['alarmFlag'];
        //     }
        //     $query->whereIn('alarm', $alarmFlags);
        // }

        if (isset($params['alarmFlag'])) {
            $alarmFlags = $params['alarmFlag'];
            $query->whereIn('alarm', $alarmFlags);
        }

        // Uncomment and adjust if date filtering is needed
        // if (isset($params['date'])) {
        //     $dates = $params['date'];
        //     if (!empty($dates) && count($dates) === 2) {
        //         $query->whereBetween('date', [$dates[0], $dates[1]]);
        //     }
        // }

        $predictions = $query->paginate(100);

        return response()->json($predictions);
    }




    public function alarmFlag()
    {
        $alarms = 0;
        $normal = 0;
        $preAlarm = 0;

        $data = Prediction::all();

        foreach ($data as $dt) {
            switch ($dt['alarm']) {
                case 0:
                    $alarms++;
                    break;
                case 1:
                    $normal++;
                    break;
                default:
                    $preAlarm++;
                    break;
            }
        }

        $response = [
            "alarm_count" => $alarms,
            "normal_count" => $normal,
            "preAlarm_count" => $preAlarm,
        ];

        return response()->json($response, 200);
    }
}
