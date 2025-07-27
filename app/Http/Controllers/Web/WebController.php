<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;

class WebController extends Controller
{

    public function contactus()
    {
        return Inertia::render('Web/contactus');
    }
    public function feedback()
    {
        $data = request()->validate([
            'email' => ['required', 'email'],
            'name' => ['required'],
            'comment' => ['required'],
        ]);

        $adminEmail = config('app.admin_email');
        Mail::to($adminEmail)
	->send(new ResetPasswordEmail($data));
        return request();
    }
}
