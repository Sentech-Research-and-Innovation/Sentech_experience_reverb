<?php


namespace App\Http\Controllers\Admin\PredictiveMaintenance;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

use App\Models\Prediction;

class PredictiveMaintenanceController extends Controller
{

    public function index()
    {

        $predictions = Prediction::all();

        return Inertia::render('Admin/PredictiveMaintenance/Index', compact('predictions'));
    }
}
