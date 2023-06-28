<?php


namespace App\Models\Address;


use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{

    public  static  function validateTown($request){
        $validateTown = CRUD::validate('town_name', config('system_config.models.town'),  $request->town_name);
        $town_arr=[
            'town_name'=>$request->town_name,
        ];
        if ($validateTown['success'] == false) {
            $town=CRUD::create(RequestEncrypt::encrypt($town_arr), config('system_config.models.town'), 'towns');
            $town_id = $town['data']->id;
        }else{
            $town_id = $validateTown['data']->id;
        }

        return $town_id;
    }
}
