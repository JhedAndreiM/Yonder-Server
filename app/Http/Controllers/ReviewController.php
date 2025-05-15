<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
{
     try{
    $request->validate([
        'item_id' => 'required|exists:product,product_id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);
    $existing = DB::table('reviews')
            ->where('product_id', $request->item_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already submitted a review for this product.');
        }
        DB::table('reviews')->insert([
        'product_id' => $request->item_id,
        'user_id' => Auth::id(),
        'rating' => $request->rating,
        'comment' => $request->comment,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('success', 'Review submitted successfully!');
    }
    catch (\Exception $e) {
        return redirect()->route('student.profile')
            ->with('error', 'Error updating user information: Fill out all fields!');
    }
}
}
