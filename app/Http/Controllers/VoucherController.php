<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function showVoucher(){
        $voucher = DB::table('vouchers')
        ->where('status', 'available')
        ->where('user_id', Auth::id())
        ->get();
        return view('vouchers', compact('voucher'));
    }
}