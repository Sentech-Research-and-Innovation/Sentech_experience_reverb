<?php

namespace App\Http\Controllers\Organizations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Company;
use App\Models\User;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Hash;

class OrganizationsController extends Controller
{
    public function index()
    {
        $companies = Company::with('contactPerson')->get();
        return Inertia::render('Companies/Index', compact('companies'));
    }

    public function create(CreateUserRequest $request)
    {
        $company = Company::create([
            "company_name" => request()->company_name
        ]);

        return $this->createContactPerson($request, $company);
    }

    public function createContactPerson($request, $company)
    {
        $data = $request->validated();

        $user = User::create([
            "name" => $data['first_name'] . ' ' . $data['last_name'],
            "first_name" => $data['first_name'],
            "last_name" => $data['last_name'],
            "email" => $data['email'],
            'password' => Hash::make('password'), // Replace with your desired default password
            'company_id' => $company->id
        ]);

        $company->contactPerson()->associate($user);
        $company->save();

        return $user;
    }
}
