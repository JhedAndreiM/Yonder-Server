<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            ->where('status', 'in_cart')
            ->first();

        if ($existingCartItem) {
            return redirect()->back()->with('Failed', 'Item already in Cart!');
        } else {
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
                } elseif ($status === 'pending') {
                    DB::table('vouchers')->where('id', $voucher_id)->update([
                        'status' => 'pending',
                        'cart_item_id' => $cartId,
                    ]);
                }
            }
            return redirect()->back()->with('success', 'Item added!');
        }
    }

    public function checkoutAll()
    {
        $userId = Auth::id();

        // Get all 'in_cart' items for this user
        $cartItems = DB::table('cart_items')
            ->where('user_id', $userId)
            ->where('status', 'in_cart')
            ->get();

        foreach ($cartItems as $item) {
            // Update the status to 'pending'
            DB::table('cart_items')
                ->where('id', $item->id)
                ->update([
                    'status' => 'pending',
                    'updated_at' => now(),
                ]);

            // Update voucher status if there's one linked
            if ($item->voucher_applied && isset($item->voucher_id)) {
                DB::table('vouchers')->where('id', $item->voucher_id)->update([
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->back()->with('success', 'All items checked out successfully!');
    }

    public function showCart()
    {
        $userId = Auth::id();
        // bali combine lang to ng 2 table (product and cart_item).
        // kunin nila yung same na product_id from cart_item and product_id from product
        $cartItems = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->where('cart_items.user_id', $userId)
            ->where('cart_items.status', '=', 'in_cart')
            ->select(
                'cart_items.id as cart_id',
                'cart_items.quantity',
                'cart_items.unit_price',
                'cart_items.product_id',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied'
            )
            ->get();


        $totalItems = $cartItems->sum('quantity');

        $totalAmount = $cartItems->reduce(function ($carry, $item) {
            return $carry + (($item->unit_price * $item->quantity) - $item->voucher_applied);
        }, 0);
        return view('addToCart', compact('cartItems', 'totalItems', 'totalAmount'));
    }

    public function destroy($id)
    {
        $deleted = DB::table('cart_items')
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function update($id)
    {
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'pending',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Item Bought.');
    }

    public function getAllNotCartItems(Request $request)
    {
        $userId = Auth::id();
        $filters = $request->get('filter');
        $query = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->join('users', 'cart_items.seller_id', '=', 'users.id')
            ->join('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
            ->where('cart_items.user_id', $userId)
            ->select(
                'cart_items.id as cart_id',
                'cart_items.quantity',
                'cart_items.seller_id',
                'cart_items.buyer_response',
                'cart_items.seller_response',
                'cart_items.status',
                'cart_items.unit_price',
                'cart_items.product_id',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as seller_name',
                'buyers.id as buyer_id'
            );
        if ($filters == "all" || $filters == null) {
            $query->where('cart_items.status', '!=', 'in_cart');
        } else {
            $query->where('cart_items.status', $filters);
        }
        $items = $query->get();

        // $querySeller = DB::table('cart_items')
        // ->join('users', 'cart_items.seller_id', '=', 'users.id')
        // ->where('cart_items.')
        // bali need to if ajax yung tatawag kasi if hindi mo to nilagay, ididsplay niya buong page imbis na cards(nakuha sa query)
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters'))->render();
        }
        return view('profile', compact('items', 'filters'));
    }

    public function cancel(Request $request, $id)
    {
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now()
            ]);
        $filters = $request->input('filterValue');
        //dd($filters);
        return redirect()->route('student.profile', ['filters' => $filters])
            ->with('success', 'Item cancelled.');
    }


    public function cancelSales(Request $request, $id)
    {
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now()
            ]);
        $filters = $request->input('filterValue');
        //dd($filters);
        if (Auth::check() && Auth::user()->role === 'student') {
             return redirect()->route('student.sales', ['filters' => $filters])
            ->with('success', 'Item cancelled.');
        }
        elseif(Auth::check() && Auth::user()->role === 'organization'){
            return redirect()->route('order.page', ['filters' => $filters])
            ->with('success', 'Item cancelled.');
        }
    }
    public function getAllSales(Request $request)
    {
        $userId = Auth::id();
        $filters = $request->get('filter');
        $query = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->join('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
            ->join('users', 'cart_items.user_id', '=', 'users.id')
            ->where('cart_items.seller_id', '=', $userId)
            ->select(
                'cart_items.id as cart_id',
                'cart_items.quantity',
                'cart_items.seller_id',
                'cart_items.buyer_response',
                'cart_items.seller_response',
                'cart_items.status',
                'cart_items.unit_price',
                'cart_items.product_id',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as seller_name',
                'buyers.id as buyer_id'
            );
        if ($filters == "all" || $filters == null) {
            $query->where('cart_items.status', '!=', 'in_cart');
        } else {
            $query->where('cart_items.status', $filters);
        }
        $items = $query->get();
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters'))->render();
        }
        return view('mysales', compact('items', 'filters'));
    }

    public function confirmStudentSales(Request $request, $id)
    {
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'receive',
                'updated_at' => now()
            ]);
        $filters = $request->input('filterValue');
        if (Auth::check() && Auth::user()->role === 'student') {
             return redirect()->route('student.sales', ['filters' => $filters])
            ->with('success', 'Item cancelled.');
        }
        elseif(Auth::check() && Auth::user()->role === 'organization'){
            return redirect()->route('order.page', ['filters' => $filters])
            ->with('success', 'Item cancelled.');
        }
    }

    public function orderReceivedDelivered(Request $request, $id)
    {
        $role = $request->input('role');
        if ($role == 'buyer') {
            DB::table('cart_items')
                ->where('id', $id)
                ->update([
                    'buyer_response' => 'yes',
                    'updated_at' => now()
                ]);
            $filters = $request->input('filterValue');

            $confirm = DB::table('cart_items')
                ->where('id', $id)
                ->first();
            if ($confirm->buyer_response === 'yes' && $confirm->seller_response === 'yes') {
                DB::table('cart_items')
                    ->where('id', $id)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => now()
                    ]);
                $product = DB::table('product')->where('product_id', $confirm->product_id)->first();
                if ($product) {
                    DB::table('product')
                        ->where('product_id', $confirm->product_id)
                        ->update([
                            'stock' => max(0, $product->stock - $confirm->quantity),
                            'updated_at' => now()
                        ]);
                }
                $pbenUser = User::getPBENUser();
                $completedCount = DB::table('cart_items')
                    ->where('user_id', $confirm->user_id)
                    ->where('seller_id', $pbenUser->id)
                    ->where('status', 'completed')
                    ->count();
                if ($completedCount % 5 === 0) {
                    DB::table('vouchers')->insert([
                        'user_id' => $confirm->user_id,
                        'seller_id' => $pbenUser->id,
                        'amount' => 5,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }


            return redirect()->route('student.profile', ['filters' => $filters])
                ->with('success', 'Item received.');
        }
        if ($role == 'seller') {
            DB::table('cart_items')
                ->where('id', $id)
                ->update([
                    'seller_response' => 'yes',
                    'updated_at' => now()
                ]);
            $filters = $request->input('filterValue');

            $confirm = DB::table('cart_items')
                ->where('id', $id)
                ->first();
            if ($confirm->buyer_response === 'yes' && $confirm->seller_response === 'yes') {
                DB::table('cart_items')
                    ->where('id', $id)
                    ->update([
                        'status' => 'completed',
                        'updated_at' => now()
                    ]);
                $product = DB::table('product')->where('product_id', $confirm->product_id)->first();
                if ($product) {
                    DB::table('product')
                        ->where('product_id', $confirm->product_id)
                        ->update([
                            'stock' => max(0, $product->stock - $confirm->quantity),
                            'updated_at' => now()
                        ]);
                }
                $pbenUser = User::getPBENUser();
                $completedCount = DB::table('cart_items')
                    ->where('user_id', $confirm->user_id)
                    ->where('seller_id', $pbenUser->id)
                    ->where('status', 'completed')
                    ->count();
                if ($completedCount % 5 === 0) {
                    DB::table('vouchers')->insert([
                        'user_id' => $confirm->user_id,
                        'seller_id' => $pbenUser->id,
                        'amount' => 5,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }


            
            return redirect()->route('student.sales', ['filters' => $filters])
                ->with('success', 'Item delivered.');
        }
    }

    public function updateSeller(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:product,product_id',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }
        $product = Product::find($request->product_id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        if ($request->hasFile('images')) {
            // Step 1: Delete old images
            if ($product->image_path) {
                $oldImages = explode(',', $product->image_path);
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('images/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            // Step 2: Save new images
            $filenames = [];
            $counter = 0;
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $filename = time() . '_' . $counter . '_' . $originalName;
                $image->move(public_path('images'), $filename);
                $filenames[] = $filename;
                $counter++;
            }

            // Step 3: Update product record
            $product->image_path = implode(',', $filenames);
        }

        $product->save();

        //return back()->with('success', 'Product updated successfully with images.');
        return redirect()->route('listing.seller');
    }
}
