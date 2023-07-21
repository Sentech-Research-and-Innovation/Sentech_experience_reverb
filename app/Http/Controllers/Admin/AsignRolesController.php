<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;



class AsignRolesController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();

        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function show(User $userId)
    {
        //  $userRole = Role::where('id', $userId)->with('roles')->get();
        return $role = $userId->roles;

        $userRole = Role::where('id', $role->role_id)->get();

        return $userRole;
    }

    public function update($userId)
    {
        //return request();

        $user = User::findOrFail($userId);

        $roleName = $user->getRoleNames();

        $user->removeRole($roleName[0]);

        $roleByname = Role::findByName(request()->roleName);

        $user->assignRole($roleByname->id);

        return request()->json([], 200);
    }
}
