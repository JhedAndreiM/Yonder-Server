<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
class CropImageController extends Controller
{
    public function cropImageUploadAjax(Request $request)
{
    $imageData = $request->cropped_avatar;
    if (!$imageData || strpos($imageData, ';base64,') === false) {
        return back()->with('message', 'Invalid image data received.');
    }

    $image_parts = explode(";base64,", $imageData);
    if (count($image_parts) !== 2) {
        
        return back()->with('message', 'Corrupted image data.');
    }

    $image_base64 = base64_decode($image_parts[1]);
    $imageName = uniqid() . '.png';
    $folderPath = public_path('storage/users-avatar/');
    $imageFullPath = $folderPath . $imageName;

    if (!File::exists($folderPath)) {
        File::makeDirectory($folderPath, 0755, true);
    }

    file_put_contents($imageFullPath, $image_base64);

    $avatarPath =  $imageName;

    DB::table('users')
        ->where('id', Auth::id())
        ->update(['avatar' => $avatarPath]);

    if(Auth::user()->role==='student'){
            return redirect()->route('account.page')
            ->with('sucess', 'Avatar updated!');
        }
        elseif(Auth::user()->role==='organization'){
            return redirect()->route('accounts.page')
            ->with('sucess', 'Avatar updated!');
        }
    }

    public function deleteAvatar(){
        try {
            DB::table('users')
            ->where('id', Auth::id())
            ->update([
            'avatar' => 'avatar.png',
            'updated_at' => now(),
            ]);  
            if(Auth::user()->role==='student'){
            return redirect()->route('account.page')
            ->with('success', 'Avatar Deleted!');
            }
            elseif(Auth::user()->role==='organization'){
            return redirect()->route('accounts.page')
            ->with('success', 'Avatar Deleted!');
        }
        } catch (\Exception $e) {
            if(Auth::user()->role==='student'){
            return redirect()->route('account.page')
            ->with('message', 'Error Deleting Avatar!');
            }
            elseif(Auth::user()->role==='organization'){
            return redirect()->route('accounts.page')
            ->with('message', 'Error Deleting Avatar!');
        }
            
        }
    }


    public function updateUserInfo(Request $request)
    {
        try{
        if(Auth::user()->role==='student'){
            $validate=$request->validate([
        'firstname' => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'lastname' => 'required|string|max:255',
        'phonenumber' => 'required|digits:11', 
        'gender' => 'required|in:Male,Female',
        ]); 
        DB::table('users')
        ->where('id', Auth::id())
        ->update([
            'name' => $request->firstname,
            'middle_name' => $request->middlename,
            'last_name' => $request->lastname,
            'phone_number' => $request->phonenumber,
            'gender' => $request->gender,
        ]);
            return redirect()->route('account.page')
            ->with('success', 'User information updated successfully!');
        }
        elseif(Auth::user()->role==='organization'){
            $validate=$request->validate([
        'firstname' => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'lastname' => 'nullable|string|max:255',
        'phonenumber' => 'required|digits:11', 
        'gender' => 'required|in:Male,Female',
        ]); 
        DB::table('users')
        ->where('id', Auth::id())
        ->update([
            'name' => $request->firstname,
            'middle_name' => $request->middlename,
            'last_name' => $request->lastname,
            'phone_number' => $request->phonenumber,
            'gender' => $request->gender,
        ]);
            return redirect()->route('accounts.page')
            ->with('success', 'User information updated successfully!');
        }
        
    }
    catch (\Exception $e) {
        if(Auth::user()->role==='student'){
            return redirect()->route('account.page')
            ->with('error', 'Error updating user information: Fill out all fields!');
        }
        elseif(Auth::user()->role==='organization'){
            return redirect()->route('accounts.page')
            ->with('error', 'Error updating user information: Fill out all fields!');
        }
    }

    }
}
