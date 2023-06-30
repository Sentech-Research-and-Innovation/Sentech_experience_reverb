<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\System\SentimentAnalysis;
use Inertia\Inertia;

class SentimentAnalysisController extends Controller
{

    public function index()
    {

        $analysis = (new SentimentAnalysis)->analysis(['size' => 100]);
        $data = ['analysis' => $analysis];
//        dd($data);
        return Inertia::render('Admin/SentimentAnalysis/Index', compact('data'));
    }
}
