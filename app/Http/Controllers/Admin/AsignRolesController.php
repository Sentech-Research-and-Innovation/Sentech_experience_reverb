<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;



class AsignRolesController extends Controller
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
        //     $users = User::where('company_id', $this->company->id)->whereNot('id', auth()->user()->id)->with('roles')->get();
        $users = User::where('company_id', $this->company->id)->with('roles')->get();
        $userAgent = request()->header('User-Agent-type');

        if ($userAgent == 'X-Mobile-Device') {
            return request()->json(200, $users);
        }
        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function show(User $userId)
    {
        //  $userRole = Role::where('id', $userId)->with('roles')->get();
        $role = $userId->roles;

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


        $message = "Changed " . $user->first_name . " " . $user->last_name . "'s role from " . $roleName[0] . " To " . $roleByname->name;
        $this->StoreActivity($message);

        return request()->json([], 200);
    }
}
