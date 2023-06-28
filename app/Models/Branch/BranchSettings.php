<?php


namespace App\Models\Branch;


use App\Models\System\DataStorage;
use Illuminate\Database\Eloquent\Model;

class BranchSettings extends Model
{

    protected $table ='branch_settings';

    public  static  function settings($id){
        $response=[
            'selected' => [],
            'living_expenses' => []
        ];
        $settings = DataStorage::dataByColumn('branch_id',$id,config('system_config.models.branch_settings'));

        if(!is_null($settings)){
            $response=  json_decode($settings['branch_settings']);
        }

        return $response;

    }
}
