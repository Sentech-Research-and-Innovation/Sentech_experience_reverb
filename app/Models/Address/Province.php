<?php


namespace App\Models\Address;


use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    public  static  function validateProvince($request){
        $validateTown = CRUD::validate('province_name', config('system_config.models.province'),  $request->province_name);
        $town_arr=[
            'province_name'=>$request->province_name,
        ];
        if ($validateTown['success'] == false) {
            $province=CRUD::create(RequestEncrypt::encrypt($town_arr), config('system_config.models.province'), 'provinces');
            $province_id = $province['data']->id;
        }else{
            $province_id = $validateTown['data']->id;
        }

        return $province_id;
    }

}
