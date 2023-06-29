<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataValidation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    private $userModel;
    private $personalDetailsModel;
    private $otpModel;
    private $errorBag;


    public function __construct()
    {
        $this->userModel = env('MODEL_PATH') . 'User';
        $this->personalDetailsModel = env('MODEL_PATH') . 'Web\PersonalDetail';
        $this->otpModel = env('MODEL_PATH') . 'Web\OTP';
        $this->errorBag = [];
    }

    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request)
//    public function store(LoginRequest $request): RedirectResponse
    {

//        Auth::login($user, $remember);
        $required = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $validate = new DataValidation();
        $this->errorBag = $validate->required($required);
        if (count($this->errorBag)) {
            return ActionResponse::error('Please ensure all required fields have been filled.', $this->errorBag);
        } else {
            $request->authenticate();
            $request->session()->regenerate();
            $this->errorBag['locked_out']['status'] = false;
        }

    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
