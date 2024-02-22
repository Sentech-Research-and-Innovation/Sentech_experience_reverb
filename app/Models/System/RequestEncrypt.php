<?php


namespace App\Models\System;


//use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class RequestEncrypt
{
    public static function encrypt($request): array
    {
        $encryptedData = [];
        if (!is_null($request)) {
            if (count($request)) {
                foreach ($request as $key => $value) {
                    $encryptedData[$key] = $value;
                    if (!in_array($key, config('system_config.do_not_encrypt'))) {
                        $encryptedData[$key] = Crypt::encryptString($value);
                    }
                }
            }
        }
        return $encryptedData;
    }

    public static function decrypt($request): array
    {
        $decryptedData = [];
        if (!is_null($request)) {
            if (count($request)) {
                foreach ($request as $key => $value) {
                    $decryptedData[$key] = $value;
                    if(!is_array($value)){
                        if (!in_array($key, config('system_config.do_not_encrypt'))) {
                            if (!is_null($value) && $value!=="") {
                                if (!is_array($value)) {
                                    $decryptedData[$key] = Crypt::decryptString($value);
                                } else {
                                    foreach ($decryptedData[$key] as $k => $dData) {
                                        if (!is_array($dData)) {
                                            $decryptedData[$key][$k] = Crypt::decryptString($dData);
                                        }
                                    }
                                }
                            }
                        }
                    }else{

                        foreach ($value as $i => $item){
                            if (!in_array($i, config('system_config.do_not_encrypt'))) {
                                if (!is_null($item) && $item!=="") {
                                    if (!is_array($item)) {
                                        $decryptedData[$i] = Crypt::decryptString($item);
                                    } else {
                                        foreach ($decryptedData[$key][$i] as $k => $dData) {
                                            if (!in_array($k, config('system_config.do_not_encrypt'))) {
                                                if (!is_array($dData)) {
                                                    $decryptedData[$key][$i][$k] = Crypt::decryptString($dData);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }

        return $decryptedData;
    }

    public  static function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }



}
