<?php


namespace App\Models\Address;


use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Database\Eloquent\Model;

class AreaCode extends Model
{

    public function validate($request)
    {
        $validateAreaCode = AreaCode::where('zip_code', $request['zip_code'])->where('town_id', $request['town_id'])->where('country_id', $request['country_id'])->where('province_id', $request['province_id'])->where('suburb_id', $request['suburb_id'])->first();

        if (!is_null($validateAreaCode)) {
            $area_code_id = $validateAreaCode->id;
        } else {
            $area_code = CRUD::create(RequestEncrypt::encrypt($request), config('system_config.models.area_code'), 'area_codes');
            $area_code_id = $area_code['data']->id;
        }
        return $area_code_id;
    }
    public  static  function validateAreaCode($request,$town_id,$province_id,$suburb_id){
        $validateAreaCode = AreaCode::where('zip_code',$request->zip_code)->where('town_id',$town_id)->where('province_id',$province_id)->where('country_id',$request->p_country_id)->where('suburb_id',$suburb_id)->first();
        $suburb_arr=[
            'zip_code'=>$request->zip_code,
            'town_id'=>$town_id,
            'country_id'=>$request->p_country_id,
            'province_id'=>$province_id,
            'suburb_id'=>$suburb_id,
        ];
        if(!is_null($validateAreaCode)){
            $area_code_id = $validateAreaCode->id;
        }else{
            $area_code=CRUD::create(RequestEncrypt::encrypt($suburb_arr), config('system_config.models.area_code'), 'area_codes');
            $area_code_id = $area_code['data']->id;
        }
        return $area_code_id;
    }

    public  function suburb(){
        return $this->hasOne(Suburb::class, 'id', 'suburb_id');
    }

    public  function town(){
        return $this->hasOne(Town::class, 'id', 'town_id');
    }
    public  function country(){
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public  function province(){
        return $this->hasOne(Province::class, 'id', 'province_id');
    }


}
