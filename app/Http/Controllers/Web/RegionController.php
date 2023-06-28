<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\Web\Region;

class RegionController extends Controller
{
    public  function search($slug){
        $regions = Region::where('region_name','LIKE','%'.$slug.'%')->get();
        $response =[];
        if(count($regions)){
            foreach ($regions as $key => $region){
                $response[]= $region->region_name;
            }
        }
        return ActionResponse::success('Towns Successfully retrieved',$response,true);
    }


}
