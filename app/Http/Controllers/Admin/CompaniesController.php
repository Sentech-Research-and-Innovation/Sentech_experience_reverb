<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address\AreaCode;
use App\Models\Address\Country;
use App\Models\Company\Company;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use App\Models\System\RequestEncrypt;
use App\Models\User;
use App\Models\UserModels\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CompaniesController extends Controller
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function index()
    {
        $data = [];
        $data['countries'] = Country::all();
        $data['owners'] = (new User())->owners();
        $data['assignedOwners'] = false;
        $data['companies'] = Company::all();
        if (count($data['companies'])) {
            foreach ($data['companies'] as $key => $company) {
                $details = DataStorage::data('company_details', 'companyDetails', $company);
                if ($details['company_details'] !== false) {
                    $data['companies'][$key]['company_office_email'] = $details['company_details']['company_office_email'];
                }
            }
        }

        return Inertia::render('Admin/Companies/Index', compact('data'));
    }

    public function company(Request $request)
    {
        if ($request['action'] === '_company_info_edit') {
            $data = Company::companyData($request->i_id);
            return Inertia::render('Admin/Companies/Company/Index', compact('data'));
        }
    }

    public function action(Request $request)
    {

        if ($request['action'] === 'create_user') {
            $validate = CRUD::validate('email', config('system_config.models.user'), $request['email']);
            if ($validate['success'] == false) {
                $userArr = $request->all();
                $userArr['password'] = $request['last_name'] . $request['first_name'];
                $owner = CRUD::create($userArr, config('system_config.models.user'), 'users');
                if ($owner['success']) {
                    $roleArr = [
                        'user_id' => $owner['data']->id,
                        'role_id' => 2
                    ];
                    CRUD::create($roleArr, config('system_config.models.user_role'), 'user_roles');
                    return ActionResponse::success('', $owner['data']);
                }
                return $owner;
            }
            return ActionResponse::error('Could not create user the user already exists', false);
        }
        if ($request['action'] === 'create' || $request['action'] === 'edit') {

            $validateArr = [
                'company_name' => $request->company_name,
                'company_vat_number' => $request->company_details['company_vat_number'],
                'company_registration_number' => $request->company_details['company_registration_number'],
                'company_ncr_number' => $request->company_details['company_ncr_number'],
                'company_office_tel_number' => $request->company_details['company_office_tel_number'],
                'company_office_email' => $request->company_details['company_office_email'],
                'street_number' => $request->company_address['street_number'],
                'street_name' => $request->company_address['street_name'],
                'town_name' => $request->company_address['town_name'],
                'suburb_name' => $request->company_address['suburb_name'],
                'province_name' => $request->company_address['province_name'],
                'country_name' => $request->company_address['country_name'],
                'zip_code' => $request->company_address['zip_code'],

            ];

            $this->errorBag = (new DataValidation)->required($validateArr);


            $companyOwners = $request['owners'];
            if (count($companyOwners) == 0) {
                $this->errorBag['owners'] = 'Please add at least 1 company owner before you can proceed.';
            }

            if (count($this->errorBag)) {
                return ActionResponse::error('An error occurred', $this->errorBag);
            }


            if ($request['action'] === 'edit') {
                $companyArr = [
                    'company_name' => $request['company_name'],
                ];
                $company = CRUD::update(RequestEncrypt::encrypt($companyArr), config('system_config.models.company'), 'companies', 'id', $request['id']);
            } else {
                $companyArr = [
                    'company_name' => $request['company_name'],
                    'company_unique_id' => strtoupper(substr($request['company_name'], 0, 3) . '-' . Str::random(3) . '-' . uniqid() . '-CRD-MT'),
                ];
                $company = CRUD::create(RequestEncrypt::encrypt($companyArr), config('system_config.models.company'), 'companies');
            }

            if ($company['success']) {
                //Add owners
                $company = $company['data'];
                if (count($companyOwners)) {


                    $delete = UserCompany::where('company_id', $company->id)->get();
                    if (count($delete)) {
                        foreach ($delete as $key => $value) {
                            $value->delete();
                        }
                    }
                    foreach ($companyOwners as $key => $value) {
                        $userCompanyArr = [
                            'company_id' => $company->id,
                            'user_id' => $value['id']
                        ];
                        CRUD::create(RequestEncrypt::encrypt($userCompanyArr), config('system_config.models.user_company'), 'user_companies');

                    }
                }


                $companyDetailsArr = $request['company_details'];
                $companyDetailsArr['company_id'] = $company->id;
                //Save Company Details

                if ($request['action'] === 'edit') {
                    CRUD::update(RequestEncrypt::encrypt($companyDetailsArr), config('system_config.models.company_details'), 'company_details', 'company_id', $company->id);
                } else {
                    CRUD::create(RequestEncrypt::encrypt($companyDetailsArr), config('system_config.models.company_details'), 'company_details');
                }


                $areaCodeArray = [];
                $suburb = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['company_address']), config('system_config.models.suburb'), 'suburbs', 'suburb_name', $request['company_address']['suburb_name']);
                if ($suburb['success']) {
                    $areaCodeArray['suburb_id'] = $suburb['data']->id;
                }

//                dd($request['company_address']);
                $town = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['company_address']), config('system_config.models.town'),
                    'towns', 'town_name',
                    $request['company_address']['town_name']
                );
                if ($town['success']) {
                    $areaCodeArray['town_id'] = $town['data']->id;
                }
                $province = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['company_address']),
                    config('system_config.models.province'),
                    'provinces',
                    'province_name',
                    $request['company_address']['province_name']
                );
                if ($province['success']) {
                    $areaCodeArray['province_id'] = $province['data']->id;
                }
                $areaCodeArray['zip_code'] = $request['company_address']['zip_code'];
                $areaCodeArray['country_id'] = $request['company_address']['country_name'];
                $areaCode = (new AreaCode)->validate($areaCodeArray);

                $addressArray = [
                    'area_code_id' => $areaCode,
                    'company_id' => $company->id,
                    'street_number' => $request['company_address']['street_number'],
                    'street_name' => $request['company_address']['street_name'],
                    'addr_complex_unit' => $request['company_address']['addr_complex_unit'],
                    'address_type' => 'company_address',
                ];

                if ($request['action'] === 'edit') {
                    CRUD::update(RequestEncrypt::encrypt($addressArray), config('system_config.models.address'), 'addresses', 'company_id', $company->id);
                } else {
                    CRUD::create(RequestEncrypt::encrypt($addressArray), config('system_config.models.address'), 'addresses');
                }


                if ($request['action'] === 'edit') {
                    $data['form'] = Company::company($company->id);
                    $data['countries'] = Country::all();
                    $data['owners'] = (new User())->owners();
                    $data['assignedOwners'] = (new User())->assignedOwners($company->id);
                    return ActionResponse::success('Company successfully created', $data);
                }
                return ActionResponse::success('Company successfully created', ['cid' => $company->id, 'unique' => $company->company_unique_id]);

            } else {
                return ActionResponse::error('An error occured', false);
            }

        }

        if ($request['action'] === '_user_edit') {

            dd($request->all());
            return Inertia::render('Admin/Users/ViewUser', compact('data'));
        }
    }


}
