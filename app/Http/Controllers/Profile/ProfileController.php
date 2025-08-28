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
        // This will automatically include profile_photo_url via the accessor
        $user = User::where('id', auth()->user()->id)
                    ->with('company', 'roles')
                    ->first();
    //     \Log::info('DEBUG PROFILE PHOTO INFO:');
    // \Log::info('Profile photo path: ' . $user->profile_photo_path);
    // \Log::info('APP_URL: ' . config('app.url'));
    // \Log::info('Generated URL: ' . $user->profile_photo_url);
    // \Log::info('Storage exists: ' . (\Storage::disk('public')->exists($user->profile_photo_path) ? 'YES' : 'NO'));
        
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
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = auth()->user();
    
        // Create the directory if it doesn’t exist
        if (!file_exists(public_path('profile_images'))) {
            mkdir(public_path('profile_images'), 0777, true);
        }
    
        // Generate unique filename
        $filename = uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
    
        // Move the uploaded file into /public/profile_images
        $request->file('file')->move(public_path('profile_images'), $filename);
    
        // Save relative path in DB (not full URL)
        $user->profile_photo_path = 'profile_images/' . $filename;
        $user->save();
    
        return response()->json([
            'message' => 'Profile photo uploaded successfully.',
            'profile_photo_url' => asset($user->profile_photo_path),
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
