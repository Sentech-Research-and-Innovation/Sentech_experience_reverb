<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\CreateUserRequest;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\Password;



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
        $randomString = Str::random(30);
        $data = $request->validated();
        $user = User::create([
            "name" => $data['first_name'] . '' . $data['last_name'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            'password' => Hash::make($randomString),
            'company_id' =>  $this->company->id
        ]);

        // Mail::to($data['email'])->send(new ResetPasswordEmail($user));

        // $status = Password::sendResetLink(
        //     $request->only('email'),

        // );

        $user->SendCreateUserNotification();

        $user->assignRole($data['role']);

        $message = "Created New user " . $data['first_name'] . " " . $data['last_name'];
        $this->StoreActivity($message);

        return request()->json([], 200);
    }

    public function delete($user_id)
    {
        $user = User::where("id", $user_id)->first();
        User::find($user_id)->delete();

        $message = "Deleted user " .  $user->first_name;
        $this->StoreActivity($message);
        return response()->json([], 200);
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
