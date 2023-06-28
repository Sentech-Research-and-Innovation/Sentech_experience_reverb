<?php


namespace App\Models\System;


class ActivityLog
{

    public  function log($request){

        $request =[
            'user_id'=>'',
            'controller'=>'',
            'method'=>'',
            'function'=>'',
            'status'=>'', //pass or fail
            'data'=>''
        ];
        dd($request);
    }
}
