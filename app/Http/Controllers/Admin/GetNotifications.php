<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Carbon;

class GetNotifications extends Controller
{
    public function index()
    {
        $notifications = Notification::whereJsonContains('model_ids', ['to_compay_id' => auth()->user()->company->id])
            ->with('notificationType')
            ->get();

        $notifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->message,
                'link' => $notification->link,
                'active' => $notification->active,
                'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                'notification_type' => $notification->notificationType->name,
            ];
        });

        return $notifications;
    }
}
