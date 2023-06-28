<?php


namespace App\Models\Address;


use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public  static function validateAddress($request,$personalDetails){
        $validateAddress = CRUD::validate('user_id', config('system_config.models.address'),  $personalDetails['user_id']);
        $address_arr=[
            'area_code_id'=>$personalDetails['area_code_id'],
            'user_id'=>$personalDetails['user_id'],
            'street_number'=>$request->street_number,
            'street_name'=>$request->street_name,
            'addr_complex_unit'=>$request->addr_complex_unit,
            'address_type'=>$personalDetails['address_type'],
        ];

        if ($validateAddress['success'] == false) {
            $address=CRUD::create(RequestEncrypt::encrypt($address_arr), config('system_config.models.address'), 'addresses');
            $address_id = $address['data']->id;
        }else{
            CRUD::update(RequestEncrypt::encrypt($address_arr), config('system_config.models.address'), 'addresses','id',$validateAddress['data']->id);
            $address_id = $validateAddress['data']->id;
        }
        return $address_id;
    }
    public function areaCode(){
    return $this->hasOne(AreaCode::class, 'id', 'area_code_id');
}
}
