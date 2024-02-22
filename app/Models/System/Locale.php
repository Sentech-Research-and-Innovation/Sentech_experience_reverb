<?php


namespace App\Models\System;


class Locale
{

    public static function location():object{
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
//        $remote  = @$_SERVER['REMOTE_ADDR'];
        $remote  = '105.22.37.114';
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }
        return  @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
    }
}
