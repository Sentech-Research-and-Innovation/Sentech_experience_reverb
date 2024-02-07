<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

trait ActivityLogTrait
{


    public function StoreActivity($message)
    {
        $userId = auth()->user()->id;


        $clientIP = $_SERVER['REMOTE_ADDR'];

        // Check if the IP address is coming through a proxy
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        }


        ActivityLog::create([
            'user_id' => $userId,
            'message' => $message,
            'IP_ADDRESS' => $clientIP
        ]);
    }
}
