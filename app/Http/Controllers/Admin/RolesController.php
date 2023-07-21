<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermisionsResources;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'Desc')->get();
        return Inertia::render('Admin/Roles/Index', compact('roles'));
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|min:4',
        ]);
        $role = Role::create(['name' => request()->name]);

        foreach (request()->permissions as $perm) {
            if ($role->hasPermissionTo($perm)) {
                return request()->json(['message' => "permission already exists"], 422);
            } else {
                $role->givePermissionTo($perm);
            }
        }
        return request()->json([], 200);
    }

    public function show()
    {
        $rolesPermissons = Role::findByName(request()->name)->permissions;

        return response()->json(new PermisionsResources($rolesPermissons));
    }

    public function update()
    {
        $permissions = request()->permissions;
        $roleName = request()->roleName;

        // Retrieve the role from the database
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

        return request()->json([], 200);
    }


    public function delete()
    {
    }

    public function getRoles()
    {
        $roles = Role::orderBy('id', 'Desc')->get();
        return request()->json(200, $roles);
    }
}
