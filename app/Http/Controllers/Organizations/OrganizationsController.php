<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use App\Models\Notification;



class OrganizationsController extends Controller
{

    public function approved()
    {
        $companies = Company::with('contactPerson')->where('active', true)->where('companyType', 'normal_company')->OrderBy('id', 'DESC')->get();
        return Inertia::render('Companies/Approved', compact('companies'));
    }

    public function request()
    {
        $companies = Company::with('contactPerson')->where('active', false)->where('companyType', 'normal_company')->OrderBy('id', 'DESC')->get();
        return Inertia::render('Companies/Requests', compact('companies'));
    }


    public function approveCompany($company_id, Request $request)
    {

        $status = Password::sendResetLink(
            $request->only('email'),

        );


        company::where('id', $company_id)->update(['active' => true]);

        Notification::whereJsonContains('model_ids', ['from_compay_id' => intval($company_id)])->update(['active' => false]);
        return response()->json([
            'status' => true,
            'message' => 'link sent',
        ], 200);
    }

    public function declineCompany()
    {
    }

    public function create(CreateUserRequest $request)
    {
        $data = $request->validated();

        $company = Company::create([
            "company_name" => request()->company_name
        ]);

        return $this->createContactPerson($company, $data);
    }

    public function createContactPerson($company, $data)
    {

        $user = User::create([
            "name" => $data['first_name'] . ' ' . $data['last_name'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            "phoneNumber" => $data['phoneNumber'],
            "position" => $data['position'],
            'password' => Hash::make('password'),
            'company_id' => $company->id
        ]);

        $company->contactPerson()->associate($user);
        $company->save();


        $role = Role::create(['guard_name' => $company->company_name, 'name' => "Super Admin", 'company_id' => $company->id]);
        $user->assignRole("Super Admin");

        return $user;
    }
}
