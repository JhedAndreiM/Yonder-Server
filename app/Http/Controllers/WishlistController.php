<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggleWishlist(Request $request)
    {
    $productId = $request->product_id;
    $userId = Auth::id();

    // Check if the wishlist entry exists
    $wishlist = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

    if ($wishlist) {
        // If it exists, tanggal
        $wishlist->delete();
        return response()->json(['status' => 'removed', 'product_id' => $productId]);
    } else {
        // Otherwise, add it
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        return response()->json(['status' => 'added', 'product_id' => $productId]);
    }
    }



    public function showWishlist(Request $request){
        $userId = Auth::id();
        $wishlistItems = Wishlist::with('product')->where('user_id', $userId)->get();
        return view('wishlist', compact('wishlistItems'));
    }
}
