<?php


namespace App\Models\System;


class ActionResponse
{

    public static function error($message, $errorBag):array{
        return ['message'=>$message,'errorBag'=>$errorBag,'success'=>false];
    }
    public static function success($message, $data): array{
        return ['message'=>$message,'data'=>$data,'success'=>true];
    }
}
