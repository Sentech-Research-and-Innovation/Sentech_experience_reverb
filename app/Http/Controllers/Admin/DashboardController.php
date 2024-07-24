<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Carbon\Carbon;

use App\Models\ActivityLog;


class DashboardController extends Controller
{
    public function index()
    {
        $url = url()->previous();
        $refresh = false;

        preg_match('~[^/]*$~', $url, $matches);

        $result = $matches[0];

        if (!$result) {
            $refresh = true;
        } else if (strpos($result, 'token') !== false) {
            $refresh = true;
        }

        return Inertia::render('Admin/Dashboard', compact('refresh'));
    }

    public function show()
    {
        $filterDate = request()->inputdate;
        $searchText = request()->searchActivity;


        $startDate = isset($filterDate[0]) ? Carbon::parse($filterDate[0]) : null;
        $endDate = isset($filterDate[1]) ? Carbon::parse($filterDate[1]) : null;

        $activities = ActivityLog::with(['user.roles', 'user' => function ($query) use ($searchText) {
            $query->where(function ($innerQuery) use ($searchText) {
                $innerQuery->where('first_name', 'LIKE', "%$searchText%")
                    ->orWhere('last_name', 'LIKE', "%$searchText%");
            });
        }])
            ->when(!is_null($startDate) && !is_null($endDate), function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->where(function ($query) use ($searchText) {
                $query->whereNull('created_at')
                    ->orWhereHas('user', function ($userQuery) use ($searchText) {
                        $userQuery->where('first_name', 'LIKE', "%$searchText%")
                            ->orWhere('last_name', 'LIKE', "%$searchText%");
                    });
            })
            ->orderBy('created_at', 'desc')->paginate(10);

        foreach ($activities as $activity) {
            $dateTimeString = $activity->created_at;

            $carbonDateTime = Carbon::parse($dateTimeString);

            $date = $carbonDateTime->format('d F Y');
            $time = $carbonDateTime->toTimeString();

            $activity->setAttribute('date', $date);
            $activity->setAttribute('time', $time);
        }

        return response()->json($activities, 200);
    }
}
