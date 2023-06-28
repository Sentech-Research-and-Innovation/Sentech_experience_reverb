<?php


namespace App\Models\Address;


use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Database\Eloquent\Model;

class Suburb extends Model
{

    public  static  function validateSuburb($request){
        $validateSuburb = CRUD::validate('suburb_name', config('system_config.models.suburb'),  $request->suburb_name);
        $suburb_arr=[
            'suburb_name'=>$request->suburb_name,
        ];
        if ($validateSuburb['success'] == false) {
            $suburb=CRUD::create(RequestEncrypt::encrypt($suburb_arr), config('system_config.models.suburb'), 'suburbs');
            $suburb_id = $suburb['data']->id;
        }else{
            $suburb_id = $validateSuburb['data']->id;
        }

        return $suburb_id;
    }
}
