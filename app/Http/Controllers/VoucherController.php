<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        $currentUser=DB::table('users')
        ->where('id', '=', Auth::id())
        ->first();
        $userCredit = $currentUser->credits;
        $voucherList = DB::table('voucherList')->get();
        return view('vouchers', compact('voucher', 'sellerRating', 'voucherList', 'userCredit'));
    }

    public function redeemVoucher(Request $request){
        $request->validate([
            'voucherAmount' => 'required|min:1',
            'voucherCost' => 'required|min:1'
        ]);
        $currentUser=DB::table('users')
        ->where('id', '=', Auth::id())
        ->first();

        if($currentUser->credits >= $request->voucherCost){
            $pbenUser = User::getPBENUser();
            $newCredits = $currentUser->credits - $request->voucherCost;
            $currentUser=DB::table('users')
            ->where('id', '=', Auth::id())
            ->update([
                'credits' => $newCredits
            ]);
            DB::table('vouchers')->insert([
                'user_id' => Auth::id(),
                'seller_id' => $pbenUser->id,
                'amount' => $request->voucherCost,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            return back()->with('voucher_success', "Voucher Redeemed!.");
        }
        elseif($currentUser->credits < $request->voucherCost){
            return back()->with('voucher_error', "You don't have enough credits to purchase this voucher.");
        }
    }
}