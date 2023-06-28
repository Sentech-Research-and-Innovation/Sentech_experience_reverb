<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Web\Country;
use Inertia\Inertia;

class CountriesController extends Controller
{

    public function countries(){
        $countries = Country::all();
        return Inertia::render('Admin/Countries',compact('countries'));
    }
}
