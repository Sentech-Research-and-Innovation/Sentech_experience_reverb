<?php


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\DataValidation;
use App\Models\System\DRAHandler;
use App\Models\System\MIP;
use App\Models\System\Profile;
use App\Models\System\SmileIdentity;
use App\Models\Web\DRAData;
use App\Models\Web\IzweApplication;
use App\Models\Web\IzweProduct;
use App\Models\Web\MpesaStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class LoanController extends Controller
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function offerResponse()
    {

    }

    public function offer(Request $request)
    {

//        dd($request->all());
        if ($request['offerResponse'] === 'accept') {
            $grantCheck = (new MIP())->getCustomerGrantData();;
            if (is_null($grantCheck)) {
                (new MIP)->createCustomer($request);
            }
        }
        if ($request['offerResponse'] === 'decline') {
            $declineCheck = (new MIP)->getCustomerDeclineData();
            if (is_null($declineCheck)) {
                (new MIP)->createCustomer($request);
            }

        }
        if ($request['offerResponse'] === 'new_account') {
            $declineCheck = (new MIP)->getCustomerDeclineData();
            if (!is_null($declineCheck)) {
                (new MIP)->createCustomer($request);
            }
        }
        if ($request['offerResponse'] === 'apply_again') {
            $declineCheck = (new MIP)->getCustomerDeclineData();
            if (is_null($declineCheck)) {
                (new MIP)->createCustomer($request);
            } else {
                if (isset($declineCheck->pcStatus)) {
                    if ($declineCheck->pcStatus !== 'Approved') {
                        (new MIP)->createCustomer($request);
                    }

                }
            }

        }

//        return ActionResponse::success('',['success'=>true],true);
//        return (new MIP())->mipLocalData();
    }

    public function getOffer(Request $request)
    {


//        self::webhook();

//        dd((new \App\Models\System\SpinMobile)->authenticate());
        $response = [];
        $response['fail'] = false;


        $mip = (new MIP())->validateMIPDataStatus();
        if (is_null($mip)) {
            $customerData = (new MIP())->getCustomerCreateData();
            $response['fail'] = $customerData;
            $response['create_customer'] = false;
            $response['customer_grant'] = false;
            $response['customer_decline'] = false;
        }


        if (isset($mip['errorBag']['customer_create_mip_data'])) {
            $response = (new MIP())->mipLocalData();
        }
//        dd($response);

        if (isset($request['_oid'])) {
            if ($request['_oid'] === 'view-offer') {

            }
            if ($request['_oid'] === 'track-payments') {

                return Inertia::render('LoanOffer/RepaymentSchedule', compact('response'));
            }
            if ($request['_oid'] === 'offer-cancel') {
                $request['offerResponse'] = 'decline';
                $declineCheck = (new MIP)->getCustomerDeclineData();
                if (is_null($declineCheck)) {
                    (new MIP)->createCustomer($request);
                }
                return Inertia::render('LoanOffer/OfferCancel', compact('response'));
            }
            if ($request['_oid'] === 'offer-declined') {
                $request['offerResponse'] = 'decline';
                $declineCheck = (new MIP)->getCustomerDeclineData();
                if (is_null($declineCheck)) {
                    (new MIP)->createCustomer($request);
                }

                return Inertia::render('LoanOffer/LoanDeclined', compact('response'));
            }

            if ($request['_oid'] === 'offer-accepted') {
                $request['offerResponse'] = 'accept';
                $grantCheck = (new MIP())->getCustomerGrantData();;
                if (is_null($grantCheck)) {
                    (new MIP)->createCustomer($request);
                }

//                dd($response);
//                return Inertia::render('LoanOffer/LoanAccepted', compact('response'));
                return Inertia::render('LoanOffer/UploadResource', compact('response'));
            }


        }
        $response['mpesa'] = false;
        $validateFileStatus = MpesaStatement::where('user_id', Auth::user()->id)->latest()->first();
        if (!is_null($validateFileStatus)) {
            if ($validateFileStatus->file_status === 'awaiting_mpesa_response') {
//                must be false after testi nting
                $response['mpesa'] = true;
            } else {
                $response['mpesa'] = true;
            }
        }
//        $response['mpesa'] = true;
//        dd($response);
        return Inertia::render('LoanOffer/Index', compact('response'));
    }

    public function getRepaymentSchedule()
    {
        return Inertia::render('LoanOffer/RepaymentSchedule');
    }

    public function init(Request $request)
    {
        $app_arr = [
            'application_status' => 'default',
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
        ];
        IzweApplication::validateIzweApplication($app_arr);
        return ActionResponse::success('', [], true);
    }

    public function apply(Request $request)
    {


        $product = IzweProduct::where('product_country_id', Auth::user()->personalDetails->p_country_id)->first();
        $app_arr = [
            'application_status' => 'default',
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
        ];
        IzweApplication::validateIzweApplication($app_arr);
        if (isset($request['link_status'])) {
            if ($request['link_status'] === 'take_the_test') {
                $linkStatus = 'take_the_test';
                $draData = [
                    'user_id' => Auth::user()->id,
                    'link_status' => $linkStatus,
                ];
                DRAData::validateDraData($draData);
                (new DRAHandler)->draGetResults();
                $dra = (new DRAHandler)->draGet();
                $dra['product'] = $product;
                return ActionResponse::success('Success', $dra, true);
            }
        }

        $user = Auth::user();
        $profile = (new Profile)->progress();
        if ($profile['profile'] < 100.0) {
            $this->errorBag['profile'] = true;
        }
        if ($user->locked == 1) {
            $this->errorBag['locked'] = true;
        }


        if (!is_null($user->personalDetails)) {

            $age = DataValidation::ageQualifying(Crypt::decryptString($user->personalDetails->dob));;
            if ($age !== false) {
                $this->errorBag['profile'] = true;
            }
            if (is_null($user->otp)) {
                $this->errorBag['otp'] = true;
                $user->locked = 1;
                $user->save();
            } else {
                if ($user->otp->validated !== 1) {
                    $this->errorBag['otp'] = true;
                    $user->locked = 1;
                    $user->save();
                }
            }
            $dra = (new DRAHandler)->draGet();

            if ($dra['success'] === false) {
                $this->errorBag['dra'] = true;
            } else {
                if (isset($dra['data'])) {
                    if ($dra['data']->test_score !== 1) {
                        $this->errorBag['dra'] = true;
                    } else {
                        if (isset($dra['data']->dra_test_score->message)) {
                            if ($dra['data']->dra_test_score->message === 'not-scored') {
                                $this->errorBag['dra'] = true;

                            }
                        }
                    }
                } else {
                    $this->errorBag['dra'] = true;
                }
            }

            $smile = (new SmileIdentity)->kyc();
            if ($smile['success'] === false) {
                $this->errorBag['kyc_smile'] = true;
            } else {
                if ($smile['data']['smile_identity'] === false) {
                    $this->errorBag['kyc_smile'] = true;
                } else {
                    if (isset($smile['data']['smile_identity']['smile_response'])) {
                        $smile = json_decode($smile['data']['smile_identity']['smile_response']);
                        if (isset($smile->ResultCode)) {
                            if ($smile->ResultCode !== '1012') {
                                $this->errorBag['kyc_smile'] = true;
                            }
                        }
                    }


                }
            }

            if (count($this->errorBag)) {
                return ActionResponse::error('Details missing', $this->errorBag, false);
            } else {
                (new MIP())->validateMIPDataStatus();
                return ActionResponse::success('Success', $this->errorBag, true);
            }
        }
        return ActionResponse::error('Details missing', $this->errorBag, false);
    }

    public function webhook(Request $request)
    {

//        $jsonData = (new MpesaController())->mpesaResponse();
        $jsonData = $request->data;
        if($request->data!==""){
            $mpesaData = json_decode($jsonData);
            if(isset($mpesaData->json_data->document)){
                if($mpesaData->json_data->last_data->remote_identifier!==""){
                    $mpesa= MpesaStatement::where('identifier',$mpesaData->json_data->last_data->remote_identifier)->latest()->first();
                    if(is_null($mpesa)){
                        if(!is_null($mpesa->webhook_data)){
                            if($mpesaData->json_data->document->status==='Completed'){
                                $mpesa->file_status = 'complete';
                                $mpesa->webhook_data = $jsonData;
                                $mpesa->save();
                                return ['success'=>true];
                            }
                        }

                    }
                }
            }
        }

        return ['success'=>false];
    }

}
