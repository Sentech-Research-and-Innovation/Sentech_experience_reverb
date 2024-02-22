<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Network;

use DateTime;

class NetworkController extends Controller
{

    public function index()
    {

        $csvFilePath = public_path('/alarmList.csv');

        // Open the CSV file for reading
        $file = fopen($csvFilePath, 'r');

        // Get the CSV headers
        $headers = fgetcsv($file);

        // Read and insert each row
        while (($data = fgetcsv($file)) !== false) {
            // Combine headers with current row data
            $row = array_combine($headers, $data);

            // return $row['EventOutDateTime'];


            //  $row['EventInDateTime'] = $this->convertNumericDate($row['EventInDateTime']);

            // $row['EventOutDateTime'] = $this->convertNumericDate($row['EventOutDateTime']);



            // Insert data into the database using Eloquent
            Network::create($row);
        }

        // Close the CSV file
        fclose($file);
    }

    public function show()
    {
        return Inertia::render('Web/networks/provinceStatus');
    }
}
