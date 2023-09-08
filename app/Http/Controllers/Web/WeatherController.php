<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class WeatherController extends Controller
{

    public function create()
    {

        $lat = request()->lat;
        $lon = request()->lon;

        $weather = Http::acceptJson()
            ->get("https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $lon . "&mode=json&units=metric&appid=e695570b6f7c7b11ff6b8dd74c8f7865");

        return $weather;
    }
}
