<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Notification;

trait StoreNotificationTrait
{


    public function StoreNotification($from_company_id, $notificationTypeId)
    {

        $data = $this->getMessage($notificationTypeId);
        Notification::create([

            "model_ids" => [

                "from_compay_id" => $from_company_id,
                "to_compay_id" => 1,
                "user_id" => 0

            ],
            "notification_type_id" => $notificationTypeId,
            "message" => $data['message'],
            "link" => $data["link"]
        ]);
    }


    private function getMessage($notificationTypeId)
    {
        $message = "";
        $link = "";
        if ($notificationTypeId == 1) {
            $message = "A new account has been requested";
            $link = "/organizantions/request";
        }
        return ["message" => $message, "link" => $link];
    }
}
