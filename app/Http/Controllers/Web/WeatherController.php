<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use GeoIP as GeoIP;

use Inertia\Inertia;


class WeatherController extends Controller
{

    public function create()
    {

        //  $location  = GeoIP::getLocation('105.22.37.114');

        //  $locationData = [
        //     'ip' => $location->ip,
        //     'country_code' => $location->country_code,
        //     'country' => $location->country,
        //     'city' => $location->city,
        //     'lon' =>  $location->lon,
        //     'lat' =>  $location->lat,

        //     // Add other properties you want to include in the response
        // ];

        $clientIP = $_SERVER['REMOTE_ADDR'];

        // Check if the IP address is coming through a proxy
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        }

        $location  = GeoIP::getLocation($clientIP);

        $lat =  $location->lat;
        $lon =  $location->lon;
        $city = ["city" => $location->city];
        $weather = Http::get("https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $lon . "&mode=json&units=metric&appid=e695570b6f7c7b11ff6b8dd74c8f7865");

        return   $data = [json_decode($weather), $city];
    }

    public function forecast()
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];

        // Check if the IP address is coming through a proxy
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        }

        $location = GeoIP::getLocation($clientIP);

        $lat = $location->lat;
        $lon = $location->lon;


        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast?lat=" . $lat . "&lon=" . $lon . "&mode=json&units=metric&appid=e695570b6f7c7b11ff6b8dd74c8f7865");
        $forecast = $response->json();
        // if ($weatherData->successful()) {
        //     $data = $weatherData->json();
        //     $response = [];
        //     $count = 0;
        //     foreach ($data['list'] as $dt) {
        //         $dateKey = date('Y-m-d', $dt['dt']);
        //         $timeKey = date('H:i:s', $dt['dt']);

        //         // Check if the date key exists in the response array
        //         if (!isset($response[$dateKey])) {
        //             $response[$dateKey] = [];
        //         }
        //         $count++;
        //         // Append the data to the response array under the date and time keys
        //         $response[$dateKey][$timeKey] = [
        //             'main' => $dt['main'],
        //             'weather' => $dt['weather'],
        //             'clouds' => $dt['clouds'],
        //             'wind' => $dt['wind'],
        //             'visibility' => $dt['visibility'],
        //             'sys' => $dt['sys'],
        //             'dt_txt' => $timeKey,
        //         ];

        //         if ($count == 6) {
        //             break;
        //         }
        //     }

        //     // You should decide whether to return JSON or render the Inertia view
        //     // In this example, I'm returning the JSON response
        //     // return response()->json($response);

        // } else {
        //     // Handle the API request error here
        //     return response()->json(['error' => 'API request failed'], 500);
        // }

        $currentWeather = $this->create();

        $data = array_merge($currentWeather, ["forecast" => $forecast]);

        return Inertia::render('Admin/WeatherForcast/Index', compact('data'));
    }
}
