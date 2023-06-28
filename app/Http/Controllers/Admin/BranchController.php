<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Address\AreaCode;
use App\Models\Branch\Branch;
use App\Models\Branch\BranchSettings;
use App\Models\Company\Company;
use App\Models\Rules\SystemRule;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use App\Models\System\MenuItem;
use App\Models\System\RequestEncrypt;
use App\Models\User;
use App\Models\UserModels\Role;
use App\Models\UserModels\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BranchController extends Controller
{
    private $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public function action(Request $request)
    {
        $data = [];
        if ($request['action'] === '_branch_info_edit') {
            if(!isset($request['id'])){
                $data =[];
                $data['branch'] = Branch::branch($request['i_id']);
                $data['company'] = DataStorage::dataByID($request->i_id, config('system_config.models.user'));
                return Inertia::render('Admin/Branches/Branch/Index', compact('data'));
            }
            $data['form'] = Branch::branch($request['id']);
            return ActionResponse::success('', $data);
        }


        if ($request['action'] === 'create_branch_user') {

            $validate = CRUD::validate('email', config('system_config.models.user'), $request['email']);
            if ($validate['success'] == false) {
                $userArr = $request->all();
                $userArr['password'] = $request['last_name'] . $request['first_name'];
                $staff = CRUD::create($userArr, config('system_config.models.user'), 'users');
                if ($staff['success']) {
                    $roleArr = [
                        'user_id' => $staff['data']->id,
                        'role_id' => $request->role_id
                    ];
                    CRUD::create($roleArr, config('system_config.models.user_role'), 'user_roles');
                    return ActionResponse::success('', $staff['data']);
                }
                return $staff;
            }
            return ActionResponse::error('Could not create user the user already exists', false);
        }

        if ($request['action'] === 'create' || $request['action'] === 'edit') {

//            dd($request->all());
            $validateArr = [
                'branch_name' => $request->branch_name,
                'branch_office_tel' => $request->branch_details['branch_office_tel'],
                'branch_email' => $request->branch_details['branch_email'],
                'street_number' => $request->branch_address['street_number'],
                'street_name' => $request->branch_address['street_name'],
                'town_name' => $request->branch_address['town_name'],
                'suburb_name' => $request->branch_address['suburb_name'],
                'province_name' => $request->branch_address['province_name'],
                'country_name' => $request->branch_address['country_name'],
                'zip_code' => $request->branch_address['zip_code'],

            ];
            $this->errorBag = (new DataValidation)->required($validateArr);


            $companyOwners = $request['staff'];
            if (count($companyOwners) == 0) {
                $this->errorBag['staff'] = 'Please add at least 1 manager before you can proceed.';
            }

            if (count($this->errorBag)) {
                return ActionResponse::error('An error occurred', $this->errorBag);
            }

//            dd($request->all(),$validateArr);

            if ($request['action'] === 'edit') {
                $branchArr = [
                    'branch_name' => $request['branch_name'],
                ];
                $branch = CRUD::update(RequestEncrypt::encrypt($branchArr), config('system_config.models.branch'), 'branches', 'id', $request['id']);
            } else {
                $branchArr = [
                    'company_id' => $request['company_id'],
                    'branch_name' => $request['branch_name'],
                    'branch_unique_id' => strtoupper(substr($request['branch_name'], 0, 3) . '-' . Str::random(3) . '-' . uniqid() . '-CRD-BR-MT'),
                ];
                $branch = CRUD::create(RequestEncrypt::encrypt($branchArr), config('system_config.models.branch'), 'branches');
            }

            if ($branch['success']) {

                $branch = $branch['data'];
                $companyStaff = $request['staff'];
                if($request['action']==='edit'){
                    $searchValues =[];
                    foreach ($companyStaff as $key => $value) {
                        $searchValues[] = [
                            'user_id' => $value['id'],
                            'branch_id' => $branch->id,
                            'company_id' => $request['company_id'],
                        ];
                    }
                    if(count($searchValues)){
                        foreach ($searchValues as $key => $value){
                            $delete[$key]=(new CRUD)->delete($value, 'company_staff');
                        }
                    }
                }

                foreach ($companyStaff as $key => $value) {
                    $roleId = 6;
                    if(!is_null(User::find($value['id'])->role)){
                        $roleId = User::find($value['id'])->role->role_id;
                    }
                    $userCompanyArr = [
                        'company_id' => $request['company_id'],
                        'branch_id' => $branch->id,
                        'role_id' => $roleId,
                        'user_id' => $value['id'],
                    ];
                    CRUD::create(RequestEncrypt::encrypt($userCompanyArr), config('system_config.models.company_staff'), 'company_staff');
                }




                $branchDetailsArr = $request['branch_details'];

                $branchDetailsArr['branch_id'] = $branch->id;
                if ($request['action'] === 'edit') {
                    CRUD::update(RequestEncrypt::encrypt($branchDetailsArr), config('system_config.models.branch_details'), 'branch_details', 'branch_id', $branch->id);
                } else {
                    CRUD::create(RequestEncrypt::encrypt($branchDetailsArr), config('system_config.models.branch_details'), 'branch_details');
                }


                $areaCodeArray = [];
                $suburb = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['branch_address']), config('system_config.models.suburb'), 'suburbs', 'suburb_name', $request['branch_address']['suburb_name']);
                if ($suburb['success']) {
                    $areaCodeArray['suburb_id'] = $suburb['data']->id;
                }

                $town = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['branch_address']), config('system_config.models.town'),
                    'towns', 'town_name',
                    $request['branch_address']['town_name']
                );
                if ($town['success']) {
                    $areaCodeArray['town_id'] = $town['data']->id;
                }
                $province = CRUD::createUpdate(
                    RequestEncrypt::encrypt($request['branch_address']),
                    config('system_config.models.province'),
                    'provinces',
                    'province_name',
                    $request['branch_address']['province_name']
                );
                if ($province['success']) {
                    $areaCodeArray['province_id'] = $province['data']->id;
                }
                $areaCodeArray['zip_code'] = $request['branch_address']['zip_code'];
                $areaCodeArray['country_id'] = $request['branch_address']['country_name'];
                $areaCode = (new AreaCode)->validate($areaCodeArray);

                $addressArray = [
                    'area_code_id' => $areaCode,
                    'branch_id' => $branch->id,
                    'company_id' => $request['company_id'],
                    'street_number' => $request['branch_address']['street_number'],
                    'street_name' => $request['branch_address']['street_name'],
                    'addr_complex_unit' => $request['branch_address']['addr_complex_unit'],
                    'address_type' => 'branch_address',
                ];

                if ($request['action'] === 'edit') {
                    $message = 'Branch successfully edited';
                    CRUD::update(RequestEncrypt::encrypt($addressArray), config('system_config.models.address'), 'addresses', 'branch_id', $branch->id);
                } else {
                    $message = 'Branch successfully added';
                    CRUD::create(RequestEncrypt::encrypt($addressArray), config('system_config.models.address'), 'addresses');
                }
                $data = Company::companyData($request['company_id']);
                return ActionResponse::success($message, $data);

            }
        }

    }

    public function branch(Request $request)
    {
        if ($request['action'] === '_branch_info_edit') {
                $data = [];
                $data['roles'] = Role::all();
                $data['menu_items'] = MenuItem::all();
                $data['branch'] = Branch::branch($request['i_id']);
                $data['company'] = DataStorage::dataByID( $data['branch']['company_id'], config('system_config.models.company'));
                $data['rulesForm'] = SystemRule::buildRulesForm();
                $data['settings'] = BranchSettings::settings($request['i_id']);
//                dd($data);
//                dd($data['rulesForm'][6]['category']['sub_category'][16]);
//                dd($data['rulesForm']);


//                must set default if branch does not have settings already (foreach branch
//                dd($data);
                return Inertia::render('Admin/Branches/Branch/Index', compact('data'));

        }
    }
}
