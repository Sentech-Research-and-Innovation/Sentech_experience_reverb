<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Inertia\Inertia;

class SentimentAnalysisController extends Controller
{

    public function index(){
        $response =[];
        return Inertia::render('Admin/SentimentAnalysis/Index', compact('response'));
    }
}
