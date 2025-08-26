<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */

    private $errorBag;
    public function __construct()
    {
        $this->errorBag = [];
    }

    public function create(): Response
    {
        return Inertia::render('Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validateEmail = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );

        if ($validateEmail->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Email address is required',
            ], 422);
        }

        $status = Password::sendResetLink(
            $request->only('email'),

        );


        // $status = Password::sendResetLink(
        //     $request->only('email'),

        // );

        if ($status == "passwords.throttled" || $status == "passwords.sent") {
            return response()->json([
                'status' => true,
                'message' => 'link sent',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }
    }



    // public function reset(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:8|confirmed',
    //         'token' => 'required'
    //     ]);


    //     $status = Password::reset(
    //         $request->only('email', 'password', 'token'),
    //         function ($user) use ($request) {
    //             $user->forceFill([
    //                 'password' => Hash::make($request->password),
    //                 'remember_token' => Str::random(60),
    //             ])->save();

    //             $user->tokens()->delete();
    //             $geuser = User::where('email', $request->email)->first();

    //             if ($geuser) {
    //                 $geuser->company()->update(['approved' => 2]);
    //             }

    //             event(new PasswordReset($user));
    //         }
    //     );


    //     if ($status == Password::PASSWORD_RESET) {
    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Password reset successful',
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Password reset expired, send another password reset request',
    //         ], 404);
    //     }
    // }

   
    public function reset(Request $request)
    {
        Log::info('Password reset request received', $request->all());
    
        // Validate required fields
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'token' => 'required'
        ]);
    
        Log::info('Validation passed for password reset', [
            'email' => $request->email,
            'token' => $request->token,
        ]);
    
        // Attempt to reset the password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                Log::info('Password::reset closure triggered', ['user_id' => $user->id]);
    
                // Update password & remember token
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                Log::info('Password updated for user', ['user_id' => $user->id]);
    
                // Delete all personal access tokens
                $user->tokens()->delete();
                Log::info('Deleted personal access tokens', ['user_id' => $user->id]);
    
                // Update company approval status
                $geuser = User::where('email', $request->email)->first();
                if ($geuser) {
                    $geuser->company()->update(['approved' => 2]);
                    Log::info('Company approved status updated', ['company_id' => $geuser->company->id ?? null]);
                }
    
                // Fire event
                event(new PasswordReset($user));
                Log::info('PasswordReset event fired', ['user_id' => $user->id]);
            }
        );
    
        Log::info('Password reset status result', ['status' => $status]);
    
        // Handle outcome
        if ($status === Password::PASSWORD_RESET) {
            Log::info('Password reset successful', ['email' => $request->email]);
            return response()->json([
                'status' => true,
                'message' => 'Password reset successful',
            ], 200);
        }
    
        Log::warning('Password reset failed', [
            'email' => $request->email,
            'reason' => $status,
        ]);
    
        return response()->json([
            'status' => false,
            'message' => __($status), // shows actual failure reason
        ], 404);
    }

    
}
