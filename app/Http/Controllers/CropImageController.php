<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CropImageController extends Controller
{
    public function update(Request $request){
        $user = Auth::user();

        $request->validate([
            'gender' => 'required|in:Male,Female',
            'phone_number' => 'nullable|string|max:11',
            'cropped_avatar' => 'nullable|string',
            'cropped_qr' => 'nullable|string',
        ]);
        
        if($request->cropped_avatar){
            $this->processImage($request->cropped_avatar, 'avatar', $user);
        }
        if($request->cropped_qr){
            $this->processImage($request->cropped_qr, 'qr_image', $user);
        }
        if ($request->gender) {
            $user->gender = $request->gender;
        }
        $user->save();
        if($request->phone_number !== $user->phone_number){
            session(['pending_phoneNumber' => $request->phone_number]);
            $cooldownMinutes = 10;
            $lastOtp = $user->last_otp_sent_at ? Carbon::parse($user->last_otp_sent_at) : null;
            if($lastOtp && $lastOtp->diffInMinutes(now()) < $cooldownMinutes){
                 $remaining = $cooldownMinutes - floor($lastOtp->diffInMinutes(now()));
                  return back()->with('otp_required', true)->withErrors(['otp' => "You can request a new OTP in $remaining minutes."]);
            }

            $otp = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
            session(['otp' => $otp]);
            // $smsService = app(\App\Services\IprogSmsService::class);
            // $response = $smsService->send($request->phone_number, 'Your verification code is ' . $otp);
            $user->last_otp_sent_at = now();
            $user->save();
            return back()->with('otp_required', true)->with('otp_code', $otp);
        }
        return back()->with('success', 'Profile updated successfully!');
    }

    
    public function sendSmsForOTP(Request $request)
    {   
        $user = Auth::user();
        if ($request->otp_combined === session('otp')) {
            $user->phone_number = session('pending_phoneNumber');
            $user->save();
            return back()->with('success', 'Phone Number Changed!');
        }
        return back()->with('otp_required', true)->with('otp_code', session('otp'))->withErrors(['otp' => 'Invalid OTP']);
    }

    private function processImage($base64Image, $type, $user){
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
        $extension = $matches[1];
        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
    } else {
        $extension = 'png';
    }

    $imageData = base64_decode($base64Image);

    if ($imageData === false) {
        return;
    }

    $folder = $type === 'avatar' ? 'users-avatar' : 'users-qr';
    $destinationPath = public_path('storage/' . $folder);

    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777, true);
    }

    $filename = uniqid() . '.' . $extension;
    $fullPath = $destinationPath . '/' . $filename;

    if (file_put_contents($fullPath, $imageData)) {
        if ($user->$type && file_exists(public_path($user->$type))) {
            unlink(public_path($user->$type));
        }

        $user->$type = $filename;
    }
    }





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
        elseif(Auth::user()->role==='admin'){
            return redirect()->route('accounts.pages')
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
