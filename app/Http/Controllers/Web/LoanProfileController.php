<?php


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\ArrayHandler;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use App\Models\System\DRAHandler;
use App\Models\System\FormResponse;
use App\Models\System\Locale;
use App\Models\System\Migman;
use App\Models\System\Profile;
use App\Models\System\RequestEncrypt;
use App\Models\System\SmileIdentity;
use App\Models\Web\Address;
use App\Models\Web\AreaCode;
use App\Models\Web\Country;
use App\Models\Web\EmploymentType;
use App\Models\Web\IzweProduct;
use App\Models\Web\MipCustomer;
use App\Models\Web\NextOfKin;
use App\Models\Web\Region;
use App\Models\Web\SelfDeclaration;
use App\Models\Web\SmileIdentityCustomerData;
use App\Models\Web\Suburb;
use App\Models\Web\Town;
use App\Models\Web\UserTerm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class LoanProfileController extends Controller
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function index()
    {

        $system_data = [];
        $system_data['personal_details'] = FormResponse::personalDetails();
        $system_data['countries'] = Country::all();
        $system_data['employment_types'] = EmploymentType::all();
        $system_data['profile'] = $profile = (new Profile)->progress();
        return Inertia::render('LoanProfile/Index', compact('system_data'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email',
        ]);
        $required = [
            'id_number' => $request->id_number,
            'mobile_number' => $request->mobile_number,
            'dob' => $request->dob,
            'employment_type_id' => $request->employment_type_id,
            'employer_name' => $request->employer_name,
            'p_country_id' => $request->p_country_id,
            'user_email' => $request->user_email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'addr_complex_unit' => $request->addr_complex_unit,
            'street_number' => $request->street_number,
            'street_name' => $request->street_name,
            'zip_code' => $request->zip_code,
            'suburb_name' => $request->suburb_name,
            'town_name' => $request->town_name,
            'region' => $request->region_province,
//            'employment_type_name' => $request->employment_type_name,
            'country_name' => $request->country_name,
            'alpha_2' => $request->alpha_2,
        ];
        $validate = new DataValidation();
        $this->errorBag = $validate->required($required);
        if (count($request->nextOfKin)) {
            foreach ($request->nextOfKin as $key => $value) {
                if (count($value)) {
                    foreach ($value as $i => $item) {
                        $this->errorBag['nextOfKin'][$key][$i] = false;

                        if (is_null($item) || $item === "") {
                            $this->errorBag['nextOfKin'][$key][$i] = 'The ' . str_replace('_', ' ', ucfirst($i)) . ' field is required';
                        }
                        if ($i === 'next_of_kin_email') {
                            if (!filter_var($item, FILTER_VALIDATE_EMAIL)) {
                                $this->errorBag['nextOfKin'][$key][$i] = "Please enter a valid email address";
                            }
                        }
                        if ($i === 'next_of_kin_mobile') {
                            $mobileNumber = DataValidation::mobileNumber($item, $request['alpha_2']);
                            if ($mobileNumber !== false) {
                                $this->errorBag['nextOfKin'][$key][$i] = $mobileNumber;
                            }
                        }
                        if (ArrayHandler::array_search(false, $this->errorBag['nextOfKin'], $i) !== false) {
                            unset($this->errorBag['nextOfKin'][$key][$i]);
                        }

                    }
                }

            }
        }

        if (!isset($this->errorBag['nextOfKin'])) {
            $this->errorBag['nextOfKin'] = false;
        } else {


            if (isset($this->errorBag['nextOfKin'])) {
                if (count($this->errorBag['nextOfKin']) == 1) {
                    if (!isset($this->errorBag['nextOfKin'][0])) {
                        $this->errorBag['nextOfKin'][0] = [];
                    }
                    if (!isset($this->errorBag['nextOfKin'][1])) {
                        $this->errorBag['nextOfKin'][1] = [];
                    }
                }
                if (count($this->errorBag['nextOfKin']) == 2) {
                    if (count($this->errorBag['nextOfKin'][0]) == 0 && count($this->errorBag['nextOfKin'][1]) == 0) {
                        unset($this->errorBag['nextOfKin']);
                    }

                }


            }
        }

        $mobileNumber = DataValidation::mobileNumberExistsLoggedIn($request->mobile_number);
        if ($mobileNumber !== false) {
            $this->errorBag['mobile_number_exists'] = $mobileNumber;
        }

        $age = DataValidation::ageQualifying($request->dob);
        if ($age !== false) {
            $this->errorBag['dob'] = $age;
        }

        $idNumber = DataValidation::idNumber($request['email']);
        if ($idNumber !== false) {
            $this->errorBag['id_number'] = $idNumber;
        }


        $mobileNumber = DataValidation::mobileNumber($request->mobile_number, $request['alpha_2']);
        if ($mobileNumber !== false) {
            $this->errorBag['mobile_number'] = $mobileNumber;
        }

        $idNumberLength = DataValidation::idNumberLength($request->id_number, $request['alpha_2']);
        if ($idNumberLength !== false) {
            $this->errorBag['id_number_length'] = $idNumberLength;
        }

        $validateActiveDebtorStatus = MipCustomer::where('id_number',$request->id_number)->first();
//        dd($validateActiveDebtorStatus);
        if(!is_null($validateActiveDebtorStatus)){
            if($validateActiveDebtorStatus->active_debtor==='Y'){
                $this->errorBag['active_debtor'] = 'Sorry you are not eligible to continue at this stage, please contact customer support for details.';
            }
        }

        if (count($this->errorBag)) {
            return ActionResponse::error('Please ensure all required fields have been filled.', $this->errorBag, false);
        }


        $personalDetails = Auth::user()->personalDetails;

        $location = Locale::location();
        $userData1['email'] = CRUD::generateEmail($request->id_number, $location->geoplugin_countryCode);
        $personal_details_arr1 = [
            'id_number' => $request->id_number,
        ];
        $smile = SmileIdentityCustomerData::where('user_id', Auth::user()->id)->first();
        if (!is_null($smile)) {
            if ($smile->id_valid == 0) {
                $updateP = CRUD::update(RequestEncrypt::encrypt($personal_details_arr1), config('system_config.models.personal_details'), 'personal_details', 'id', $personalDetails->id);
                CRUD::update(RequestEncrypt::encrypt($userData1), config('system_config.models.user'), 'users', 'id', $personalDetails->user_id);
                if ($updateP['success']) {
                    \Session::put('updated_id', Crypt::encryptString($updateP['data']->id_number));
                }
            }
        } else {
            CRUD::update(RequestEncrypt::encrypt($personal_details_arr1), config('system_config.models.personal_details'), 'personal_details', 'id', $personalDetails->id);
            CRUD::update(RequestEncrypt::encrypt($userData1), config('system_config.models.user'), 'users', 'id', $personalDetails->user_id);
        }

        $smileD = (new SmileIdentity)->kyc();

        if ($smileD['success']) {
            if (!is_null($smileD)) {
                $smile = SmileIdentityCustomerData::where('user_id', Auth::user()->id)->first();
                $smile = RequestEncrypt::decrypt($smile->toArray());
                if ($smile['id_valid'] == 0) {
                    $this->errorBag['id_number'] = 'Failed to authenticate ID number please enter valid Kenyan ID number';
                    return ActionResponse::error('', $this->errorBag, false);
                }
            }

        }

        $smile = SmileIdentityCustomerData::where('user_id', Auth::user()->id)->first();


        if (isset($smile['id_valid'])) {
            if ($smile['id_valid'] == 1) {
                $migmanData = DataStorage::data('migman', 'migman', Auth::user());
                $sanctions_status =0;
                if (isset($migmanData['migman'])) {
                    if ($migmanData['migman'] !== false) {
                        $migmanData = DataStorage::data('migman', 'migman', Auth::user());
                        $sanctions_status = $migmanData['migman']['sanctions_status'];
                    } else {
                        $migmanString = Auth::user()->firstname . ' ' . Auth::user()->lastname;
                       $migmanData= (new Migman)->watchlist($migmanString);
                       $sanctions_status = $migmanData['data']->sanctions_status;

                    }

                }

                if($sanctions_status==1){
                    return ActionResponse::error('Sorry you are not eligible to continue at this stage, please contact customer support for details.', ['sanctions'=>true], false);
                }

            }
        }
        $personal_details_arr = [
            'employment_type_id' => $request->employment_type_id,
            'employer_name' => $request->employer_name,
            'country_code' => $request->country_code,
            'dob' => $request->dob,
            'mobile_number' => $request->mobile_number,
        ];
        $updatePersonal = CRUD::update(RequestEncrypt::encrypt($personal_details_arr), config('system_config.models.personal_details'), 'personal_details', 'id', $personalDetails->id);

        if ($updatePersonal['success']) {
            if (isset($request['nextOfKin'])) {
                if (count($request['nextOfKin'])) {
                    NextOfKin::validateNetOfKin($request['nextOfKin'], $updatePersonal);
                }
            }
            $suburb_id = Suburb::validateSuburb($request);
            $town_id = Town::validateTown($request);
            $region_id = Region::validateRegion($request);

            $areaCode = AreaCode::validateAreaCode($request, $town_id, $region_id, $suburb_id);
            $address_arr = [
                'area_code_id' => $areaCode,
                'user_id' => $personalDetails->user_id,
                'personal_details_id' => $personalDetails->id,
            ];
//            UserTerm::validateUserTerms($request);
            Address::validateAddress($request, $address_arr);
//            dd(FormResponse::personalDetails());
            return ActionResponse::success('Personal Information successfully saved', FormResponse::personalDetails(), true);
        }
        return ActionResponse::error('An error occurred', [], false);

    }

    public function selfDeclarationGet()
    {

        $selfDec = DataStorage::data('self_declaration', 'selfDeclaration', Auth::user());
        if (isset($selfDec['self_declaration'])) {
            if ($selfDec['self_declaration'] !== false) {
                $selfDecArr['expenses'] = json_decode($selfDec['self_declaration']['expenses']);
                $selfDecArr['income_range'] = json_decode($selfDec['self_declaration']['income_range']);
                if (count($selfDecArr)) {
                    foreach ($selfDecArr as $key => $value) {
                        if (count($value)) {
                            foreach ($value as $k => $v) {
                                $selfDecArr[$key . '_formatted'][$k] = number_format($v, 2);
                            }
                        }
                    }
                }


                return ActionResponse::success('', $selfDecArr, true);
            }
        }
        return ActionResponse::success('', [
            'expenses' => [
                0, 999
            ],
            'income_range' => [
                0, 9999
            ],

        ], true);

    }

    public function personalDetailsGet()
    {
        return FormResponse::personalDetails();
    }

    public function selfDeclaration(Request $request)
    {

        $average = [];
        if (count($request->all())) {
            foreach ($request->all() as $key => $value) {
                $average[$key] = array_sum($value) / 2;
            }
        }
        if ($average['expenses'] >= $average['income_range']) {
            return ActionResponse::error('Your expenses cannot be higher that your income range.', true, false);
        }

        $self_dec_arr = [
            'user_id' => Auth::user()->id,
            'income_range' => json_encode($request['income_range']),
            'expenses' => json_encode($request['expenses']),
        ];
        $response = SelfDeclaration::validateSelfDeclaration($self_dec_arr);
        if ($response['success']) {
            $selfDec = DataStorage::data('self_declaration', 'selfDeclaration', Auth::user());
            $selfDec['expenses'] = json_decode($selfDec['self_declaration']['expenses']);
            $selfDec['income_range'] = json_decode($selfDec['self_declaration']['income_range']);
            return ActionResponse::success('Self declaration saved', $selfDec, true);
        }
        return ActionResponse::error('An error occurred', [], false);
    }

    public static function getDRAOdyssey(Request $request)
    {
        $user = Auth::user();
        (new DRAHandler)->draGetResults();
        $dra = (new DRAHandler)->draGet();
        $dra['has_results'] = false;
        $dra['refresh'] = false;


        if (isset($request->REASON)) {
            if ($request->REASON === 'COMPLETE') {
                $dra['refresh'] = true;
            }

        }
        if ($dra['success']) {
            $draData = CRUD::validate('user_id', config('system_config.models.dra'), $user->id);
            if (is_null($draData['data']->dra_test_score)) {
                (new DRAHandler)->draGetResults();
            } else {
                if (is_null($dra['data']->dra_test_score->totalRiskScore)) {
                    (new DRAHandler)->draGetResults();
                    $dra['has_results'] = false;
                    $dra['refresh'] = true;
                } else {
                    if ($dra['data']->dra_test_score->message === 'has-results') {
                        $dra['has_results'] = true;
                        $dra['refresh'] = false;
                    }
                }
            }

        }
        if (!isset($request->REASON)) {
            $dra['refresh'] = false;
        }

        $product = IzweProduct::where('product_country_id', $user->personalDetails->p_country_id)->first();
        $dra['product'] = $product;
        return Inertia::render('LoanProfile/DraOdyssey', compact('dra'));
    }

    public function generateLink()
    {
        if ((new DRAHandler)->draGet()['success']) {
            return (new DRAHandler)->draGet();
        } else {
            $dra = (new DRAHandler)->draPost();
            if ($dra['success'] === false) {
                return ActionResponse::error('An error occured while genarating you link, please contact support for further assistance.', $dra['errorBag'], false);
            }
        }

        return (new DRAHandler)->draGet();
    }
}
