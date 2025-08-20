<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



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
        $requestData = $request->all();
    
        $validator = Validator::make($requestData, [
            'first_name'   => 'required|max:55',
            'last_name'    => 'required|max:55',
            'phoneNumber'  => 'required|regex:/(0)[0-9]{9}/|max:10'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
    
        $user = $request->user();
    
        // Only update the fields you want (prevents mass assignment issues)
        $user->update([
            'first_name'  => $request->input('first_name'),
            'last_name'   => $request->input('last_name'),
            'phoneNumber' => $request->input('phoneNumber'),
        ]);
    
        return response()->json([
            'message' => 'Profile updated successfully'
        ], 200);
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

    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('file')->store('profile_images', 'public');

        $user = auth()->user();
        $user->profile_photo_path = $path;
        $user->save();

        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'path' => asset('storage/' . $path),
        ]);
    }

    public function deleteProfileImage()
    {
        $user = auth()->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        return response()->json([
            'message' => 'Profile image deleted successfully',
        ]);
    }
}
