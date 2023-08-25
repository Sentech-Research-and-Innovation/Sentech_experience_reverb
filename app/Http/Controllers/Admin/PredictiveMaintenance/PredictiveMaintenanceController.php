<?php


namespace App\Http\Controllers\Admin\PredictiveMaintenance;

use Illuminate\Support\Facades\Http;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\User;
use App\Models\UserModels\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DateTime;
use Illuminate\Support\Facades\DB;

class PredictiveMaintenanceController extends Controller
{
    public function nationalSites()
    {
        $sites = DB::table('national_site')->get();

        return Inertia::render('Admin/PredictiveMaintenance/nationalSites', compact('sites'));
    }

    public function predictions()
    {
        $predictions = DB::table('prediction')->get();

        return Inertia::render('Admin/PredictiveMaintenance/predictions', compact('predictions'));
    }


    public function deviceConfig()
    {
        $devices = DB::table('device_config')->limit(3)->get();

        return Inertia::render('Admin/PredictiveMaintenance/deviceConfig', compact('devices'));
    }

    public function alarmList()
    {
        $alarms = DB::table('alarm_list')->limit(10)->get();

        return Inertia::render('Admin/PredictiveMaintenance/alarmList', compact('alarms'));
    }
}
