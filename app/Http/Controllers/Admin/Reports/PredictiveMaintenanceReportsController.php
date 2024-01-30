<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Prediction;
use Carbon\Carbon;
use DateTime;
use VerumConsilium\Browsershot\Facades\PDF;



class PredictiveMaintenanceReportsController extends Controller
{

    public function index()
    {
        $reportType = request()->reportType;

        $siteNamesSearch = request()->searchData['searchFilter']['siteNames'];
        $startDate = request()->searchData['searchFilter']['date'][0];
        $endDate = request()->searchData['searchFilter']['date'][1];


        // $siteNamesSearch = [
        //     "PORT ELIZABETH",
        //     "CONSTANTIABERG",
        //     "JOHANNESBURG"
        // ];
        // $startDate = new DateTime("2023-01-26T08:54:00.000Z");
        // $endDate = new DateTime("2023-12-01T08:54:00.000Z");


        if ($reportType == "pdf") {
            return $this->pdf($siteNamesSearch, $startDate, $endDate);
        } else {
            return $this->csv();
        }
    }

    private function csv()
    {
    }

    private function pdf($siteNamesSearch, $startDate, $endDate)
    {
        $predictions = Prediction::whereIn('siteName', $siteNamesSearch)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $distinctItemIds = [];
        $monitoredSensorCount = 0;

        foreach ($predictions as $prediction) {
            $itemId = $prediction->item_id;

            if (!isset($distinctItemIds[$itemId])) {
                $distinctItemIds[$itemId] = true;
                $monitoredSensorCount++;
            }
        }

        $alarmCounts = $predictions->countBy('alarm')->toArray();

        $alarmStatusCount = [];

        foreach ($alarmCounts as $alarmValue => $count) {
            $alarmStatusCount[] = [
                'alarmValue' => $alarmValue,
                'alarmStatus' => ($alarmValue == 0) ? 'Alarm' : 'Normal',
                'count' => $count,
            ];
        }

        $classCounts = $predictions->countBy('Classification_x')->toArray();

        $classSatusCount = [];

        foreach ($classCounts as $classValue => $count) {
            $classSatusCount[] = [
                'classValue' => $classValue,
                'count' => $count,
            ];
        }


        $deviceNamesCount = $predictions
            ->unique('DeviceName')
            ->count();


        $sensorInAlarmBySite = $this->getSensorInAlarmBySite($predictions);

        $alarmSateByDate = $this->top10Dates($predictions);

        $data = [
            'siteNames' => $siteNamesSearch,
            'start_date' =>  $this->dateFormat($startDate),
            'end_date' =>  $this->dateFormat($endDate),
            'monitoredSensorCount' => $monitoredSensorCount,
            'sensorInAlarmBySite' => $sensorInAlarmBySite,
            'alarmStatusCount' => $alarmStatusCount,
            'classSatusCount' => $classSatusCount,
            'deviceNamesCount' => $deviceNamesCount,
            'alarmSateByDate' => $alarmSateByDate
        ];


        $fileName = time() . ".pdf";
        $pdfStoredPath = PDF::loadView('reports/index', compact('data'))->margins(10, 0, 0, 0);
        // ->setNodeBinary('c:\src\nodejs\node.exe')
        // ->setNpmBinary('c:\src\nodejs\npm')
        //   ->storeAs('pdfs/', $fileName);
        return $pdfStoredPath->download('report' . '.pdf');

        // $headers = [
        //      'Content-Type' => 'application/pdf', // Adjust the content type based on your file type
        // ];
        //return  $filePath = storage_path('/app/pdfs/' . $fileName);

        // Send the file as a download response with the specified content type
        //return response()->download($filePath, $fileName, $headers);
        //return view('reports.index', compact('data'));
    }

    private function dateFormat($date)
    {
        $carbonDate = Carbon::parse($date);
        return $carbonDate->format('Y-m-d');
    }

    private function getSensorInAlarmBySite($predictions)
    {
        $uniqueItemCounts = [];

        foreach ($predictions as $prediction) {

            $siteName = $prediction->SiteName;

            if ($prediction->alarm == 0) {

                if (!isset($uniqueItemCounts[$siteName])) {
                    $uniqueItemCounts[$siteName] = [];
                }

                if (!isset($uniqueItemCounts[$siteName][$prediction->item_id])) {
                    $uniqueItemCounts[$siteName][$prediction->item_id] = true;
                }
            }
        }

        $sensorInAlarmBySite = [];

        foreach ($uniqueItemCounts as $siteName => $itemCounts) {
            $siteCount = count($itemCounts);
            $sensorInAlarmBySite[] = ['siteName' => $siteName, 'uniqueItemCount' => $siteCount];
        }

        return $sensorInAlarmBySite;
    }

    private function top10Dates($predictions)
    {
        $predictionsArray = $predictions->toArray();

        $filteredPredictions = array_filter($predictionsArray, function ($prediction) {
            return $prediction['alarm'] == 0;
        });

        $dateCounts = [];

        foreach ($filteredPredictions as $data) {
            $formattedDate = Carbon::parse($data['date'])->format('d M Y');

            if (!isset($dateCounts[$formattedDate])) {
                $dateCounts[$formattedDate] = 1;
            } else {
                $dateCounts[$formattedDate]++;
            }
        }

        ksort($dateCounts);

        $top10Dates = array_slice($dateCounts, 0, 10, true);

        return $alarmSateByDate = [
            'labels' => array_keys($top10Dates),
            'series' => array_values($top10Dates),
        ];
    }
}
