<?php

namespace App\Http\Controllers\LoanWizard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLoanApplicationRequest;
use App\Http\Requests\UpdateLoanApplicationRequest;
use App\Models\LoanWizard\LoanApplication;

class LoanApplicationController extends Controller
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
    public function store(StoreLoanApplicationRequest $request)
    {
        $loanApplication = new LoanApplication;
        $loanApplication->training_check = $request->training_check;
        $loanApplication->branch_id = $request->branch_id;
        $loanApplication->save();

          
        return response()
            ->json([
                'success' => true,
                'loanApplication' => $loanApplication,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanApplicationRequest $request, LoanApplication $loanApplication)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanApplication $loanApplication)
    {
        //
    }
}
