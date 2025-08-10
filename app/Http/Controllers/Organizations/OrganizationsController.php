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
use App\Notifications\AccountAprrovalNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestDeclinedMail;



class OrganizationsController extends Controller
{

    public function approved()
    {
        $companies = Company::with('contactPerson')->where('approved', 2)->where('companyType', 'normal_company')->OrderBy('id', 'DESC')->get();

        $userAgent = request()->header('User-Agent-type');

        if ($userAgent == 'X-Mobile-Device') {
            return request()->json(200, $companies);
        }
        return Inertia::render('Companies/Approved', compact('companies'));
    }

    public function request()
    {
        $companies = Company::with('contactPerson')->where('approved', 0)->where('companyType', 'normal_company')->OrderBy('id', 'DESC')->get();

        $userAgent = request()->header('User-Agent-type');

        if ($userAgent == 'X-Mobile-Device') {
            return request()->json(200, $companies);
        }
        return Inertia::render('Companies/Requests', compact('companies'));
    }

    public function pending()
    {
        $companies = Company::with('contactPerson')->where('approved', 1)->where('companyType', 'normal_company')->OrderBy('id', 'DESC')->get();

        $userAgent = request()->header('User-Agent-type');

        if ($userAgent == 'X-Mobile-Device') {
            return request()->json(200, $companies);
        }
        return Inertia::render('Companies/Pending', compact('companies'));
    }



    public function approveCompany($company_id, Request $request)
    {

        // $status = Password::sendResetLink(
        //     $request->only('email'),

        // );

        company::where('id', $company_id)->update(['active' => true, 'approved' => 1]);


        $company = company::find($company_id);

        //$user = User::find($company->contact_person_id);

        $status = Password::sendResetLink(
            $request->only('email'),

        );


        Notification::whereJsonContains('model_ids', ['from_compay_id' => intval($company_id)])->update(['active' => false]);

        $message = "Approved a company " . $company->company_name;
        $this->StoreActivity($message);

        return response()->json([
            'status' => true,
            'message' => 'link sent',
        ], 200);
    }

    
     public function declineCompany($company_id, Request $request)
    {
        $message = $request->input('message');
        // Find the company
        $company = Company::find($company_id);
        if (!$company) {
            return response()->json(['error' => 'Company not found'], 404);
        }

        // Get the contact person (admin user)
        $adminUser = $company->contactPerson;

        // Deactivate related notifications
        Notification::whereJsonContains('model_ids', ['from_compay_id' => intval($company_id)])->update(['active' => false]);

        // Log the activity
        $this->StoreActivity("Declined company request: " . $company->company_name);

        // Send email to company admin
        if ($adminUser) {
            
            Mail::to($adminUser->email)->send(new RequestDeclinedMail($adminUser->first_name, $message));
            Log::info("Email sent to the sure ");
            // Delete user
            $adminUser->delete();
        Log::info("user deleted ");
        }

        return response()->json(['status' => true, 'message' => 'Company registration request deleted.']);
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

        $message = "Created a company " . $company->company_name;
        $this->StoreActivity($message);

        return $user;
    }
}
