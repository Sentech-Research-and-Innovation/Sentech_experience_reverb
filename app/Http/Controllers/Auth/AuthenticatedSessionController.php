<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataValidation;
use App\Models\System\Wirepick;
use App\Models\User;
use App\Models\Web\LoginAttempt;
use App\Models\Web\OTP;
use App\Models\Web\PersonalDetail;
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
            return ActionResponse::error('Please ensure all required fields have been filled.', $this->errorBag, false);
        } else {

            $user = null;
            $getUser = PersonalDetail::where('id_number', $request->email)->first();
            if(!is_null($getUser)){
                $loginAttempt = LoginAttempt::where('user_id',$getUser->user_id)->where('status',0)->first();

                if(!is_null($loginAttempt)){
                    if($loginAttempt->count >= 3){
                        return ActionResponse::error('You have tried to authenticate your account 3 times, your account has been locked please contact Izwe customer support to proceed.', ['You have tried to authenticate your account 3 times, your account has been locked please contact Izwe customer support to proceed.'], false);
                    }else{
                        $attemptReq=[
                            'user_id'=>$getUser->user_id,
                            'count'=> $loginAttempt->count+1,
                            'status'=>0
                        ];
                        CRUD::update($attemptReq,config('system_config.models.login_attempt'),'login_attempts','id',$loginAttempt->id);
                    }

                }else{
                    $attemptReq=[
                        'user_id'=>$getUser->user_id,
                        'count'=>1,
                        'status'=>0
                    ];
                    CRUD::create($attemptReq,config('system_config.models.login_attempt'),'login_attempts');
                }
            }

            $this->errorBag['locked_out'] = false;
            if (!is_null($getUser)) {
                $user = User::find($getUser->user_id);
                if(is_null($user)){
                    $this->errorBag['email'] = 'You have entered an incorrect ID number please ensure you already have an account with us before you can proceed.';
                    return ActionResponse::error('These credentials do not match our records.', $this->errorBag, false);
                }
           if(!is_null($user)){
               if ($user->locked == 1) {
                   \Session::put('_uid', Crypt::encrypt($user->id));
                   $this->errorBag['locked_out'] = true;
                   $this->errorBag['email'] = 'Your profile has been locked for a period of 1 hour. An OTP has been sent to you , if not received please send again in 1 hour. Please enter OTP to gain access to your account, once validated you will be redirected to the login screen.';
                   $otp = OTP::where('user_id', $user->id)->first();
                   if (!is_null($otp)) {
                       $dateNow = date('Y-m-d H:m:s');
                       $date1 = new \DateTime($otp->updated_at);
                       $date2 = new \DateTime($dateNow);

                       $diff = $date1->diff($date2);

                       if ($diff->h >= 1) {
//                            $otpCode = rand(1000, 9999);
                           $otpCode = 1234;
                           $message = 'Your OTP is ' . $otpCode;
                           $country_code = '';
                           $wirepick = new Wirepick();
                           $sms = $wirepick->sms($user->personalDetails->mobile_number, $message, $country_code);
                           if ($sms['success']) {
                               $sent = 0;
                               if (isset($sms['data']['sms']['status'])) {
                                   if ($sms['data']['sms']['status'] === 'SND') {
                                       $sent = 1;
                                   }
                               }
                               $_otp_arr_update = [
                                   'otp' => $otpCode,
                                   'sms_sent' => $sent,
                                   'send_again' => 0,
                                   'used' => 0,
                                   'expired' => 0,
                                   'sms_response' => json_encode($sms['data'])
                               ];
                               CRUD::update($_otp_arr_update, config('system_config.models.otp'), 'otp', 'id', $otp->id);
                               \Session::put('_uid', Crypt::encrypt($user->id));
                               $this->errorBag['email'] = 'We have sent you an OTP to unlock your account, please enter OTP, if you do not have access to the number you used to register, please contact customer support.';
                               return ActionResponse::error('Profile locked out.', $this->errorBag, false);
//                                return Inertia::render('Auth/Otp', compact('locked_out'));
                           }

                       } else {
                           return ActionResponse::error('Profile locked out.', $this->errorBag, false);

                       }

                   }
               }
               else {
                   $validated = OTP::where('user_id', $user->id)->where('admin_override', 0)->first();

                   if (!is_null($validated)) {
                       if ($validated->send_again > 3) {
                           return ActionResponse::error('Your account has tried to authenticate for more than 3 times, Please contact customer services for assistance.', $this->errorBag, false);
                       } else {
                           if ($validated->validated == 1) {

                               $request['email'] = $user->email;
                               $request->authenticate();
                               $request->session()->regenerate();
                               $this->errorBag['locked_out']['status'] = false;

                               if(!is_null($loginAttempt)){
                               $attemptReq=[
                                   'user_id'=>$getUser->user_id,
                                   'status'=>1
                               ];
                               CRUD::update($attemptReq,config('system_config.models.login_attempt'),'login_attempts','id',$loginAttempt->id);

                           }
                               return ActionResponse::success('Login successful.', $this->errorBag, true);
                           } else {
                               $this->errorBag['locked_out'] = true;
                               $this->errorBag['email'] = 'Sorry you have not verified your account, an OTP has been sent to your mobile number, please verify and login.';
//                                $otp = rand(1000, 9999);
                               $otp =1234;
                               $otp_Request = [
                                   'otp' => $otp,
                               ];
                               \Session::put('_uid', Crypt::encrypt($user->id));
                               CRUD::update($otp_Request, config('system_config.models.otp'), 'otp', 'id', $validated->id);
                               return ActionResponse::error('Sorry you have not verified your account, an OTP has been sent to your mobile number, please verify and login.', $this->errorBag, false);
                           }

                       }
                   } else {
                       $this->errorBag['email'] = 'You have entered an incorrect ID number please ensure you already have an account with us before you can proceed.';
                       return ActionResponse::error('These credentials do not match our records.', $this->errorBag, false);
                   }
               }
           }
            } else {
                $this->errorBag['email'] = 'You have entered an incorrect ID number please ensure you already have an account with us before you can proceed.';

                return ActionResponse::error('These credentials do not match our records.', $this->errorBag, false);
            }

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
