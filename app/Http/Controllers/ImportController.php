<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BroadcastFrequencyImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use App\Models\FrequencyFinder;


class ImportController extends Controller
{

    public function index2()
    {
        $filePath = public_path('b1.csv');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        try {
            $file = fopen($filePath, 'r');
            // Read the header row
            $header = fgetcsv($file);

            if (!$header) {
                return response()->json(['error' => 'No data found in file.'], 400);
            }

            $chunkSize = 1000; // Number of rows per chunk
            $rowCount = 0;
            $chunk = [];

            while (($row = fgetcsv($file)) !== false) {
                $dataRow = array_combine($header, $row);
                $chunk[] = $dataRow;

                $rowCount++;

                if ($rowCount % $chunkSize == 0) {
                    $this->processChunk($chunk);
                    $chunk = []; // Reset chunk array
                }
            }

            // Process remaining rows
            if (!empty($chunk)) {
                $this->processChunk($chunk);
            }

            fclose($file);

            return response()->json(['message' => 'File imported successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing file.'], 500);
        }
    }

    private function processChunk(array $chunk)
    {
        foreach ($chunk as $dataRow) {
            FrequencyFinder::updateOrCreate(
                ['map_num' => $dataRow['MAP_NUM']], // Use a unique key if possible
                [
                    'prov_code' => $dataRow['PROV_CODE'],
                    'station_name' => $dataRow['STATION_NAME'],
                    'lat_deg' => $dataRow['LAT_DEG'],
                    'lat_min' => $dataRow['LAT_MIN'],
                    'lat_sec' => $dataRow['LAT_SEC'],
                    'long_deg' => $dataRow['LONG_DEG'],
                    'long_min' => $dataRow['LONG_MIN'],
                    'long_sec' => $dataRow['LONG_SEC'],
                    'map_num' => $dataRow['MAP_NUM'],
                    'serv_code' => $dataRow['SERV_CODE'],
                    'serv_name' => $dataRow['SERV_NAME'],
                    'serv_description' => $dataRow['SERV_DESCRIPTION'],
                    'tx_freq' => $dataRow['TX_FREQ'],
                    'tx_channel' => $dataRow['TX_CHANNEL'],
                ]
            );
        }
    }



    public function index()
    {
        set_time_limit(300); // 300 seconds = 5 minutes


        $filePath = public_path('nnn.xlsx');

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            // Skip the header row
            $header = array_shift($data);

            foreach ($data as $row) {
                $dataRow = array_combine($header, $row);

                FrequencyFinder::create([

                    'province_code' => $dataRow['PROV_CODE'],
                    'station_name' => $dataRow['STATION_NAME'],
                    'lat_deg' => $dataRow['LAT_DEG'],
                    'lat_min' => $dataRow['LAT_MIN'],
                    'lat_sec' => $dataRow['LAT_SEC'],
                    'long_deg' => $dataRow['LONG_DEG'],
                    'long_min' => $dataRow['LONG_MIN'],
                    'long_sec' => $dataRow['LONG_SEC'],
                    'map_num' => $dataRow['MAP_NUM'],
                    'serv_code' => $dataRow['SERV_CODE'],
                    'serv_name' => $dataRow['SERV_NAME'],
                    'serv_description' => $dataRow['SERV_DESCRIPTION'],
                    'tx_freq' => $dataRow['TX_FREQ'],
                    'tx_channel' => $dataRow['TX_CHANNEL'],

                ]);
            }

            return response()->json(['message' => 'File imported successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing file.'], 500);
        }
    }
}
