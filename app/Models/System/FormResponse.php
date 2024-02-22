<?php


namespace App\Models\System;


use App\Models\Branch\BranchSettings;
use App\Models\Categories\RuleCategory;
use App\Models\Categories\RuleSubCategory;
use App\Models\LoanWizard\LoanApplication;
use App\Models\LoanWizard\SystemUser;
use App\Models\Rules\SystemRule;

class FormResponse
{
    public static function form($id)
    {
        $response = [];
        $form = [
            'application_id' => $id,
            'first_name' => '',
            'last_name' => '',
            'id_number' => '',
            'email' => '',
            'mobile_number' => '',
            'home_number' => '',
            'work_number' => '',
            'addr_complex_unit' => '',
            'street_number' => '',
            'street_name' => '',
            'suburb_name' => '',
            'province_name' => '',
            'zip_code' => '',
            'town_name' => '',
            'user_id' => '',
            'employer_name' => '',
        ];

        $application = LoanApplication::find($id);


        if (!is_null($application)) {
            $response = [];
            $response['step'] = config('system_config.steps.' . $application->current_step);
            $user = SystemUser::find($application->user_id);
            if (!is_null($user)) {
                $form['user_id'] = $user->id;
                $form['first_name'] = $user->first_name;
                $form['last_name'] = $user->last_name;
                $form['id_number'] = $user->id_number;
                $form['email'] = $user->email;
                if (!is_null($user->personalDetails)) {
                    $form['mobile_number'] = $user->personalDetails->mobile_number;
                    $form['home_number'] = $user->personalDetails->home_number;
                    $form['work_number'] = $user->personalDetails->work_number;
                }
                $address = DataStorage::data('address', 'address', $user);
                if (!is_null($address)) {

                    $areaCode = DataStorage::data('area_code', 'areaCode', $user->address);

                    if ((!is_null($areaCode))) {
                        if (count($areaCode)) {
                            $province = "";
                            $town = "";
                            $suburb = "";
                            if (!is_null($user->address->areaCode->province)) {
                                $province = $user->address->areaCode->province->province_name;
                            }
                            if (!is_null($user->address->areaCode->town)) {
                                $town = $user->address->areaCode->town->town_name;
                            }
                            if (!is_null($user->address->areaCode->suburb)) {
                                $suburb = $user->address->areaCode->suburb->suburb_name;
                                $form['addr_complex_unit'] = $address['address']['addr_complex_unit'];
                                $form['street_number'] = $address['address']['street_number'];
                                $form['street_name'] = $address['address']['street_name'];
                                $form['zip_code'] = $areaCode['area_code']['zip_code'];
                                $form['suburb_name'] = $suburb;
                                $form['town_name'] = $town;
                                $form['province_name'] = $province;
                            }

                        }
                    }


                }


            }
           $qualifying= FormResponse::qualifyingCriteria($application->branch_id);
            if(count($qualifying)){
                foreach ($qualifying as $key => $value){
                    $qualifying[$key]['selectedCat'] = false;
                    if($application->loan_cat_id == $value['id']){
                        $qualifying[$key]['selectedCat'] = true;
                        $response['selectedCatID'] =$value['id'];
                    }
                }
            }
            $employment = DataStorage::dataByColumn('application_id',$id,config('system_config.models.employment_information'));
            if(!is_null($employment)){
                $form['employer_name'] = $employment['employer_name'];
            }
            $response['qualifying'] = $qualifying;
            $response['form'] = $form;
            return $response;

        }
    }

    public static function qualifyingCriteria($id)
    {
        $response = [];
        $qualifyingCat = BranchSettings::where('branch_id', $id)->latest()->first();
        if (!is_null($qualifyingCat)) {
            $qualifyingCat = RequestEncrypt::decrypt($qualifyingCat->toArray());
            $settings = json_decode($qualifyingCat['branch_settings']);
            $category = RuleCategory::find(7);
            if (!is_null($category)) {
                $sub = RuleSubCategory::find(15);
                if (!is_null($sub)) {
                    $rules = SystemRule::where('sub_cat_id', 15)->get();
                    if (count($rules)) {
                        $settingsArr = (array)$settings->selected;
                        foreach ($rules as $key => $value) {
                            $response[$key] = $value->toArray();
                            if (count($settingsArr)) {
                                foreach ($settingsArr as $s => $v) {
                                    $response[$key]['selectedCat'] = false;
                                    $response[$key]['selected'] = $settingsArr[$value->id];
                                }
                            }
                        }
                    }
                }
            }


        }

        return $response;
    }
}
