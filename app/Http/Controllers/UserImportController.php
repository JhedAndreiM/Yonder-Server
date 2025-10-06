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
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();

            $rows = $worksheet->toArray(null, true, true, true);

            // Get header row (1st row)
            $headers = array_shift($rows);

            // Convert headers to lowercase for flexible matching
            $headers = array_map(fn($h) => strtolower(trim($h)), $headers);

            foreach ($rows as $row) {
                // Map row values by header name
                $data = array_combine($headers, $row);

                $birthdayCell = $data['birthday'] ?? null;
                $passwordPlain = 'BPSU12345678';

                if (!empty($birthdayCell)) {
                    if (is_numeric($birthdayCell)) {
                        try {
                            $dt = PhpSpreadsheetDate::excelToDateTimeObject($birthdayCell);
                            $passwordPlain = $dt->format('d-m-Y');
                        } catch (\Exception $e) {}
                    } else {
                        try {
                            $dt = Carbon::parse($birthdayCell);
                            $passwordPlain = $dt->format('d-m-Y');
                        } catch (\Exception $e) {}
                    }
                }

                User::create([
                    'name' => $data['name'] ?? null,
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    'email' => $data['email'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
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
