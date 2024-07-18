<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\CreateUserRequest;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;


class UserController extends Controller
{

    protected $company;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->company = auth()->user()->company;
            return $next($request);
        });
    }


    public function create(CreateUserRequest $request)
    {

        $data = $request->validated();
        $user = User::create([
            "name" => $data['first_name'] . '' . $data['last_name'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            'password' => '$2y$10$D2tQCuUfUuYhCbJOSxOtf.45hNeFmjW3hUGzZMBB/CK7UBf9HlaQe',
            'company_id' =>  $this->company->id


        ]);

        $user->assignRole($data['role']);

        $message = "Created New user " . $data['first_name'] . " " . $data['first_name'];
        $this->StoreActivity($message);

        return request()->json([], 200);
    }


    public function sendResetLinkEmail($email)
    {
        $user = User::where('email', $email)->first();


        $token = Str::random(60);
        $user->password_reset_token = $token;
        $user->save();

        // Send the password reset email
        Mail::to($user->email)->send(new ResetPasswordEmail($user));

        //return redirect()->back()->with('success', 'Password reset link sent. Please check your email.');
    }
}
