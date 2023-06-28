<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\CRUD;
use App\Models\System\DataStorage;
use App\Models\System\DataValidation;
use App\Models\System\MenuItem;
use App\Models\User;
use App\Models\UserModels\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersController extends Controller
{

    private $errorBag;

    public function __construct()
    {

        $this->errorBag = [];
    }

    public function index()
    {
        $data = [];
        $users = User::all();
       // dd($users);
        $menuItems = MenuItem::get();
        $response = [];

        if (count($users)) {
            foreach ($users as $key => $user) {
                $response[$key] = $user->toArray();
              
                $response[$key]['role_name'] = null;
                if (!is_null($user->role)) {
                    $response[$key]['role_name'] = Role::find($user->role->role_id)->role_name;
                    $response[$key]['role_id'] = Role::find($user->role->role_id)->id;

                }

                foreach ($menuItems as $k => $menu) {
                    $response[$key]['selected_permissions'][$menu->id]['create'] = false;
                    $response[$key]['selected_permissions'][$menu->id]['update'] = false;
                    $response[$key]['selected_permissions'][$menu->id]['delete'] = false;
                    $response[$key]['selected_permissions'][$menu->id]['read'] = false;
                  //  dd( $response[$key]);
                    if (count($user->permissions)) {
                        foreach ($user->permissions as $p => $permission) {
                            if ($menu->id == $permission->page_id) {
                                if ($permission->function === 'create') {
                                    $response[$key]['selected_permissions'][$menu->id]['create'] = true;
                                }
                                if ($permission->function === 'update') {
                                    $response[$key]['selected_permissions'][$menu->id]['update'] = true;
                                }
                                if ($permission->function === 'read') {
                                    $response[$key]['selected_permissions'][$menu->id]['read'] = true;
                                }
                                if ($permission->function === 'delete') {
                                    $response[$key]['selected_permissions'][$menu->id]['delete'] = true;
                                }
                            }
                        }
                    }
                }

            }
        }
        $p = $this->paginateFilter($response, 10);
        $p->withPath('users');
        $data['users'] = $p;
        $data['roles'] = Role::all();
        $data['menu_items'] = MenuItem::all();
        return Inertia::render('Admin/Users/Index', compact('data'));
    }

    public function paginateFilter($items, $perPage = 2, $page = null){
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1); 
        $total = count($items);
        $currentPage = $page;
        $offset = ($currentPage * $perPage) - $perPage;
        $itemToShow = array_slice($items, $offset, $perPage);
        //dd($itemToShow);
        return new LengthAwarePaginator($itemToShow, $total, $perPage);
    }

    public function action(Request $request)
    {

        $permissionsArr = [];

        if ($request['action'] === 'create' || $request['action'] === 'edit') {
            $userArr = [
                'email' => $request['email'],
                'last_name' => $request['last_name'],
                'first_name' => $request['first_name'],
                'role_id' => $request['role_id'],
                'name' => $request['name'],
            ];

            $this->errorBag = (new DataValidation())->required($userArr);

            if (count($this->errorBag)) {
                return ActionResponse::error('An error occurred', $this->errorBag);
            }


            if ($request['action'] === 'edit') {
                $user = CRUD::update($userArr, config('system_config.models.user'), 'users', 'id', $request['id']);
            } else {
                $user = CRUD::create($userArr, config('system_config.models.user'), 'users');
            }


            if ($user['success']) {

                $rolesArr = [
                    'role_id' => $request['role_id'],
                    'user_id' => $user['data']->id,
                ];

                if ($request['action'] === 'edit') {
                    CRUD::update($rolesArr, config('system_config.models.user_role'), 'user_roles', 'id', $request['id']);
                } else {
                    CRUD::create($rolesArr, config('system_config.models.user_role'), 'user_roles');
                }

                if (count($request['permissions'])) {
                    foreach ($request['permissions'] as $key => $permissions) {
                        if (!is_null($permissions)) {
                            foreach ($permissions as $k => $permission) {
                                $permissionsArr[$key . '_' . $k] = [
                                    'user_id' => $user['data']->id,
                                    'page_id' => $key,
                                    'function' => $permission,
                                ];

                            }
                        }
                    }
                }

                if (count($permissionsArr)) {
                    $searchValues =[];
                    foreach ($permissionsArr as $key => $value) {
                        $searchValues = [
                            'user_id' => $user['data']->id,
                        ];
//                        (new CRUD)->delete($searchValues, 'user_permissions');
                    }

                    if(count($searchValues)){
                        foreach ($searchValues as $key => $value){
                            $delete[$key]=(new CRUD)->delete($value, 'user_permissions');
                        }
                    }

                    foreach ($permissionsArr as $key => $value) {
                        CRUD::create($value, config('system_config.models.user_permission'), 'user_permissions');
                    }


                }
                $userPermission =[];
                $user = $user['data'];
                $user['role_name'] = Role::find($user->role->role_id)->role_name;
                $user['role_id'] = Role::find($user->role->role_id)->id;
                if (!is_null($user->permissions)) {
                    $menuItems = MenuItem::all();

                    foreach ($menuItems as $k => $menu) {
                       $userPermission[$menu->id]['create'] = false;
                        $userPermission[$menu->id]['update'] = false;
                        $userPermission[$menu->id]['delete'] = false;
                        $userPermission[$menu->id]['read'] = false;
                        if (count($user->permissions)) {
                            foreach ($user->permissions as $p => $permission) {
                                if ($menu->id == $permission->page_id) {
                                    if ($permission->function === 'create') {
                                        $userPermission[$menu->id]['create'] = true;
                                    }
                                    if ($permission->function === 'update') {
                                        $userPermission[$menu->id]['update'] = true;
                                    }
                                    if ($permission->function === 'read') {
                                        $userPermission[$menu->id]['read'] = true;
                                    }
                                    if ($permission->function === 'delete') {
                                        $userPermission[$menu->id]['delete'] = true;
                                    }
                                }
                            }
                        }
                    }

                }
                $user['selected_permissions'] = $userPermission;
                return ActionResponse::success('User created successfully.', $user);
            }

        }

        if ($request['action'] === '_user_edit') {
            $data = [];
            $user =DataStorage::dataByID($request['id'], config('system_config.models.user'));
            $data['form'] = $user;
            $data['form'] = DataStorage::dataByID($request['id'], config('system_config.models.user'));


            $userPermission =[];

            $data['form']['role_name'] = Role::find($user->role->role_id)->role_name;
            $data['form']['role_id'] = Role::find($user->role->role_id)->id;
            if (!is_null($user->permissions)) {
                $menuItems = MenuItem::all();

                foreach ($menuItems as $k => $menu) {
                    $userPermission[$menu->id]['create'] = false;
                    $userPermission[$menu->id]['update'] = false;
                    $userPermission[$menu->id]['delete'] = false;
                    $userPermission[$menu->id]['read'] = false;
                    if (count($user->permissions)) {
                        foreach ($user->permissions as $p => $permission) {
                            if ($menu->id == $permission->page_id) {
                                if ($permission->function === 'create') {
                                    $userPermission[$menu->id]['create'] = true;
                                }
                                if ($permission->function === 'update') {
                                    $userPermission[$menu->id]['update'] = true;
                                }
                                if ($permission->function === 'read') {
                                    $userPermission[$menu->id]['read'] = true;
                                }
                                if ($permission->function === 'delete') {
                                    $userPermission[$menu->id]['delete'] = true;
                                }
                            }
                        }
                    }
                }

            }
            $data['form']['selected_permissions'] = $userPermission;
            return ActionResponse::success('User successfully retrieved',$data);

        }
    }

    public function user(Request $request)
    {
        if ($request['action'] === '_user_edit') {
            $data = [];
            $data['form'] = DataStorage::dataByID($request['i_id'], config('system_config.models.user'));
            return Inertia::render('Admin/Users/Index', compact('data'));
        }

    }
}
