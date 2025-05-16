<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            // 2. Load the Excel file
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            
            // 3. Get the first worksheet
            $worksheet = $spreadsheet->getActiveSheet();
            
            // 4. Get all rows as an array
            $rows = $worksheet->toArray();
            
            // 5. Remove the header row
            $headers = array_shift($rows);
            
            // 6. Process each row
            foreach ($rows as $row) {
                // Create password from birthday (assuming birthday is in column 7)
                $birthday = $row[6]; // Adjust index based on your Excel column
                $password = date('m-d-y', strtotime($birthday));
                
                // Create new user
                User::create([
                    'name' => $row[0],          // First column
                    'middle_name' => $row[1],    // Second column
                    'last_name' => $row[2],      // Third column
                    'gender' => $row[3],         // Fourth column
                    'email' => $row[4],          // Fifth column
                    'phone_number' => $row[5],   // Sixth column
                    'password' => Hash::make($password),
                    'role' => 'student',         // Default role
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
}
