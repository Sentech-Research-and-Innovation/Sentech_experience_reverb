<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Branch\BranchSettings;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\RequestEncrypt;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SystemSettingsController extends Controller
{

    public function system()
    {

//        return BranchSettings::settings(38);
        $data = [];
        return Inertia::render('Admin/System/Index', compact('data'));
    }

    public function action(Request $request)
    {

        if ($request['action'] === 'save-settings'){

            if (count($request['selected'])) {
                $deleteArr = [
                    'branch_id' => $request['branch_id'],
                ];
                (new CRUD)->delete($deleteArr, 'branch_settings');
                $arr = [
                    'selected'=> $request['selected'],
                    'living_expenses'=> $request['living_expenses'],
                ];

                foreach ($arr as $key => $value) {
                    foreach ($value as $k => $v){
                        if (is_null($v)) {
                            unset($arr[$key][$k]);
                        }
                    }

                }

                $selectedArr = [
                    'branch_id' => $request['branch_id'],
                    'company_id' => $request['company_id'],
                    'branch_settings' => json_encode($arr)
                ];

                CRUD::create(RequestEncrypt::encrypt($selectedArr), config('system_config.models.branch_settings'), 'branch_settings');
                return ActionResponse::success('', [BranchSettings::settings($request['branch_id'])]);
            }
            return ActionResponse::error('',[]);
        }


    }
}

