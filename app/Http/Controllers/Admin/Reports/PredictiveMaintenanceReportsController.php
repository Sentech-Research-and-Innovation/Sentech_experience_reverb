<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Prediction;
use Carbon\Carbon;
use DateTime;
use VerumConsilium\Browsershot\Facades\PDF;
use Symfony\Component\HttpFoundation\StreamedResponse;


class PredictiveMaintenanceReportsController extends Controller
{

    public function index()
    {
        $reportType = request()->reportType;

        $siteNamesSearch = request()->searchData['searchFilter']['siteNames'];
        $startDate = request()->searchData['searchFilter']['date'][0];
        $endDate = request()->searchData['searchFilter']['date'][1];

        // $startDate = "2024-01-01T08:54:00.000Z";
        // $endDate = "2024-12-26T08:54:00.000Z";
        // $siteNamesSearch = ["PORT ELIZABETH", "CONSTANTIABERG", "JOHANNESBURG"];


        if ($reportType == "pdf") {
            return $this->pdf($siteNamesSearch, $startDate, $endDate);
        } else {
            return $this->csv($siteNamesSearch, $startDate, $endDate);
        }


        // return $this->pdf($siteNamesSearch, $startDate, $endDate);
    }

    private function csv($siteNamesSearch, $startDate, $endDate)
    {
        $predictions = Prediction::whereIn('siteName', $siteNamesSearch)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()->toArray();


        $csvFileName = 'sentiments-report.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];

        $callback = function () use ($predictions) {
            $csvContent = fopen('php://output', 'w');
            fputcsv($csvContent, [
                'id',
                'item_id',
                'target_value',
                'alarm',
                'SiteName',
                'SiteCode',
                'Classification_x',
                'OC',
                'Region_x',
                'Province',
                'DeviceName',
                'DeviceIP',
                'MeasureDescription',
                'lowerPreAlarmTsh',
                'upperPreAlarmTsh',
                'lowerAlarmTsh',
                'upperAlarmTsh',
                'oid',
                'oidIndex',
                'Latitude',
                'Longitude',
                'updated_at',
                'created_at'
            ]);

            foreach ($predictions as $row) {
                fputcsv($csvContent, $row);
            }

            fclose($csvContent);
        };

        return new StreamedResponse($callback, 200, $headers);
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



        // ->setNodeBinary('/home/ubuntu/.nvm/versions/node/v16.0.0/bin/node')

        // ->setNpmBinary('/home/ubuntu/.nvm/versions/node/v16.0.0/bin/npm')->noSandbox();
        //   ->storeAs('pdfs/', $fileName);


        $fileName = time() . ".pdf";

        $footerHtml =  view('reports/footer')->render();
        $headerHtml =  view('reports/header')->render();

        $pdfStoredPath = PDF::loadView('reports/index', compact('data'))->margins(10, 25, 17, 25)
            ->showBrowserHeaderAndFooter()->footerHtml($footerHtml)->headerHtml($headerHtml)->showBackground();
        return $pdfStoredPath->download('report' . '.pdf');

        //  return view("reports/index", compact('data'));
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

        arsort($dateCounts);

        // Get top 10 dates
        $top10Dates = array_slice($dateCounts, 0, 10, true);

        // krsort($top10Dates);

        uksort($top10Dates, function ($a, $b) {
            $date1 = strtotime($a);
            $date2 = strtotime($b);
            return $date1 - $date2;
        });



        return $alarmSateByDate = [
            'labels' => array_keys($top10Dates),
            'series' => array_values($top10Dates),
        ];
    }
}
