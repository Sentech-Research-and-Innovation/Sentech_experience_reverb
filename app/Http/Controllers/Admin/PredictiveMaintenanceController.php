<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PredictiveMaintenanceController extends Controller
{

    public function index(){

        $response =[];
        return Inertia::render('Admin/PredictiveMaintenance/Index', compact('response'));

    }
}
