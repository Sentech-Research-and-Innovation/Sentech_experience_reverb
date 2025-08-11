<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class ProfileController extends Controller
{
    public function index()
    {

        $user = User::where('id',  auth()->user()->id)->with('company', 'roles')->first();
        return Inertia::render('Profile/Index', compact('user'));
    }

    public function show($id)
    {
        $user = User::with('company', 'roles')->findOrFail($id);
        return Inertia::render('Profile/Index_1', compact('user'));
            
    }

    public function update(Request $request)
    {

        return Auth::user();

        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'first_name' => 'required|max:55',
            'last_name' => 'required|max:55',
            'phoneNumber' => 'required|regex:/(0)[0-9]{9}/|max:10'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        return  $user = $request->user();
        $data =  $request->toArray();

        $user->update($data);

        return request()->json([], 200);
    }



    public function updatePassword(Request $request)
    {
        $validateData = $request->validate([
            'password' => ['required', 'string', 'confirmed'],
        ]);


        $validateData['password'] = bcrypt($request['password']);

        $user = $request->user();

        $user->update($validateData);
        return request()->json([], 200);
    }
}
