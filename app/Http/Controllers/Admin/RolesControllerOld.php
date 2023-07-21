<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\UserModels\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RolesController extends Controller
{


    public  function index()
    {

        $roles = Role::paginate(10);
        $data['roles'] = $roles;
        // dd($data);
        return Inertia::render('Admin/Roles/Index', compact('data'));
    }

    public  function  action(Request $request)
    {

        if ($request['action'] === 'edit' || $request['action'] === 'create') {
            $roleArr = [
                'role_name' => $request['role_name']
            ];
            if ($request['action'] === 'edit') {
                $role = CRUD::update($roleArr, config('system_config.models.role'), 'roles', 'id', $request['id']);
            } else {
                $role = CRUD::create($roleArr, config('system_config.models.role'), 'roles');
            }

            if ($role['success']) {
                return ActionResponse::success('Role successfully updated', $role['data']);
            }
        }
        if ($request['action'] === '_role_edit') {
            $data = [];
            $data['form'] = DataStorage::dataByID($request['id'], config('system_config.models.role'));
            return ActionResponse::success('Role successfully retrieved', $data);
        }
    }
}
