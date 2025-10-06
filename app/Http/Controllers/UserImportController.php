<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

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

                $birthdayCell  = $row[6] ?? null;
                $passwordPlain = null;
                if (empty($birthdayCell) && $birthdayCell !== 0) {
                    $passwordPlain = 'BPSU12345678';
                } else {
                    // 2) If it's numeric, treat as Excel date serial
                    if (is_numeric($birthdayCell)) {
                        try {
                            // Convert Excel serial to PHP DateTime
                            $dt = PhpSpreadsheetDate::excelToDateTimeObject($birthdayCell);
                            $passwordPlain = $dt->format('d-m-Y'); // e.g., 06-06-2004
                        } catch (\Exception $e) {
                            // fallback if conversion fails
                            $passwordPlain = '01-01-2000';
                        }
                    } else {
                        // 3) Try parsing string dates using Carbon (covers 'YYYY-MM-DD', 'MM/DD/YYYY', etc.)
                        try {
                            // Carbon is flexible with many formats
                            $dt = Carbon::parse($birthdayCell);
                            $passwordPlain = $dt->format('d-m-Y');
                        } catch (\Exception $e) {
                            // Last attempt: if PhpSpreadsheet gave a formatted string already, try common formats
                            // or fallback to default
                            $passwordPlain = '01-01-2000';
                        }
                    }
                }
                // Create new user
                User::create([
                    'name' => $row[0] ?? null,
                    'middle_name' => $row[1] ?? null,
                    'last_name' => $row[2] ?? null,
                    'gender' => $row[3] ?? null,
                    'email' => $row[4] ?? null,
                    'phone_number' => $row[5] ?? null,
                    'password' => Hash::make($passwordPlain),
                    'role' => 'student',
                    'active_status' => 0,
                    'messenger_color' => '#2180f3',
                    'dark_mode' => 0,
                    'password_changed' => false,
                ]);
            }

            return back()->with('excel_success', 'Users imported successfully!');

        } catch (\Exception $e) {
            return back()->with('excel_success', 'Error: ' . $e->getMessage());
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
