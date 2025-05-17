<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
                $birthday = $row[6]; 
                $password = date('m-d-y', strtotime($birthday));
                
                // Create new user
                User::create([
                    'name' => $row[0],         
                    'middle_name' => $row[1],   
                    'last_name' => $row[2],     
                    'gender' => $row[3],        
                    'email' => $row[4],          
                    'phone_number' => $row[5],  
                    'password' => Hash::make($password),
                    'role' => 'student',         
                    'active_status' => 0,
                    'messenger_color' => '#2180f3',
                    'dark_mode' => 0,
                ]);
            }

            return back()->with('success', 'Users imported successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find the user and update the password
        DB::table('users')
        ->where('id', Auth::id())
        ->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!')->with('active_tab', 'account');

    }
}
