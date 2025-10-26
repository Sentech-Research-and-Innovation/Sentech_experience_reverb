<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\Notification;

trait StoreNotificationTrait
{


    public function StoreNotification($from_company_id, $notificationTypeId,  $sender = null)
    {

        $data = $this->getMessage($notificationTypeId);
        Notification::create([

            "model_ids" => [

                "from_compay_id" => $from_company_id,
                "to_compay_id" => 1,
                "user_id" =>  $sender?->id ?? 0,

            ],
            "notification_type_id" => $notificationTypeId,
            "message" => $data['message'],
            "link" => $data["link"]
        ]);
    }


    private function getMessage($notificationTypeId, $sender = null)
    {
        $message = "";
        $link = "";
        if ($notificationTypeId == 1) {
            $message = "A new account has been requested";
            $link = "/organizantions/request";
        }elseif ($notificationTypeId == 2  && $sender) {
        $message = "{$sender->first_name} {$sender->last_name} sent you a text message";
        $link = "/profile/{$sender->id}";
    }
        return ["message" => $message, "link" => $link];
    }
}
