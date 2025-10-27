<?php

// namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Notification;
// use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\Log;

// class GetNotifications extends Controller
// {
//     public function index()
//     {
//         Log::info('[GetNotifications] Reached controller', [
//             'user_id' => auth()->id(),
//             'company_id' => auth()->user()->company->id ?? null,
//         ]);

//         $notifications = Notification::whereJsonContains('model_ids', [
//                 'to_compay_id' => auth()->user()->company->id
//             ])
//             ->where('active', 1)
//             ->with('notificationType')
//             ->orderBy('id', 'DESC')
//             ->get();

//         Log::info('[GetNotifications] Notifications fetched', [
//             'count' => $notifications->count(),
//         ]);

//         $notifications = $notifications->map(function ($notification) {
//             return [
//                 'id' => $notification->id,
//                 'message' => $notification->message,
//                 'link' => $notification->link,
//                 'active' => $notification->active,
//                 'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
//                 'notification_type' => $notification->notificationType->name ?? 'N/A',
//             ];
//         });

//         Log::info('[GetNotifications] Notifications transformed', [
//             'data_sample' => $notifications->take(2), // log only first 2 to avoid flooding
//         ]);

//         return response()->json($notifications);
//     }
// }

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class GetNotifications extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company->id ?? null;

        Log::info('[GetNotifications] Reached controller', [
            'user_id' => auth()->id(),
            'company_id' => $companyId,
        ]);

        if (!$companyId) {
            Log::warning('[GetNotifications] No company found for user', [
                'user_id' => auth()->id()
            ]);
            return response()->json([]);
        }

        $notifications = Notification::whereJsonContains('model_ids', [
                'to_company_id' => $companyId
            ])
            ->where('active', 1)
            ->with('notificationType')
            ->orderBy('id', 'DESC')
            ->get();

        Log::info('[GetNotifications] Notifications fetched', [
            'count' => $notifications->count(),
        ]);

        $notifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'message' => $notification->message,
                'link' => $notification->link,
                'active' => $notification->active,
                'created_at' => Carbon::parse($notification->created_at)->diffForHumans(),
                'notification_type' => $notification->notificationType->name ?? 'N/A',
            ];
        });

        Log::info('[GetNotifications] Notifications transformed', [
            'data_sample' => $notifications->take(2),
        ]);

        return response()->json($notifications);
    }
}
