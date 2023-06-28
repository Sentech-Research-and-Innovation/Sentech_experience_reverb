<?php


namespace App\Models\Company;


use App\Models\Address\Address;
use App\Models\Address\AreaCode;
use App\Models\Address\Country;
use App\Models\Address\Province;
use App\Models\Address\Suburb;
use App\Models\Address\Town;
use App\Models\Branch\Branch;
use App\Models\System\DataStorage;
use App\Models\System\RequestEncrypt;
use App\Models\User;
use App\Models\UserModels\Role;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $table = 'companies';

    public static function company($id)
    {

        $company = DataStorage::dataByID($id, config('system_config.models.company'));
        $companyAddress = Address::where('company_id', $id)->where('address_type', 'company_address')->latest()->first();
        $companyDetails = DataStorage::data('company_details', 'companyDetails', $company);

        $data = $company->toArray();

        $data['company_details'] = $companyDetails['company_details'];
        if (!is_null($companyAddress)) {
            $data['company_address'] = RequestEncrypt::decrypt($companyAddress->toArray());
            $areaCode = AreaCode::find($data['company_address']['area_code_id']);
            if (!is_null($areaCode)) {

                $data['company_address']['zip_code'] = $areaCode->zip_code;
                if (!is_null(Suburb::find($areaCode->suburb_id))) {
                    $data['company_address']['suburb_name'] = Suburb::find($areaCode->suburb_id)->suburb_name;
                }
                if (!is_null(Country::find($areaCode->country_id))) {
                    $data['company_address']['country_name'] = Country::find($areaCode->country_id)->id;
                }
                if (!is_null(Province::find($areaCode->province_id))) {
                    $data['company_address']['province_name'] = Province::find($areaCode->province_id)->province_name;
                }
                if (!is_null(Town::find($areaCode->town_id))) {
                    $data['company_address']['town_name'] = Town::find($areaCode->town_id)->town_name;
                }

            }

        } else {
            $data['company_address'] = false;
        }
        $data['owner'] = [];
        return $data;

    }

    public function companyDetails(){
        return $this->hasOne(CompanyDetails::class, 'company_id', 'id');
    }
    public function branches(){
        return $this->hasMany(Branch::class, 'company_id', 'id');
    }

    public function branch(){
        return $this->hasMany(Branch::class, 'company_id', 'id');
    }

   public static function companyData ($companyId){
       $data['form'] = Company::company($companyId);
       $data['countries'] = Country::all();
       $data['owners'] = (new User())->owners();
       $data['staff'] = (new User())->staff();
       $data['roles'] = Role::all();
       $company = DataStorage::dataByID($companyId, config('system_config.models.company'));
       $data['branches'] = $company->branches;
       if(count($data['branches'])){
           foreach ($data['branches'] as $key => $branch){
               $details =DataStorage::data('branch_details', 'branchDetails', $branch);
               if($details['branch_details']!==false){
                   $data['branches'][$key]['branch_email'] =$details['branch_details']['branch_email'];
               }
           }
       }

       $data['assignedOwners'] = (new User())->assignedOwners($companyId);
       return $data;
   }



}
