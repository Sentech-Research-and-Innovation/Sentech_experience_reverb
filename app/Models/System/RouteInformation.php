<?php


namespace App\Models\System;


use Illuminate\Support\Facades\Route;

class RouteInformation
{

    public  static function route(){
        $route = Route::getCurrentRoute();
        if($route->uri);
    }
}
