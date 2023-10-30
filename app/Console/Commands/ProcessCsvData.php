<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Prediction;
use Carbon\Carbon;


class ProcessCsvData extends Command
{
    protected $signature = 'csv:process';
    protected $description = 'Process CSV data and store it in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $yesterday_date = Carbon::yesterday()->toDateString();

        // $filePath = 'measures-data/historical-data/' . $yesterday_date . '_Measures.csv';
        $filePath = 'measures-data/historical-data/2023-09-20_Measures.csv';

        $fileContents = Storage::disk('s3')->get($filePath);

        $csvData = str_getcsv($fileContents, "\n");

        $header = str_getcsv(array_shift($csvData));

        $parsedData = [];

        foreach ($csvData as $row) {
            $parsedData[] = array_combine($header, str_getcsv($row));
        }


        foreach ($parsedData as $row) {

            Prediction::create(
                [
                    "item_id" => $row['item_id'],
                    "date" => $row['date'],
                    'target_value' => $row['target_value'],
                    'alarm' => $row['alarm'],
                    "SiteName" => $row['SiteName'],
                    "SiteCode" => $row['SiteCode'],
                    "Classification_x" => $row['Classification_x'],
                    "OC" => $row['OC'],
                    "Region_x" => $row['Region_x'],
                    "Province" => $row['Province'],
                    "DeviceName" => $row['DeviceName'],
                    "DeviceIP" => $row['DeviceIP'],
                    "MeasureDescription" => $row['MeasureDescription'],
                    "lowerPreAlarmTsh" => $row['lowerPreAlarmTsh'],
                    "upperPreAlarmTsh" => $row['upperPreAlarmTsh'],
                    "lowerAlarmTsh" => $row['lowerAlarmTsh'],
                    "upperAlarmTsh" => $row['upperAlarmTsh'],
                    "oid" => $row['oid'],
                    "oidIndex" => $row['oidIndex'],
                ]

            );
        }

        $this->info('CSV data processed and stored successfully.');
    }
}
