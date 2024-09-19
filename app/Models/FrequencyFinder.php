<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrequencyFinder extends Model
{
    protected $fillable = [
        'province_code',
        'station_name',
        'lat_deg',
        'lat_min',
        'lat_sec',
        'long_deg',
        'long_min',
        'long_sec',
        'map_num',
        'serv_code',
        'serv_name',
        'serv_description',
        'tx_freq',
        'tx_channel'
    ];


    protected $table = 'frequency_finder';
}
