<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Notification;

trait StoreNotificationTrait
{


    public function StoreNotification($request, $notificationTypeId)
    {
        $message = $this->getMessage($notificationTypeId);
        Notification::create([
            "company_id" =>  auth()->user()->company->id ?? 1,
            "notification_type_id" => $notificationTypeId,
            "message" => $message
        ]);
    }


    private function getMessage($notificationTypeId)
    {
        $message = "";
        if ($notificationTypeId == 1) {
            $message = "A new account has been requested";
        }
        return $message;
    }
}
