<?php

namespace App\Http\Controllers\LoanWizard;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdministrationOrderRequest;
use App\Http\Requests\UpdateAdministrationOrderRequest;
use App\Models\LoanWizard\AdministrationOrder;

class AdministrationOrderController extends Controller
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
    public function store(StoreAdministrationOrderRequest $request)
    {
        $administrationOrder = new AdministrationOrder;
        $administrationOrder->application_id = $request->application_id;
        $administrationOrder->company_name = $request->company_name;
        $administrationOrder->date_of_order = $request->date_of_order;
        $administrationOrder->instalment_value = $request->instalment_value;
        $administrationOrder->paid_by = $request->paid_by;
        $administrationOrder->attorney_name = $request->attorney_name;

        $administrationOrder->save();

          
        return response()
            ->json([
                'success' => true,
                'administrationOrder' => $administrationOrder,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AdministrationOrder $administrationOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdministrationOrder $administrationOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdministrationOrderRequest $request, AdministrationOrder $administrationOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdministrationOrder $administrationOrder)
    {
        //
    }
}
