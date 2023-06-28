<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataValidation;
use App\Models\System\Locale;
use App\Models\System\RequestEncrypt;
use App\Models\System\Wirepick;
use App\Models\User;
use App\Models\Web\Country;
use App\Models\Web\IzweProduct;
use App\Models\Web\MipCustomer;
use App\Models\Web\OTP;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\Builder;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    private $userModel;
    private $personalDetailsModel;
    private $otpModel;
    private $ipData;
    private $errorBag;
    private $mipModel;

    public function __construct()
    {

        $this->userModel = env('MODEL_PATH') . 'User';
        $this->personalDetailsModel = env('MODEL_PATH') . 'Web\PersonalDetail';
        $this->otpModel = env('MODEL_PATH') . 'Web\OTP';
        $this->ipData = env('MODEL_PATH') . 'Web\IPData';
        $this->mipModel = env('MODEL_PATH') . 'Web\MIPData';
        $this->errorBag = [];
    }

    public function create()
    {


        $id = null;
        $otp = null;

        if (!is_null(Session::get('_uid'))) {
            $otp = Session::get('_otp');
            $id = Crypt::decrypt(Session::get('_uid'));

            if (!is_null($id)) {
                $validateOtp = OTP::where('user_id',$id)->first();
                if(is_null($validateOtp)){
                    return redirect(RouteServiceProvider::LOGIN);
                }
            }
            if ($otp) {
                return redirect(RouteServiceProvider::OTP);
            }

        }
        if (!is_null($id)) {
            return redirect(RouteServiceProvider::HOME);
        }

        $countries = Country::all();
        $country_id = config('system_config.'.env('APP_COUNTRY').'.code');
        $country_code =Country::find($country_id);
        if(!is_null($country_code)){
            $country_code = $country_code->dial_code;
        }
        return Inertia::render('Auth/Register',compact('countries'))->with('country_code',$country_code);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {


        $location = Locale::location();
        $required = [
            'id_number' => $request->id_number,
            'firstname' => $request->firstname,
            'password' => $request->password,
            'lastname' => $request->lastname,
            'mobile_number' => $request->mobile_number,
             'dob' => $request->dob,
            'user_email' => $request->user_email,
            'country_code' => $request->country_code,
            'password_confirmation' => $request->password_confirmation,
        ];
        $validate = new DataValidation();
        $this->errorBag = $validate->required($required);
        if (count($this->errorBag)) {
            return ActionResponse::error('Please ensure all required fields have been filled.', $this->errorBag, false);
        }


        $userData = $request->all();
        $userData['country'] = $location->geoplugin_countryCode;
        $userData['email'] = CRUD::generateEmail($request->id_number, $location->geoplugin_countryCode);
        $ipJson = json_encode($location);
//        $mipResponse =$validate->mipValidation('izwe_kenya_customers',$request->id_number,$request->mobile_number);

        $age = DataValidation::age($request->dob);
        if ($age !== false) {
            $this->errorBag['dob'] = $age;
        }

        $idNumber = DataValidation::idNumber($userData['email']);
        if ($idNumber !== false) {
            $this->errorBag['id_number'] = $idNumber;
        }

        $mobileNumber = DataValidation::mobileNumber($request->mobile_number, $userData['country']);
        if ($mobileNumber !== false) {
            $this->errorBag['mobile_number'] = $mobileNumber;
        }
        $mobileNumber = DataValidation::mobileNumberExists($request->mobile_number);
        if ($mobileNumber !== false) {
            $this->errorBag['mobile_number_exists'] = $mobileNumber;
        }

        $idNumberLength = DataValidation::idNumberLength($request->id_number, $userData['country']);
        if ($idNumberLength !== false) {
            $this->errorBag['id_number_length'] = $idNumberLength;
        }


        if (count($this->errorBag)) {
            return ActionResponse::error('An error occurred please check that all fields are correct', $this->errorBag, false);
        } else {

            $user = CRUD::create(RequestEncrypt::encrypt($userData), config('system_config.models.user'), 'users');

            if ($user['success']) {

                Session::put('_uid', Crypt::encrypt($user['data']->id));
                Session::put('_otp', true);
//                $mipData = [
//                    'mip_customer_data'=>json_encode($mipResponse),
//                    'internal_system_csv_check'=>json_encode($mipResponse),
//                    'user_id'=>$user['data']->id
//                ];
//
//                if(count($mipResponse)){
//                    CRUD::create(RequestEncrypt::encrypt($mipData),config('system_config.models.mip'),'mip_customer_data');
//                }

                $personalDetailsData = $request->all();
                $personalDetailsData['user_id'] = $user['data']->id;
                $personalDetailsData['p_country_id'] = Country::where('dial_code',$request->country_code)->first();
                if(is_null($personalDetailsData['p_country_id'])){
                    $personalDetailsData['p_country_id'] = 112;
                }else{
                    $personalDetailsData['p_country_id'] =  $personalDetailsData['p_country_id']->id;
                }
                $ipData = [
                    'user_id' => $user['data']->id,
                    'ip_data' => $ipJson
                ];
                event(new Registered($user['data']));

                CRUD::create(RequestEncrypt::encrypt($ipData), config('system_config.models.ip'), 'ip_data');
                CRUD::create(RequestEncrypt::encrypt($personalDetailsData), config('system_config.models.personal_details'), 'personal_details');
                $otp = 1234;

//                $message = 'Your OTP is ' . $otp;
//                $wirepick = new Wirepick();
//                $mobileNumber = $validate->numberFormat($request->mobile_number,10);
//                if($mobileNumber!==false){
//                    if(strlen($mobileNumber)==9){
//                        $mobileNumber = $request->country_code.$mobileNumber;
//                    }
//                }
//
//                $sms = $wirepick->sms($mobileNumber, $message);
                $sms['success'] = true;
                $sms['data'] = json_decode('{"sms":{"msgid":"h249391779","phone":"254716119279","status":"SND","recd_time":"2023-02-08 12:52:54"}}');
                if ($sms['success']) {
                    $sent = 1;
//                    $sent = 0;
//                    if (isset($sms['data']['sms']['status'])) {
//                        if ($sms['data']['sms']['status'] === 'ACT') {
//                            $sent = 1;
//                        }
//                    }
                    $_otp_arr = [
                        'user_id' => Crypt::decrypt(Session::get('_uid')),
                        'otp' => $otp,
                        'used' => 0,
                        'expired' => 0,
                        'sms_response' => json_encode($sms['data'])
                    ];

                    $_new_otp = CRUD::create($_otp_arr, config('system_config.models.otp'), 'otp');

                    if ($_new_otp['success']) {
                        $validate_otp = OTP::where('otp', $otp)->where('user_id', Crypt::decrypt(Session::get('_uid')))->where('used', 0)->where('expired', 0)->first();
                        if (is_null($validate_otp)) {
                            $_otp_arr_update = [
                                'user_id' => Crypt::decrypt(Session::get('_uid')),
                                'sms_sent' => $sent
                            ];
                            CRUD::update($_otp_arr_update, config('system_config.models.otp'), 'otp', 'id', $validate_otp->id);
                        }

                    }
                } else {
                    $validate_otp = OTP::where('otp', $otp)->where('user_id', Crypt::decrypt(Session::get('_uid')))->where('used', 0)->where('expired', 0)->first();
                    if (is_null($validate_otp)) {
                        $_otp_arr_update = [
                            'user_id' => Crypt::decrypt(Session::get('_uid')),
                            'sms_sent' => 0,
                            'sms_response' => json_encode($sms['data'])
                        ];
                        CRUD::update($_otp_arr_update, config('system_config.models.otp'), 'otp', 'id', $validate_otp->id);
                    }
                    $this->errorBag['sms'] = $sms['data'];
                }


                return ActionResponse::success('You have been successfully registered', $this->errorBag, true);

            }

        }

    }

    public function otp()
    {


        $id = Session::get('_uid');
        if (!is_null($id)) {
            $id = Crypt::decrypt(Session::get('_uid'));
        }
        if (is_null($id)) {
            return redirect(RouteServiceProvider::REGISTER);
        }

        $otp = OTP::where('user_id', $id)->where('validated', 1)->first();
        if (!is_null($otp)) {
            return redirect(RouteServiceProvider::LOGIN);
        }

        $locked_out['status'] = false;
        return Inertia::render('Auth/Otp', compact('locked_out'));
    }

    public function otpStore(Request $request)
    {

        $response = ActionResponse::error('You have entered an incorrect OTP.', [], false);
        if (isset($request->locked_out)) {
            if ($request->locked_out) {
                $locked_out = [];
                $locked_out['status'] = true;
                $locked_out['message'] = $request->message;
                return Inertia::render('Auth/Otp', compact('locked_out'));
            }
        }
//        RedirectResponse
        $remember = true;
        $user = User::find(Crypt::decrypt(Session::get('_uid')));

        $required = [
            'Otp' => $request->otp,
        ];
        $validate = new DataValidation();
        $this->errorBag = $validate->required($required);


        $validate_OTP = OTP::where('otp_type', 1)->where('user_id', $user->id)->first();

        if (!is_null($validate_OTP)) {

            $used = $used = $validate_OTP->used + 1;;
            $otp_Request = [
                'used' => $used
            ];
            $smsResponse = json_decode($validate_OTP->sms_response);
            CRUD::update($otp_Request, config('system_config.models.otp'), 'otp', 'id', $validate_OTP->id);


            if ($validate_OTP->otp == $request->otp) {
                if ($validate_OTP->send_again > 3 || $validate_OTP->used > 3) {
                    $dateNow = date('Y-m-d H:m:s');
                    $date1 = new \DateTime($validate_OTP->updated_at);
                    $date2 = new \DateTime($dateNow);

                    $diff = $date1->diff($date2);

                    if ($diff->h >= 1) {
//                        $otpCode = rand(1000, 9999);
                        $otpCode = 1234;
                        $message = 'Your OTP is ' . $otpCode;
                        $country_code = '';
                        $wirepick = new Wirepick();
                        $sms = $wirepick->sms($user->personalDetails->mobile_number, $message, $country_code);
                        if ($sms['success']) {
                            $sent = 0;
                            $x =true;
                            if (isset($sms['data']['sms']['status'])) {
//                                if ($sms['data']['sms']['status'] === 'SND') {
                                if ($x) {
//                                if ($sms['data']['sms']['status'] === 'ACT') {
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
                            CRUD::update($_otp_arr_update, config('system_config.models.otp'), 'otp', 'id', $validate_OTP->id);


                        }
                    }
                    $response = ActionResponse::error('You have entered an incorrect OTP for more than 3 times, your profile has been locked for a period of 1 hour.', [], false);
                } else {

//                    $smsResponse->sms->status === 'RTN'
                    $x = true;
                    if ($x=true) {
//                if($smsResponse->sms->status==='ACT'){
                        if (strtotime($validate_OTP->updated_at) > strtotime("-30 minutes")) {
                            if ($validate_OTP->send_again <= 3) {
                                $otp_Request = [
                                    'validated' => 1,
                                ];
                                $user_request = [
                                    'email_verified_at' => date('Y-m-d H:m:s'),
                                ];
                                CRUD::update($otp_Request, config('system_config.models.otp'), 'otp', 'id', $validate_OTP->id);
                                CRUD::update($user_request, config('system_config.models.user'), 'users', 'id', Crypt::decrypt(Session::get('_uid')));

                                if ($request->source === 'register') {
                                    Auth::login($user, $remember);
                                } else {
                                    $user->locked = 0;
                                    $user->save();
                                }
                                if ($validate_OTP->send_again > 3 || $validate_OTP->used > 3) {
                                    $response = ActionResponse::error('Your account has tried to authenticate for more than 3 times, Please contact customer services for assistance.', [], false);
                                } else {
                                    $product= (new  IzweProduct)->productSlug();
                                    $response = ActionResponse::success('Otp validated successfully.',$product , true);
                                    Session::put('_otp', false);
                                }

                            } else {
                                $response = ActionResponse::error('This pin has been used more than 3 times, Please contact customer services for assistance.', [], false);
                            }
                        }

                    } else {
                        $response = ActionResponse::error('Your account has been locked please contact customer support to proceed.', [], false);
                    }
                }
            } else {
                $otp = OTP::where('otp_type', 1)->where('user_id', $user->id)->first();
                if ($otp->used == 1)
                    $response = ActionResponse::error('You have entered the incorrect OTP, you have two (2) more attempts to enter the correct OTP.  After three (3) attempts your profile will be locked for a period of one (1) hour.', [], false);

                if ($otp->used == 2)
                    $response = ActionResponse::error('You have entered the incorrect OTP, you have 1 (1) more attempt to enter the correct OTP.  After three (3) attempts your profile will be locked for a period of one (1) hour.', [], false);

                if ($otp->used >= 3) {
                    $response = ActionResponse::error('You have entered the incorrect OTP more than three (3) times.  Your profile has been locked for a period of one (1) hour.', [], false);
                    $user->locked = 1;
                    $user->save();
                }

            }
        }

        return $response;

    }

    public function sendAgain(Request $request)
    {
        $id = Session::get('_uid');
        if (!is_null($id)) {
            $id = Crypt::decrypt($id);
        }
        $otp = OTP::where('user_id', $id)->first();

        $validateUser = User::find($id);
        if(!is_null($validateUser)){
            if($validateUser->locked==1){
                return ActionResponse::error('You have entered an incorrect OTP for more than 3 times, your profile has been locked for a period of 1 hour.', [], false);
            }
        }

        $response = ActionResponse::error('The account you are trying to verify does not exist, please confirm registration or contact customer support for further assistance.', [], false);
        if (!is_null($otp)) {
            if ($otp->send_again <= 3) {
                $i = $otp->send_again + 1;
                if ($otp->send_again == 3) {
                    $i = $otp->send_again;
                }
                $code = 1234;
//                $code = rand(1000, 9999);

                $otp_Request =
                    [
                        'send_again' => $i,
                        'otp' => $code
                    ];
                $update = CRUD::update($otp_Request, config('system_config.models.otp'), 'otp', 'id', $otp->id);
                if($otp->send_again >=3){
                    $response = ActionResponse::error('You have tried to send the OTP more than 3 times, please try again in an hour.', [], false);
                }else{
                    if ($update['success']) {
                        $response = ActionResponse::success('An OTP has been sent to you, please enter this pin to validate your account.', [], true);
                    }
                }

            } else {
                $response = ActionResponse::error('Your account has tried to authenticate for more than 3 times, Please contact customer services for assistance.', [], false);
            }

        }
        return $response;
    }


}
