<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Prediction;
use Carbon\Carbon;

ini_set('memory_limit', '512M');
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
        try {
            $directory = 'current-prediction/';

            $files = Storage::disk('s3')->allFiles($directory);
            foreach ($files as $filePath) {
                if (preg_match('/^prediction-\d{2}-\d{2}-\d{4}-\d{2}-\d{2}-\d{2}\.csv$/', basename($filePath))) {
                    return $this->processCsvFile($filePath);
                }
            }

            $this->info('CSV data processed and stored successfully.');
        } catch (\Exception $e) {
            $this->error('Error processing CSV data: ' . $e->getMessage());
        }
    }

    private function processCsvFile($filePath)
    {
        $fileContents = Storage::disk('s3')->get($filePath);

        $csvData = str_getcsv($fileContents, "\n");

        $header = str_getcsv(array_shift($csvData));

        $parsedData = [];

        foreach ($csvData as $row) {
            $parsedData[] = array_combine($header, str_getcsv($row));
        }

        $this->info("data '$csvData[0]'");

        Prediction::truncate();

        $chunkedData = array_chunk($parsedData, 100);

        foreach ($chunkedData as $chunk) {
            $insertData = [];

            foreach ($chunk as $row) {
                $insertData[] = [
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
                    "Latitude" => $row['Latitude (#)'],
                    "Longitude" => $row['Longitude (#)'],
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s'),
                ];
            }

            Prediction::insert($insertData);
        }

        $this->info("CSV file '$filePath' processed and stored.");
    }
}
