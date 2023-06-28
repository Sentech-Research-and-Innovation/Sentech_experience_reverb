<?php


namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address\Address;
use App\Models\Address\AreaCode;
use App\Models\Address\Province;
use App\Models\Address\Suburb;
use App\Models\Address\Town;
use App\Models\LoanWizard\LoanApplication;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use App\Models\System\Experian;
use App\Models\System\FormResponse;
use App\Models\System\RequestEncrypt;
use App\Models\System\SouthAfricanIDNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoanWizzardController extends Controller
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function action(Request $request)
    {


        if ($request['action'] === 'form-update') {
            return ActionResponse::success('', FormResponse::form($request['application_id']));

        }
        if ($request['action'] === 'employment-information') {
            $employmentArr = [
                'employer_name' => $request['employer_name'],
                'user_id' => $request['user_id'],
                'application_id' => $request['application_id']
            ];
            $update = CRUD::createUpdate(RequestEncrypt::encrypt($employmentArr), config('system_config.models.employment_information'), 'employment_information', 'application_id', $request['application_id']);
            if($update['success']){
                return ActionResponse::success('', FormResponse::form($request['application_id']));
            }
            return $update;

        }
        if ($request['action'] === 'personal-information') {
            $validateArr = [
                'application_id' => $request['application_id'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'id_number' => $request['id_number'],
                'mobile_number' => $request['mobile_number'],
                'branch_id' => $request['branch_id'],
                'company_id' => $request['company_id'],
                'email' => $request['email'],
                'home_number' => $request['home_number'],
                'work_number' => $request['work_number'],
                'addr_complex_unit' => $request['addr_complex_unit'],
                'street_number' => $request['street_number'],
                'street_name' => $request['street_name'],
                'suburb_name' => $request['suburb_name'],
                'town_name' => $request['town_name'],
                'province_name' => $request['province_name'],
                'zip_code' => $request['zip_code']
            ];
            $this->errorBag = (new DataValidation)->required($validateArr);
            if (count($this->errorBag)) {
                return ActionResponse::error('', $this->errorBag);
            }

            $suburb_id = Suburb::validateSuburb($request);
            $town_id = Town::validateTown($request);
            $province_id = Province::validateProvince($request);
            $areaCode = AreaCode::validateAreaCode($request, $town_id, $province_id, $suburb_id);

            $address_arr = [
                'area_code_id' => $areaCode,
                'user_id' => $request['user_id'],
                'branch_id' => $request['branch_id'],
                'address_type' => 'system_user_address',
            ];
            Address::validateAddress($request, $address_arr);
            $personalDetArr = [
                'home_number' => $request['home_number'],
                'work_number' => $request['work_number'],
            ];
            CRUD::update(['email' => $request['email']], config('system_config.models.system_user'), 'system_users', 'id', $request['user_id']);
            $update = CRUD::update($personalDetArr, config('system_config.models.personal_details'), 'personal_details', 'user_id', $request['user_id']);
            if ($update['success']) {
                $applicationArr = ['current_step' => $request['current_step']];
                CRUD::update($applicationArr, config('system_config.models.loan_application'), 'loan_applications', 'id', $request['application_id']);
                return ActionResponse::success('', FormResponse::form($request['application_id']));
            }
            return $update;


        }
        if ($request['action'] === 'select=loan-cat') {
            $updateArr = [
                'loan_cat_id' => $request['selectedCatID']
            ];
            return CRUD::update($updateArr, config('system_config.models.loan_application'), 'loan_applications', 'id', $request['application']['id']);

        }
        if ($request['action'] === 'verify-otp') {
            $response = [];
            $validate = CRUD::validate('application_id', config('system_config.models.credit_report_data'), $request['application']['id']);
            if ($validate['success'] == false) {
                (new Experian())->report($request->all());
            }
            $validate = CRUD::validate('application_id', config('system_config.models.credit_report_data'), $request['application']['id']);
            $creditReport = RequestEncrypt::decrypt($validate['data']->toArray());
            $response = FormResponse::form($request['application']['id']);
//            $response['report'] = json_decode($creditReport['credit_report_data']);
//            dd($response,$request->all());
            return ActionResponse::success('Application successfully created.', $response);


        }
        if ($request['action'] === 'application_init') {
            $userArr = [
                'id_number' => $request['id_number'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
            ];

            $this->errorBag = (new DataValidation)->required($userArr);

            $idValidate = (new SouthAfricanIDNumber)->validate($request['id_number']);
            $mobileNumber = DataValidation::mobileNumber($request['mobile_number'], 'ZA');
            if ($mobileNumber) {
                $this->errorBag['mobile_number'] = $mobileNumber;
            }
            if ($idValidate !== true) {
                $this->errorBag['id_number'] = $idValidate;
            }

            if (count($this->errorBag)) {
                return ActionResponse::error('An error occurred', $this->errorBag);
            }


            if ($request['trained']) {
                $userID = null;
                $user = CRUD::validate('id_number', config('system_config.models.system_user'), $request['id_number']);
                if ($user['success'] == false) {

                    $save = CRUD::create($userArr, config('system_config.models.system_user'), 'system_users');
                    if ($save['success']) {
                        $userID = $save['data']->id;
                    }
                } else {
                    $userID = $user['data']->id;
                }


                if (!is_null($userID)) {

                    $mobileNumber = DataValidation::mobileNumber($request['mobile_number'], 'ZA');

                    if ($mobileNumber) {
                        $this->errorBag['mobile_number'] = $mobileNumber;
                    } else {
                        $personalDetails = CRUD::validate('user_id', config('system_config.models.personal_details'), $userID);
                        if (!$personalDetails['success']) {
                            $personalDetailsArr = [
                                'user_id' => $userID,
                                'mobile_number' => $request['mobile_number'],
                                'created_by' => Auth::user()->id,
                            ];
                            $personalDetails = CRUD::create($personalDetailsArr, config('system_config.models.personal_details'), 'personal_details');
                        } else {
//                            what must happen if number exists
                        }

//                        dd($personalDetails);
                        if ($personalDetails['success']) {
//                                must send sms
                            $applicationArr = [
                                'user_id' => $userID,
                                'company_id' => $request['company_id'],
                                'branch_id' => $request['branch_id'],
                                'training_check' => $request['trained'],
                                'current_step' => $request['current_step'],
                                'created_by' => Auth::user()->id,
                            ];
//                            dd(($applicationArr));
                            //must get branch rules
                            // more validations to come
//                            dd($applicationArr);

                            $validateApplication = LoanApplication::where('user_id', $userID)->where('branch_id', $request['branch_id'])->where('training_check', 1)->where('company_id', $request['company_id'])->where('current_step', 'training-check')->where('created_by', Auth::user()->id)->latest()->first();

//                            dd($validateApplication);
                            if (is_null($validateApplication)) {
                                $loanApplication = CRUD::create($applicationArr, config('system_config.models.loan_application'), 'loan_applications');
                                $loanApplication = $loanApplication['data'];
                            } else {
                                $loanApplication = DataStorage::dataByID($validateApplication->id, config('system_config.models.loan_application'));
//                                dd($loanApplication);
                            }

//                            dd($loanApplication);
                            return ActionResponse::success('Application successfully created.', $loanApplication);


                        }

                    }


                }

            }
        }

        return ActionResponse::error('An error occurred', $this->errorBag);
    }

    public function getLoanWizzard(Request $request)
    {


//        FormResponse::form(22);
        $data = [];
        if (isset($request['b_id'])) {
            if (isset($request['action'])) {
                if ($request['action'] === 'new-application') {
                    $branch = DataStorage::dataByID($request['b_id'], config('system_config.models.branch'));
                    $data = [
                        'branch' => $branch,
                        'company' => DataStorage::dataByID($branch->company_id, config('system_config.models.company')),
                    ];
                }
            }

        }

        return Inertia::render('LoanWizzard/Index', compact('data'));
    }
}
