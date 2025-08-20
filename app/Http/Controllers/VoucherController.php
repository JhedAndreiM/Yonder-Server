<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function showVoucher(){
        $sellerId = Auth::id();
        $sellerRating = DB::table('reviews')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $sellerId)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
            ->first();
        $voucher = DB::table('vouchers')
        ->where('status', 'available')
        ->where('user_id', Auth::id())
        ->get();
        return view('vouchers', compact('voucher', 'sellerRating'));
    }
}