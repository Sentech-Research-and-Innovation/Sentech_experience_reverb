<?php


namespace App\Models\System;


class ArrayHandler
{

    public static function array_flatten($array)
    {
        if (!is_array($array)) {
            return FALSE;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public  static function array_search ($value,$array,$column){
      return array_search($value, array_column($array, $column));
    }

    public  static function unsetKeys($arr,$data){
        foreach ($arr as $key => $unset){
            if (isset($data[$unset])) {
                unset($data[$unset]);
            }
        }
        return $data;
    }

    public  static function unsetKeysOnNull($data){
        foreach ($data as $key => $value){
            if (is_null($value) || $value ==='') {
                unset($data[$key]);
            }
        }
        return $data;
    }

    public static function array_validate($array,$message){
        if(!is_null($array)){
            if(count($array)){
                return ActionResponse::success($message,$array,true);
            }
        }
        return ActionResponse::error('Error validating array',$array,true);
    }
}
