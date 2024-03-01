<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Network;
use Illuminate\Support\Facades\DB;
use DateTime;

class NetworkController extends Controller
{

    public function index()
    {
        return 1;
    }

    public function show()
    {
        return Inertia::render('Web/networks/provinceStatus');


        // $csvFilePath = public_path('/Measures.csv');

        // $file = fopen($csvFilePath, 'r');

        // $headers = fgetcsv($file);

        // while (($data = fgetcsv($file)) !== false) {
        //     $row = array_combine($headers, $data);


        //     // $row['EventInDateTime'] = str_replace(',', '', $row['EventInDateTime']);
        //     // $row['EventOutDateTime']  = str_replace(',', '', $row['EventOutDateTime']);
        //     // $row['EventInDateTime'] = DateTime::createFromFormat('YmdHis', substr($row['EventInDateTime'], 0, 14))->format('Y-m-d H:i:s');


        //     // $row['EventOutDateTime'] = DateTime::createFromFormat('YmdHis', substr($row['EventOutDateTime'], 0, 14))->format('Y-m-d H:i:s');

        //     Network::create($row);
        // }

        // fclose($file);
    }

    public function provinceCities($province)
    {
        // / 
        $data = Network::where('Province', $province)->select('SiteName')->distinct()->get();
        return $data;
    }

    public function getAlarmsDataByProvince($province)
    {
        // return $latestRecords = Network::where('Province', $province)
        //     ->select('SiteName', DB::raw('MAX(DeviceName) as DeviceName'), DB::raw('MAX(EventInDateTime) as EventInDateTime'), DB::raw('MAX(EventOutDateTime) as EventOutDateTime'))
        //     ->groupBy('SiteName')
        //     ->get();

        // return $latestRecords = Network::where('Province', $province)
        //     ->where('DeviceName', 'like', '%DVMS4%')
        //     ->orderBy('SiteName')
        //     ->get()
        //     ->groupBy('SiteName');
        $data = Network::where('Province', $province)
            ->where(function ($query) {
                $query->where('DeviceName', 'like', '%DVMS4%')
                    ->orWhere('DeviceName', 'like', '%TX%');
            })
            ->where(function ($query) {
                $query->where('MeasureDescription', 'like', '%TS Sync Loss%')
                    ->orWhere('MeasureDescription', 'like', '%FWD Power%')
                    ->orWhere('MeasureDescription', 'like', '%Forward Power%');
            })
            ->select('SiteName', 'MeasureDescription', 'DeviceIP', 'FormattedDateTimeEvent', 'DeviceName')
            ->orderBy('SiteName')
            ->get()
            ->groupBy('SiteName', 'MeasureDescription', 'DeviceIP');







        $latestRecords = [];

        foreach ($data as $siteName => $records) {
            foreach ($records as $record) {
                $key = $record['SiteName'] . $record['DeviceIP'];

                // Check if the key exists and the current record has a later date
                if (!isset($latestRecords[$key]) || $record['FormattedDateTimeEvent'] > $latestRecords[$key]['FormattedDateTimeEvent']) {
                    $latestRecords[$key] = $record;
                }
            }
        }

        // Get the values from the associative array to get the final result
        $filteredData = array_values($latestRecords);


        return $filteredData;
    }





    // $csvFilePath = public_path('/alarmList.csv');

    // $file = fopen($csvFilePath, 'r');

    // $headers = fgetcsv($file);

    // while (($data = fgetcsv($file)) !== false) {
    //     $row = array_combine($headers, $data);


    //     $row['EventInDateTime'] = str_replace(',', '', $row['EventInDateTime']);
    //     $row['EventOutDateTime']  = str_replace(',', '', $row['EventOutDateTime']);
    //     $row['EventInDateTime'] = DateTime::createFromFormat('YmdHis', substr($row['EventInDateTime'], 0, 14))->format('Y-m-d H:i:s');
    //     $row['EventOutDateTime'] = DateTime::createFromFormat('YmdHis', substr($row['EventOutDateTime'], 0, 14))->format('Y-m-d H:i:s');

    //     Network::create($row);
    // }

    // fclose($file);
}
