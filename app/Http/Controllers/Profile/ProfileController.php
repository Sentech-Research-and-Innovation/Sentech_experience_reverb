<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

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

    // public function uploadProfileImage(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    //     ]);
    
    //     // store the file in storage/app/public/profile_images
    //     $path = $request->file('file')->store('profile_images', 'public');

    //     // $user = auth()->user();
    //     // // delete old profile photo if it exists
    //     // if ($user->profile_photo_path && \Storage::disk('public')->exists($user->profile_photo_path)) {
    //     //     \Storage::disk('public')->delete($user->profile_photo_path);
    //     // }
    
    //     // update the DB column
    //     $user = auth()->user();
    //     $user->update(['profile_photo_path' => $path]);
    //     $user->save();
 
    //     return response()->json([
    //         'message' => 'Profile image uploaded successfully',
    //         'url'    => $user->profile_photo_url, 
    //     ]);
    // }
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $user = auth()->user();
        
        // Delete old profile photo if it exists
        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
    
        // Store the new file
        $path = $request->file('file')->store('profile_images', 'public');
    
        // Update user record - use the correct column name
        $user->profile_photo_path = $path;
        $user->save();
    
        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'url' => $user->profile_photo_url, // This will use your accessor
        ]);
    }


    public function deleteProfileImage()
    {
        $user = auth()->user();

        if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->update(['profile_photo_path' => null]);

        return response()->json([
            'message' => 'Profile image deleted successfully',
        ]);
    }
}
