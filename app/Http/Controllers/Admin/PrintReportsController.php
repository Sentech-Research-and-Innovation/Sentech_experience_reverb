<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Spatie\Browsershot\Browsershot;
use VerumConsilium\Browsershot\Facades\PDF;

class PrintReportsController extends Controller
{
    public function index()
    {
        return $pdfStoredPath = PDF::loadUrl(route('services', ['encrypt' => bcrypt(config('app.key'))]))
            ->save('pdfs.pdf');

        //    / Browsershot::url()->pdf();
    }
}
