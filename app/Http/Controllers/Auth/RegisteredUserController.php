<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Company;
use Spatie\Permission\Models\Role;



class RegisteredUserController extends Controller
{


    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'firstName' => 'required|max:55',
            'lastName' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'position' => 'required|max:55',
            'companyName' => 'required|max:55',
            'phoneNumber' => 'required|regex:/(0)[0-9]{9}/|max:10'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $company = Company::create([
            "company_name" =>  $requestData['companyName'],
        ]);

        $password = Hash::make(Str::random(16));

        $user = User::create(
            [
                'first_name' => $requestData['firstName'],
                'last_name' => $requestData['lastName'],
                'email' => $requestData['email'],
                'phoneNumber' => $requestData['phoneNumber'],
                'position' => $requestData['position'],
                'password' =>  $password,
                'company_id' => $company->id
            ]
        );

        $company->contactPerson()->associate($user);
        $company->save();

        $role = Role::create(['guard_name' => $company->company_name, 'name' => "Super Admin", 'company_id' => $company->id]);
        $user->assignRole("Super Admin");
        $this->StoreNotification($company->id, 1);
        return response(['status' => true, 'message' => 'User successfully register.'], 200);
    }
}
