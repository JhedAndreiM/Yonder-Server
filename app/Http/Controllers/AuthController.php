<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function selectRole(){
        return view('select-role');
    }

    public function showLoginForm($role){
        $validRoles=['admin','organization','student'];
        if (!in_array($role, $validRoles)) {
            abort(404);
        }
        return view('login', ['role' => $role]);
    }

    public function login(Request $request){
         $request->validate([
        'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@bpsu\.edu\.ph$/']
    ], [
        'email.regex' => 'Only BPSU email addresses are allowed.'
    ]);
        $credentials = $request->only('email', 'password');
        $selectedRole = $request->input('role');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['error' => 'Email and password do not match.']);
        }

        if(Auth::attempt($credentials)){
            $user=Auth::user();
            if($selectedRole==='admin'&& $user->role !=='admin'){
                if ($user->email !== 'jacmagdato@bpsu.edu.ph'){
                    Auth::logout();
                    return back()->withErrors(['error' => 'Only the admin can access this page.']);
                }
            }
        

        if($selectedRole === 'organization' && !in_array($user->role,['organization', 'admin'])){
            Auth::logout();
            return back()->withErrors(['error' => 'You are not authorized to access organization view.']);
        }

        if ($selectedRole === 'student' && !in_array($user->role, ['student', 'organization', 'admin'])) {
            Auth::logout();
            return back()->withErrors(['error' => 'Unauthorized for student view.']);
        }

        return redirect()->route($selectedRole . '.dashboard');
    }
    return back()->withErrors(['error' => 'Invalid credentials']);
}
}

