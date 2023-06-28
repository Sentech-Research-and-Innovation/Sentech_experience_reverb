<?php


namespace App\Models\Branch;


use App\Models\Address\Address;
use App\Models\Address\AreaCode;
use App\Models\Address\Country;
use App\Models\Address\Province;
use App\Models\Address\Suburb;
use App\Models\Address\Town;
use App\Models\Company\CompanyDetails;
use App\Models\System\DataStorage;
use App\Models\System\RequestEncrypt;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{

    public function branchDetails(){
        return $this->hasOne(BranchDetails::class, 'branch_id', 'id');
    }

    public static function branch($id){
        $branch = DataStorage::dataByID($id, config('system_config.models.branch'));
        $branchAddress = Address::where('branch_id', $id)->where('address_type', 'branch_address')->latest()->first();
        $companyDetails = DataStorage::data('branch_details', 'branchDetails', $branch);
        $data = $branch->toArray();
        $data['branch_details'] = $companyDetails['branch_details'];
        $data['staff'] = (new User())->assignedStaff($id);

        if (!is_null($branchAddress)) {
            $data['branch_address'] = RequestEncrypt::decrypt($branchAddress->toArray());
            $areaCode = AreaCode::find($data['branch_address']['area_code_id']);
            if (!is_null($areaCode)) {

                $data['branch_address']['zip_code'] = $areaCode->zip_code;
                if (!is_null(Suburb::find($areaCode->suburb_id))) {
                    $data['branch_address']['suburb_name'] = Suburb::find($areaCode->suburb_id)->suburb_name;
                }
                if (!is_null(Country::find($areaCode->country_id))) {
                    $data['branch_address']['country_name'] = Country::find($areaCode->country_id)->id;
                }
                if (!is_null(Province::find($areaCode->province_id))) {
                    $data['branch_address']['province_name'] = Province::find($areaCode->province_id)->province_name;
                }
                if (!is_null(Town::find($areaCode->town_id))) {
                    $data['branch_address']['town_name'] = Town::find($areaCode->town_id)->town_name;
                }

            }

        } else {
            $data['branch_address'] = false;
        }

        return $data;
    }

}
