<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\DataStorage;
use App\Models\System\ImportMipCustomers;
use App\Models\System\Migman;
use App\Models\System\MIP;
use App\Models\System\Profile;
use App\Models\Web\IzweApplication;
use App\Models\Web\IzweProduct;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use  App\Models\System\SpinMobile;

class DashboardController extends Controller
{

    private $errorBag;

    public function __construct()
    {
        $this->errorBag =[];
    }

    public function dashboard()
    {
        $response =[];
        return Inertia::render('Admin/Dashboard', compact('response'));
    }

    public function onboarding(Request $request)
    {

        $user = Auth::user();
        $product = IzweProduct::where('product_country_id',$user->personalDetails->p_country_id)->first();


        $completeProfile = true;
        if(isset($request['_mid'])){
            if($request['_mid']==='apply'){
                $completeProfile = false;
            }
        }


        if(isset($request['_pid'])){
            if(!is_null($product)){
                $app_arr = [
                    'application_status' => 'default',
                    'user_id' => Auth::user()->id,
                    'product_id' => $product->id,
                ];

                IzweApplication::validateIzweApplication($app_arr);

            }

            return redirect(RouteServiceProvider::HOME);
//            if($completeProfile){
//                $applications = IzweApplication::applications();
//                if(count($applications['applications'])){
////                return redirect(RouteServiceProvider::HOME);
//                }
//            }
        }


        $data['completeProfile'] = $completeProfile;
        $data['product'] = $product;
        return Inertia::render('User/OnBoarding',compact('data'));
    }

}
