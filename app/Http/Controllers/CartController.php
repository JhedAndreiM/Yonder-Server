<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'product_id' => 'required|exists:product,product_id',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|min:0',
            'voucher' => 'nullable|numeric|min:0',
            'action_type' => 'required|in:cart,buy_now'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        dd($e->errors()); 
    }

    //bali eto kukunin niya yung row ng product_id :)
    $product = Product::findOrFail($validated['product_id']);

    $cartItemId = DB::table('cart_items')->insert([
        'user_id' => Auth::id(),
        'product_id' => $request['product_id'],
        'seller_id' => $product->user_id,
        'quantity' => $request['quantity'],
        'unit_price' => $request['unit_price'],
        'voucher_applied' => $request['voucher'] ?? 0,
        'status' => $request['action_type'] === 'buy_now' ? 'pending' : 'in_cart',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    if (!empty($validated['voucher_id'])) {
        $voucher = Voucher::where('id', $validated['voucher_id'])
            ->where('user_id', Auth::id())
            ->where('status', 'available')
            ->first();

        if ($voucher) {
            $voucher->update([
                'status' => 'in_cart',
                'cart_item_id' => $cartItemId,
            ]);
        }
    }
    return redirect()->back()->with('success', 'Item added!');
}
}
