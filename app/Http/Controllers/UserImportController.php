<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rules\Password;

class UserImportController extends Controller
{
    public function showForm()
    {
        return view('admin.import-users');
    }

    // Handle file upload
    public function upload(Request $request)
    {
        // 1. Validate the uploaded file
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            
            $worksheet = $spreadsheet->getActiveSheet();
            
            $rows = $worksheet->toArray();
            
            $headers = array_shift($rows);
            
            foreach ($rows as $row) {
                
                
                // Create new user
                User::create([
                    'name' => $row[0],         
                    'middle_name' => $row[1],   
                    'last_name' => $row[2],     
                    'gender' => $row[3],        
                    'email' => $row[4],          
                    'phone_number' => $row[5],  
                    'password' => Hash::make('12345678'),
                    'role' => 'student',         
                    'active_status' => 0,
                    'messenger_color' => '#2180f3',
                    'dark_mode' => 0,
                    'password_changed' => false, // imported users need to change password
                ]);
            }

            return back()->with('excel_success', 'Users imported successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
        ], [
            'new_password.confirmed' => 'The new password and confirmation do not match.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        // Decide redirect based on "force password change"
        if ($request->has('force_password_change')) {
        return redirect()->route('student.dashboard')
                         ->with('success', 'Password updated successfully!');
        }

        return redirect()->route('account.page')
                        ->with('success', 'Password updated successfully!');
    }

}
