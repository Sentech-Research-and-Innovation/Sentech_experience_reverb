<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

use App\Http\Requests\CreateUserRequest;



class UserController extends Controller
{

    protected $company;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->company = auth()->user()->company;
            return $next($request);
        });
    }

    public function create(CreateUserRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            "name" => $data['first_name'] . '' . $data['last_name'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            'password' => '$2y$10$D2tQCuUfUuYhCbJOSxOtf.45hNeFmjW3hUGzZMBB/CK7UBf9HlaQe',
            'company_id' =>  $this->company->id

        ]);

        $user->assignRole($data['role']);

        return response()->json([], 200);
    }
}
