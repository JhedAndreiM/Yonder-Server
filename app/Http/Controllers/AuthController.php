<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function selectRole(){
        $studentInfo = Auth::user();

        if(auth()->user()){
            if($studentInfo->role=='student'){
                return redirect()->route('student.dashboard');
            }
            elseif($studentInfo->role=='organization'){
                return redirect()->route('organization.dashboard');
            }
            elseif($studentInfo->role=='admin'){
                return redirect()->route('admin.dashboard');
            }
        }
        return view('select-role');
    }

    public function showLoginForm(){
        if(auth()->check()){
            $user = auth()->user();
            if($user->role==='student'){
                return redirect()->route('student.dashboard');
            } elseif($user->role==='organization'){
                return redirect()->route('organization.dashboard');
            } elseif($user->role==='admin'){
                return redirect()->route('admin.dashboard');
            }
        }
        return view('login');
    }

    public function login(Request $request){
         $request->validate([
        'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@bpsu\.edu\.ph$/']
    ], [
        'email.regex' => 'Only BPSU email addresses are allowed.'
    ]);
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['error' => 'Email and password do not match.']);
        }
        $user = Auth::user();
        
        // Check if user needs to change password (for new accounts with default password)
        if (!$user->password_changed && Hash::check('12345678', $user->password)) {
            // Redirect to profile settings with force password change flag
            return redirect()->route('account.page')->with('force_password_change', true);
        }

        // Redirect by role automatically
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'organization') {
            return redirect()->route('organization.dashboard');
        } else {
            return redirect()->route('student.dashboard');
        }
    }

    public function handleModalSubmit(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'middle_name'  => ['nullable', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@bpsu\.edu\.ph$/', 'unique:users,email'],
            'confirmemail' => ['required', 'same:email'],
        ], [
            'name.required'        => 'First name is required.',
            'last_name.required'   => 'Last name is required.',
            'email.regex'          => 'Only BPSU email addresses are allowed.',
            'email.unique'         => 'This email already has an account.',
            'confirmemail.same'    => 'Emails must match.'
        ]);

        $user = User::create([
            'name'        => $request->name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'password'    => Hash::make('12345678'), // default password
            'role'        => 'student', // only if you have a role column
            'password_changed' => false, // new users need to change password
        ]);

        return redirect()->back()->with('success', 'Account created! Use your email and the default password: 12345678');
    }
}

