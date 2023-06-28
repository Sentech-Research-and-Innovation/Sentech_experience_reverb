<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\Web\Town;

class TownController extends Controller
{
    public  function search($slug){
        $towns = Town::where('town_name','LIKE','%'.$slug.'%')->get();
        $response=[];
        if(count($towns)){
            foreach ($towns as $key => $town){
                $response[]= $town->town_name;
            }
        }
        return ActionResponse::success('Towns Successfully retrieved',$response,true);
    }


}
