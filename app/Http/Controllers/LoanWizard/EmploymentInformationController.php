<?php

namespace App\Http\Controllers\LoanWizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmploymentInformationRequest;
use App\Http\Requests\UpdateEmploymentInformationRequest;
use App\Models\LoanWizard\EmploymentInformation;

class EmploymentInformationController extends Controller
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
    public function store(StoreEmploymentInformationRequest $request)
    {
        
        

        $employmwntInformation = new EmploymentInformation;
        $employmwntInformation->employer_name = $request->employer_name;
        $employmwntInformation->application_id = $request->application_id;
        $employmwntInformation->save();

          
        return response()
            ->json([
                'success' => true,
                'employmwntInformation' => $employmwntInformation,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(EmploymentInformation $employmentInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmploymentInformation $employmentInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmploymentInformationRequest $request, EmploymentInformation $employmentInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmploymentInformation $employmentInformation)
    {
        //
    }
}
