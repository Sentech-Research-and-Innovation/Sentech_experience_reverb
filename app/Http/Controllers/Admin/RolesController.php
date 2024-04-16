<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermisionsResources;
use App\Models\User;

class RolesController extends Controller
{
    protected $company;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->company = auth()->user()->company;
            return $next($request);
        });
    }

    public function index()
    {

        $roles = Role::where('company_id', $this->company->id)->orderBy('id', 'Desc')->get();

        $userAgent = request()->header('User-Agent-type');

        if ($userAgent == 'X-Mobile-Device') {
            return request()->json(200, $roles);
        }

        return Inertia::render('Admin/Roles/Index', compact('roles'));
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:4',
        ]);
        $role = Role::create(['name' => request()->name . "_" . $this->company->id, 'company_id' => $this->company->id, 'guard_name' => 'web']);
        foreach (request()->permissions as $perm) {
            if ($role->hasPermissionTo($perm)) {
                return request()->json(['message' => "permission already exists"], 422);
            } else {
                $role->givePermissionTo($perm);
            }
        }

        $message = "Created a role " . request()->name;
        $this->StoreActivity($message);
        return request()->json([], 200);
    }

    public function show()
    {
        // return $rolesPermissons = Role::findByName(request()->name)->permissions;

        $rolesPermissons =  Role::where('name', request()->name)
            ->where('company_id', $this->company->id)
            ->with('permissions')
            ->first();

        $permissions = $rolesPermissons->permissions->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
            ];
        });

        return response()->json($permissions, 200);
    }

    public function update()
    {
        $permissions = request()->permissions;
        $roleName = request()->roleName;

        $role = Role::where('name', $roleName)->first();

        // Assign new permissions from the request
        foreach ($permissions as $perm) {
            $hasNoPermission = !$role->hasPermissionTo($perm);

            if ($hasNoPermission) {
                $role->givePermissionTo($perm);
            }
        }

        // Revoke permissions that are not in the request
        foreach ($role->permissions as $permission) {
            if (!in_array($permission->name, $permissions)) {
                $role->revokePermissionTo($permission);
            }
        }
        $message = "Updated permissions for role " . $roleName;
        $this->StoreActivity($message);
        return request()->json([], 200);
    }


    public function delete()
    {
        $roles_has_users = User::with("roles")->whereHas("roles", function ($q) {
            $q->where("id", request()->roleId);
        })->pluck("first_name");

        if (count($roles_has_users) > 0) {
            return response()->json($roles_has_users, 401);
        } else {
            $role = Role::find(request()->roleId)->first();
            Role::find(request()->roleId)->delete();

            $message = "Deleted role " . $role;
            $this->StoreActivity($message);
            return response()->json([], 200);
        }
    }

    public function getRoles()
    {
        $roles = Role::where('company_id', $this->company->id)->orderBy('id', 'Desc')->get();
        return request()->json(200, $roles);
    }
}
