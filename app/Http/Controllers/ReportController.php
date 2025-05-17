<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'report_id' => 'required',
        'message' => 'required|string|max:1000',
    ]);

    DB::table('reports')->insert([
        'user_id' => $validated['user_id'],
        'report_id' => $validated['report_id'],
        'message' => $validated['message'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Report submitted successfully.');
}
}
