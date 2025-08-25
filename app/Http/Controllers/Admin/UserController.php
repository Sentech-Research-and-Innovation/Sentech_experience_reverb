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
use App\Notifications\CreateUserNotification;

use Illuminate\Support\Facades\Log;



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

        $status = Password::sendResetLink(
            $request->only('email'),

        );
        
        $user->assignRole($data['role']);

        $message = "Created New user " . $data['first_name'] . " " . $data['last_name'];
        $this->StoreActivity($message);

        return request()->json([], 200);
    }

        //  public function resendEmail($user_id, Request $request)
        // {
        //     // Step 1: Find the user
        //     $user = User::findOrFail($user_id);
            
        //     // Step 2: Send the password reset link to the user's email
        //     $status = Password::sendResetLink(['email' => $user->email]);
            
        //     // Step 3: Log the activity
        //     $message = "Resent signup email to " . $user->first_name . " " . $user->last_name;
        //     $this->StoreActivity($message);
            
        //     // Step 4: Return response
        //     return response()->json(['message' => 'Signup link sent successfully.'], 200);
        // }
    public function resendEmail($user_id, Request $request)
    {
        Log::info('resendEmail called', [
            'user_id' => $user_id,
            'request_data' => $request->all(),
        ]);
    
        try {
            // Step 1: Find the user
            $user = User::findOrFail($user_id);
            Log::info('User found for resendEmail', [
                'id' => $user->id,
                'email' => $user->email,
            ]);
    
            // Step 2: Send the password reset link
            $status = Password::sendResetLink(['email' => $user->email]);
            Log::info('Password reset link status', ['status' => $status]);
    
            // Step 3: Log the activity
            $message = "Resent signup email to " . $user->first_name . " " . $user->last_name;
            $this->StoreActivity($message);
            Log::info('Activity stored for user', ['message' => $message]);
    
            // Step 4: Return response
            return response()->json(['message' => 'Signup link sent successfully.'], 200);
        } catch (\Exception $e) {
            Log::error('Error in resendEmail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['error' => 'Failed to resend email'], 500);
        }
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

        // return redirect()->back()->with('success', 'Password reset link sent. Please check your email.');
    }
}
