<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Browsershot\Browsershot;
use PDF2;

class PrintReportsController extends Controller
{
    public function index()
    {
        $data = [
            'siteNames' => ["PORT ELIZABETH", "CONSTANTIABERG", "JOHANNESBURG"],
            'date' => ["2023-03-01T08:54:00.000Z", "2023-08-26T08:54:00.000Z"],
            // Add other data as needed
        ];

        $pdf = PDF2::loadView('reports/index', compact('data'))->setOption('margin-top', 0)->setOption('margin-bottom', 0)->setOption('margin-right', 0)->setOption('margin-left', 0);
        return $pdf->download('test0012' . '.pdf');
    }

    public function test()
    {
        return view('reports/sentiments');
    }
}
