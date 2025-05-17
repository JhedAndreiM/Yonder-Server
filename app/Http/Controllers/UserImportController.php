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
                
                
                // Create new user
                User::create([
                    'name' => $row[1],         
                    'middle_name' => $row[2],   
                    'last_name' => $row[3],     
                    'gender' => $row[4],        
                    'email' => $row[5],          
                    'phone_number' => $row[6],  
                    'password' => Hash::make($row[0]),
                    'role' => 'student',         
                    'active_status' => 0,
                    'messenger_color' => '#2180f3',
                    'dark_mode' => 0,
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
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Find the user and update the password
        DB::table('users')
        ->where('id', Auth::id())
        ->update(['password' => Hash::make($request->password)]);

        return back()->with('successfull', 'Password updated successfully!')->with('active_tab', 'account');

    }
}
