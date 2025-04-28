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
            'voucher_id' => 'nullable|numeric|min:0',
            'voucher_amount' => 'nullable|numeric|min:0',
            'action_type' => 'required|in:cart,buy_now'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        dd($e->errors()); 
    }
    

    //bali eto kukunin niya yung row ng product_id :)
    $product = Product::findOrFail($validated['product_id']);
    // check yung status ng buy order if add to cart ba or direct buy 
    $status = $request['action_type'] === 'buy_now' ? 'pending' : 'in_cart';

    $existingCartItem  = DB::table('cart_items')
        ->where('user_id', Auth::id())
        ->where('product_id', $request['product_id'])
        ->first();

    if($existingCartItem){
        return redirect()->back()->with('Failed', 'Item already in Cart!');
    }
    else{
        // insert sa cart_items
        $cartId = DB::table('cart_items')->insertGetId([
            'user_id' => Auth::id(),
            'product_id' => $request['product_id'],
            'seller_id' => $product->user_id,
            'quantity' => $request['quantity'],
            'unit_price' => $request['unit_price'],
            'voucher_applied' => $request['voucher_amount'] ?? 0,
            'status' => $request['action_type'] === 'buy_now' ? 'pending' : 'in_cart',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
    $voucher_id = $request['voucher_id'];
    $voucher = DB::table('vouchers')->where('id', $voucher_id)->first();
        if ($voucher) {
        if ($status === 'in_cart') {
            DB::table('vouchers')->where('id', $voucher_id)->update([
                'status' => 'in_cart', 
                'cart_item_id' => $cartId,
            ]);
        }
        elseif ($status=== 'pending') {
            DB::table('vouchers')->where('id', $voucher_id)->update([
                'status' => 'pending', 
                'cart_item_id' => $cartId,
            ]);
        }
    }
    return redirect()->back()->with('success', 'Item added!');
    }
    
    


    
}


    public function showCart(){
        $userId = Auth::id();
        // bali combine lang to ng 2 table (product and cart_item).
        // kunin nila yung same na product_id from cart_item and product_id from product
        $cartItems = DB::table('cart_items')
        ->join('product', 'cart_items.product_id', '=', 'product.product_id') 
        ->where('cart_items.user_id', $userId)
        ->select(
            'cart_items.id as cart_id', 
            'cart_items.quantity',
            'cart_items.unit_price',
            'cart_items.product_id', 
            'product.name as product_name', 
            'product.image_path',
            'product.description'
        )
        ->get();
        return view('addToCart', compact('cartItems'));
    }
}
