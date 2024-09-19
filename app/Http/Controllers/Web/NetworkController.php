<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Network;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\FrequencyFinder;

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
        $req = FrequencyFinder::where('province_code', $province)->where('serv_name', "<>", "null")
            ->get()
            ->groupBy('station_name');

        return $req;
    }

    //



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
