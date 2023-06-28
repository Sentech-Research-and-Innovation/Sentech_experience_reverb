<?php


namespace App\Models\Company;


use App\Models\System\DataStorage;

class CompanyRules
{

    public static function rules($id,$request){
        $company = DataStorage::dataByID( $id, config('system_config.models.company'));
        dd($company,$request);
    }
}
