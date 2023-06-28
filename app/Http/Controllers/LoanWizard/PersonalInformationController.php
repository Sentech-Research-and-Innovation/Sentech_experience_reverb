<?php

namespace App\Http\Controllers\LoanWizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePersonalInformationRequest;
use App\Http\Requests\UpdatePersonalInformationRequest;
use App\Models\LoanWizard\PersonalDetail;
use App\Models\User;
use App\Models\Address\Address;

class PersonalInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonalInformationRequest $request)
    {


        $user = User::where('email', $request->email)->first();

        if(!isset($user->id)) {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name." ".$request->last_name;
            $user->email = $request->email;
            $user->email = $request->email;
            $user->save();
        }

        $homeAddress = new Address();
        $homeAddress->address_type = 'application_address';
        $homeAddress->street_number =  $request->street_number;
        $homeAddress->street_name =  $request->street_name;
        $homeAddress->addr_complex_unit =  $request->addr_complex_unit;
        $homeAddress->user_id =  $user->id;

        $homeAddress->save();


        $personalInformation = new PersonalDetail;
        $personalInformation->user_id = $user->id;
        $personalInformation->address_id = $homeAddress->id;
        $personalInformation->application_id = $request->application_id;

        $personalInformation->save();



        return response()
            ->json([
                'success' => true,
                'personalInformation' => $personalInformation,
                'user' => $user,
                'homeAddress' => $homeAddress
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(PersonalDetail $personalInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonalDetail $personalInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalInformationRequest $request, PersonalDetail $personalInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonalDetail $personalInformation)
    {
        //
    }
}
