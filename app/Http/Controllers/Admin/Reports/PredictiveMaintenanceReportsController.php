<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Prediction;


class PredictiveMaintenanceReportsController extends Controller
{
    public function index()
    {
        $predictions = Prediction::orderby('item_id', 'ASC')->get();
        return Inertia::render('Admin/Reports/PredictiveMaintenance', compact('predictions'));
    }
}
