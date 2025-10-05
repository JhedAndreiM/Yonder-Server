<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

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
                'action_type' => 'required|in:in_cart,buy_now',
                'paymentType' => 'required|in:onlinePayment,cashPayment'
            ]);
        


        //bali eto kukunin niya yung row ng product_id :)
        $product = Product::findOrFail($validated['product_id']);
        $variantData = $product->variants;
        $variantIndex = (int) $request->selected_variant;

        $selectedVariantName=isset($variantData['options'][$variantIndex])
        ? $variantData['options'][$variantIndex]
        : null;
        // check yung status ng buy order if add to cart ba or direct buy 
        $status = $request['action_type'] === 'buy_now' ? 'pending' : 'in_cart';

        // pang check ng stock ng specific variant
        if ($variantData) {
            $variantOptions = $variantData['options'];
            $variantStocks = $variantData['optionStocks'];
            $availableStock = isset($variantStocks[$variantIndex]) ? $variantStocks[$variantIndex] : 0;
        } else {
            $availableStock = $product->stock;
        }

        $existingCartItem  = DB::table('cart_items')
            ->where('user_id', Auth::id())
            ->where('product_id', $request['product_id'])
            ->where('status', 'in_cart')
            ->where('selected_variant', $selectedVariantName)
            ->first();

        if ($existingCartItem && $request['action_type']=== 'in_cart' && empty($validated['voucher_id'])) {
            $newQuantity = $existingCartItem->quantity + $request['quantity'];

            if ($newQuantity > $availableStock) {
                return redirect()->back()->with('error', 'Not enough stock. Only ' . $availableStock . ' available. Check your Cart');
            }
            DB::table('cart_items')
                ->where('id', $existingCartItem->id)
                ->update([
                    'quantity' => $newQuantity,
                    'updated_at' => now(),
                ]);

            return redirect()->back()->with('success', 'Cart updated: quantity increased.');
        } else {
            $status = $request['action_type'] === 'buy_now' ? 'pending' : 'in_cart';
            // insert sa cart_items
            $cartId = DB::table('cart_items')->insertGetId([
                'user_id' => Auth::id(),
                'product_id' => $request['product_id'],
                'seller_id' => $product->user_id,
                'quantity' => $request['quantity'],
                'unit_price' => $request['unit_price'],
                'voucher_applied' => $request['voucher_amount'] ?? 0,
                'status' => $status,
                'selected_variant' => $selectedVariantName,
                'payment_type' => $request['paymentType'],
                'created_at' => now(),
                'updated_at' => now(),
                'listing_update' => now(),
            ]);
            if ($status === 'pending') {
                $buyer = Auth::user();
                $seller = DB::table('users')->where('id', $product->user_id)->first();

                DB::table('sms_notifLogs')->insert([
                    'from_id' => $buyer->id,
                    'to_id' => $seller->id,
                    'message' => "User {$buyer->name} placed a Buy Now order for your product '{$product->name}'.",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

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
            
            if ($status === 'in_cart') {
                return redirect()->back()->with('success', 'Item added to Cart!');
            }
            elseif($status === 'pending'){
                return redirect()->back()->with('success', 'Product bought successfully!');
            }
        }
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
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
    public function checkoutSelected(Request $request)
    {
        $userId = Auth::id();
        $selectedIds = $request->input('selected_items', []);

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Please select at least one item to checkout.');
        }

        $cartItems = DB::table('cart_items')
            ->where('user_id', $userId)
            ->where('status', 'in_cart')
            ->whereIn('id', $selectedIds)
            ->get();

        foreach ($cartItems as $item) {
            // Check stock availability
            $product = DB::table('product')->where('product_id', $item->product_id)->first();

            $availableStock = $product->stock;
            if ($item->selected_variant && $product->variants) {
                $variants = json_decode($product->variants, true);
                $variantIndex = array_search($item->selected_variant, $variants['options'] ?? []);
                if ($variantIndex !== false) {
                    $availableStock = $variants['optionStocks'][$variantIndex] ?? $product->stock;
                }
            }

            if ($availableStock <= 0) {
                continue; // skip out of stock item
            }

            // Mark as pending
            DB::table('cart_items')->where('id', $item->id)->update([
                'status' => 'pending',
                'updated_at' => now(),
            ]);

            // Update voucher status if applicable
            if ($item->voucher_applied && isset($item->voucher_id)) {
                DB::table('vouchers')->where('id', $item->voucher_id)->update([
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Selected items checked out successfully!');
    }
    public function showCart()
    {
        $userId = Auth::id();
        
        $cartItems = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->where('cart_items.user_id', $userId)
            ->where('cart_items.status', '=', 'in_cart')
            ->select(
                'cart_items.id as cart_id',
                'cart_items.updated_at',
                'cart_items.quantity',
                'cart_items.unit_price',
                'cart_items.product_id',
                'cart_items.selected_variant',
                'product.variants',
                'product.name as product_name',
                'product.stock as product_stock',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied'
            )
            ->get();

        $cartItems->transform(function ($item) {
            $variantStock = null;

            if ($item->selected_variant && $item->variants) {
                $variants = json_decode($item->variants, true);

                if (!empty($variants['options']) && !empty($variants['optionStocks'])) {
                    // find index of selected variant
                    $variantIndex = array_search($item->selected_variant, $variants['options']);
                    if ($variantIndex !== false) {
                        $variantStock = $variants['optionStocks'][$variantIndex] ?? null;
                    }
                }
            }

            // attach stock value to the item
            $item->available_stock = $variantStock ?? $item->product_stock;

            return $item;
        });        
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
        $cartItem = DB::table('cart_items')->where('id', $id)->first();
        $product = DB::table('product')->where('product_id', $cartItem->product_id)->first();
    $buyer = Auth::user(); // the one placing the order
    $seller = DB::table('users')->where('id', $cartItem->seller_id)->first();
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'pending',
                'updated_at' => now()
            ]);
        DB::table('sms_notifLogs')->insert([
        'from_id' => $buyer->id,
        'to_id' => $seller->id,
        'message' => "User {$buyer->name} placed a Buy Now order for your product '{$product->name}'.",
        'created_at' => now(),
        'updated_at' => now(),
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
            ->leftJoin('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
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
                'cart_items.updated_at',
                'cart_items.paymentConfirmation',
                'cart_items.gcash_receipt',
                'cart_items.payment_type',
                'cart_items.selected_variant',
                'cart_items.listing_update',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as seller_name',
                'users.qr_image as seller_qr_image',
                'buyers.id as buyer_id'
            );
        if ($filters == "all" || $filters == null) {
            $query->where('cart_items.status', '!=', 'in_cart');
        } else {
            $query->where('cart_items.status', $filters);
        }
        $items = $query->orderBy('cart_items.updated_at', 'asc')->get();
        foreach ($items as $item) {
        $item->formatted_updated_at = Carbon::parse($item->updated_at)->format('F d, Y');
        }
        $sellerId = Auth::id();
        $sellerRating = DB::table('reviews')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $sellerId)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
            ->first();
        // bali need to if ajax yung tatawag kasi if hindi mo to nilagay, ididsplay niya buong page imbis na cards(nakuha sa query)
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters', 'sellerRating'))->render();
        }

        return view('profile', compact('items', 'filters', 'sellerRating'));
    }
    public function getCardPartial($id, Request $request)
    {
        $items = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->join('users', 'cart_items.seller_id', '=', 'users.id')
            ->leftJoin('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
            ->where('cart_items.id', $id)
            ->select(
                'cart_items.id as cart_id',
                'cart_items.quantity',
                'cart_items.seller_id',
                'cart_items.buyer_response',
                'cart_items.seller_response',
                'cart_items.status',
                'cart_items.unit_price',
                'cart_items.product_id',
                'cart_items.updated_at',
                'cart_items.paymentConfirmation',
                'cart_items.gcash_receipt',
                'cart_items.payment_type',
                'cart_items.selected_variant',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as seller_name',
                'buyers.id as buyer_id',
                'users.qr_image as seller_qr_image',
                'buyers.name as buyer_name'
            )
            ->first();

        if (!$items) {
            return response()->json(['success' => false, 'message' => 'Cart item not found.']);
        }

        $items->formatted_updated_at = Carbon::parse($items->updated_at)->format('F d, Y');
        $filters = $request->query('filter');
        $items = collect([$items]);
        $html = view('partials.profileProduct', compact('items', 'filters'))->render();
        return response()->json(['success' => true, 'html' => $html]);
        
    }

    public function saveGcashReceipt(Request $request, $id){
        $request->validate([
            'gcash_receipt' => 'required|image|mimes:jpeg,png,jpg'
        ]);
        $image = $request->file('gcash_receipt');
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('gcash_receipts'), $filename);

        DB::table('cart_items')
        ->where('id', $id)
        ->update([
            'gcash_receipt' => basename($filename),
            'updated_at' => now()
        ]);
        return redirect()
            ->route('student.profile', ['filters' => 'pending']) 
            ->with('successfull', 'GCash receipt uploaded successfully.');
        }
    public function removeGcashReceipt(Request $request, $id){
        DB::table('cart_items')
        ->where('id', $id)
        ->update([
            'gcash_receipt' => null,
            'updated_at' => now()
        ]);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }
        return redirect()
            ->route('student.profile', ['filters' => 'pending'])
            ->with('successfull', 'GCash receipt Removed successfully.');
        }

public function cancel(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $cartItem = DB::table('cart_items')->where('id', $id)->lockForUpdate()->first();
        if (!$cartItem) {
            DB::rollBack();
            return back()->with('error', 'Cart item not found.');
        }

        $product = DB::table('product')->where('product_id', $cartItem->product_id)->lockForUpdate()->first();
        if (!$product) {
            DB::rollBack();
            return back()->with('error', 'Product not found.');
        }

        $selectedOption = $cartItem->selected_variant;
        $cancelQty      = $cartItem->quantity;
        $hasVariants    = !empty($product->variants);

        if ($cartItem->paymentConfirmation === 'yes') {
            if ($hasVariants) {
                // -------------------------------
                // 1. Restore to JSON variants
                // -------------------------------
                $variantData = json_decode($product->variants, true);

                if (
                    isset($variantData['options'], $variantData['optionStocks']) &&
                    is_array($variantData['options']) &&
                    is_array($variantData['optionStocks'])
                ) {
                    $optionIndex = array_search($selectedOption, $variantData['options']);
                    if ($optionIndex !== false) {
                        $variantData['optionStocks'][$optionIndex] =
                            (int) $variantData['optionStocks'][$optionIndex] + $cancelQty;

                        // ✅ Also restore to product_variants table
                        $variantRow = DB::table('product_variants')
                            ->where('product_id', $product->product_id)
                            ->where('variant_option', $selectedOption)
                            ->lockForUpdate()
                            ->first();

                        if ($variantRow) {
                            $newStock = (int) $variantRow->stock + $cancelQty;
                            DB::table('product_variants')
                                ->where('id', $variantRow->id)
                                ->update(['stock' => $newStock, 'updated_at' => now()]);
                        }

                        // Recalculate total stock from JSON
                        $newTotalStock = array_sum(array_map('intval', $variantData['optionStocks']));

                        DB::table('product')
                            ->where('product_id', $product->product_id)
                            ->update([
                                'variants' => json_encode($variantData),
                                'stock'    => $newTotalStock,
                            ]);
                    }
                }
            } else {
                // -------------------------------
                // No variants → restore to product.stock
                // -------------------------------
                $newStock = (int) $product->stock + $cancelQty;
                DB::table('product')
                    ->where('product_id', $product->product_id)
                    ->update(['stock' => $newStock]);
            }
        }

        // Restore voucher if applied
        if (!empty($cartItem->voucher_applied) && $cartItem->voucher_applied > 0) {
            DB::table('vouchers')
                ->where('cart_item_id', $cartItem->id)
                ->update([
                    'status' => 'available',
                    'updated_at' => now(),
                ]);
        }

        // Mark item as cancelled
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now(),
            ]);

        DB::commit();

        $filters = $request->input('filterValue');
        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }

        return redirect()->route('student.profile', ['filters' => $filters])
            ->with('success', 'Item cancelled successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'An error occurred while cancelling the order.');
    }
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
                'cart_items.updated_at',
                'cart_items.paymentConfirmation',
                'cart_items.gcash_receipt',
                'cart_items.payment_type',
                'cart_items.selected_variant',
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
        foreach ($items as $item) {
        $item->formatted_updated_at = Carbon::parse($item->updated_at)->format('F d, Y');
        }
        $sellerId = Auth::id();
        $sellerRating = DB::table('reviews')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $sellerId)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
            ->first();
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters', 'sellerRating'))->render();
        }
        return view('mysales', compact('items', 'filters', 'sellerRating'));
    }

public function confirmStudentSales(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $cartItem = DB::table('cart_items')->where('id', $id)->lockForUpdate()->first();
        if (!$cartItem) {
            DB::rollBack();
            return back()->with('error', 'Buy order not found.');
        }

        // Lock the product row
        $product = DB::table('product')->where('product_id', $cartItem->product_id)->lockForUpdate()->first();
        if (!$product) {
            DB::rollBack();
            return back()->with('error', 'Product not found.');
        }

        $selectedOption = $cartItem->selected_variant;
        $requestedQty   = $cartItem->quantity;

        $hasVariants = !empty($product->variants);

        if ($hasVariants) {
            // -------------------------------
            // 1. Handle JSON-based variants
            // -------------------------------
            $variantData = json_decode($product->variants, true);

            if (
                !isset($variantData['options'], $variantData['optionStocks']) ||
                !is_array($variantData['options']) ||
                !is_array($variantData['optionStocks'])
            ) {
                DB::rollBack();
                return back()->with('error', 'Invalid variant data.');
            }

            $optionIndex = array_search($selectedOption, $variantData['options']);
            if ($optionIndex === false) {
                DB::rollBack();
                return back()->with('error', 'Selected variant not found.');
            }

            $originalStock = (int) $variantData['optionStocks'][$optionIndex];

            // Exclude current cart item when calculating reserved qty
            $reservedQty = DB::table('cart_items')
                ->where('product_id', $product->product_id)
                ->where('selected_variant', $selectedOption)
                ->whereIn('status', ['receive'])
                ->where('id', '!=', $cartItem->id)
                ->sum('quantity');

            $available = $originalStock - $reservedQty;
            if ($available < $requestedQty) {
                DB::rollBack();
                return back()->with('error', "Not enough stock. Only $available left.");
            }

            // Subtract from JSON stock
            $variantData['optionStocks'][$optionIndex] = max(0, $originalStock - $requestedQty);

            // ✅ Also subtract from product_variants table
            $variantRow = DB::table('product_variants')
                ->where('product_id', $product->product_id)
                ->where('variant_option', $selectedOption)
                ->lockForUpdate()
                ->first();

            if ($variantRow) {
                $newStock = max(0, $variantRow->stock - $requestedQty);
                DB::table('product_variants')
                    ->where('id', $variantRow->id)
                    ->update(['stock' => $newStock, 'updated_at' => now()]);
            }

            // Recalculate total stock from all JSON variant stocks
            $newTotalStock = array_sum(array_map('intval', $variantData['optionStocks']));

            DB::table('product')
                ->where('product_id', $product->product_id)
                ->update([
                    'variants' => json_encode($variantData),
                    'stock'    => $newTotalStock,
                ]);

        } else {
            // -------------------------------
            // No variants: subtract from product.stock
            // -------------------------------
            $originalStock = (int) $product->stock;

            $reservedQty = DB::table('cart_items')
                ->where('product_id', $product->product_id)
                ->where('status', 'receive')
                ->where('id', '!=', $cartItem->id)
                ->sum('quantity');

            $available = $originalStock - $reservedQty;
            if ($available < $requestedQty) {
                DB::rollBack();
                return back()->with('error', "Not enough stock. Only $available left.");
            }

            $newTotalStock = max(0, $originalStock - $requestedQty);

            DB::table('product')
                ->where('product_id', $product->product_id)
                ->update(['stock' => $newTotalStock]);
        }

        // -------------------------------
        // Mark cart item as confirmed
        // -------------------------------
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'paymentConfirmation' => 'yes',
                'updated_at' => now(),
            ]);

        DB::commit();
        $buyer = DB::table('users')->where('id', $cartItem->user_id)->first();
        if($buyer){
            $notification = Notification::create([
                'user_id' => $buyer->id,
                'title' => 'Order Accepted',
                'message' => 'Your order for "' . $product->name . '" has been confirmed by the seller.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new \App\Events\NewNotification($notification));
            if (!empty($buyer->phone_number)) {
                try {
                    $smsService = app(\App\Services\IprogSmsService::class);
                    $text = 'Your payment for "' . $product->name . '" has been confirmed by the seller.';
                    $smsService->send($buyer->phone_number, $text);
                } catch (\Exception $e) {
                    Log::error('Failed to send SMS', ['error' => $e->getMessage()]);
                }
            }
        }
        $filters = $request->input('filterValue');
        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }

        if (Auth::check() && Auth::user()->role === 'student') {
            return redirect()->route('student.sales', ['filters' => $filters])
                ->with('success', 'Item Approved.');
        } elseif (Auth::check() && Auth::user()->role === 'organization') {
            return redirect()->route('order.page', ['filters' => $filters])
                ->with('success', 'Item Approved.');
        }

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'An error occurred while processing the order.');
    }
}


    public function confirmGcashPayment(Request $request, $id){
        DB::table('cart_items')
        ->where('id', $id)
        ->update([
            'status' => 'receive',
            'updated_at' => now()
        ]);
        $order = DB::table('cart_items')->where('id', $id)->first();
        if ($order) {
            $buyer = DB::table('users')->where('id', $order->user_id)->first();
            $notification = Notification::create([
                'user_id' => $buyer->user_id,
                'title' => 'Buy Order Confirmed',
                'message' => 'Your buy order for "'.$order->product_name.'" has been confirmed and ready to be received.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new \App\Events\NewNotification($notification));
            if($buyer->phone_number){
                try {
                    $smsService = app(\App\Services\IprogSmsService::class);
                    $message = 'Your buy order for "'.$order->product_name.'" has been confirmed.';
                    $response = $smsService->send($buyer->phone_number, $message);
                } catch (\Throwable $th) {
                    Log::error('Failed to send SMS to buyer', [
                        'error' => $e->getMessage(),
                        'order_id' => $id,
                    ]);
                }
            }
        }
        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }
        if(Auth::user()->role=== 'student'){
            return redirect()
            ->route('student.sales', ['filters' => 'pending']) 
            ->with('successfull', 'Order now up for delivery');
        }
        elseif(Auth::user()->role=== 'organization'){
            return redirect()
            ->route('order.page', ['filters' => 'pending']) 
            ->with('successfull', 'Order now up for delivery');
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

        $confirm = DB::table('cart_items')->where('id', $id)->first();

        if ($confirm->buyer_response === 'yes' && $confirm->seller_response === 'yes') {
            DB::table('cart_items')
                ->where('id', $id)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now()
                ]);

            $pbenUser = User::getPBENUser();
            $totalPrice = $confirm->unit_price * $confirm->quantity;
            $percentage = DB::table('credit_settings')->value('percentage');
            $creditsToAdd = ($totalPrice * $percentage) / 100;

            if ($confirm->seller_id == $pbenUser->id) {
                DB::table('users')
                    ->where('id', '=', Auth::id())
                    ->increment('credits', $creditsToAdd);
            }

            // ✅ Update critical level
            $this->recalculateCriticalLevel($confirm);
            $buyer = DB::table('users')->where('id', $confirm->user_id)->first();
            if($buyer){
                $notification = Notification::create([
                    'user_id' => $buyer->id,
                    'title' => 'Order Delivered',
                    'message' => 'Your order for "'.$confirm->product_name.'" has been delivered.',
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                event(new \App\Events\NewNotification($notification));
                if($buyer->phone_number){
                    $smsService = app(\App\Services\IprogSmsService::class);
                    $message = 'Your order for "'.$confirm->product_name.'" has been completed.';
                    $response = $smsService->send($buyer->phone_number, $message);
                }
            }
            
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
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

        $confirm = DB::table('cart_items')->where('id', $id)->first();

        if ($confirm->buyer_response === 'yes' && $confirm->seller_response === 'yes') {
            DB::table('cart_items')
                ->where('id', $id)
                ->update([
                    'status' => 'completed',
                    'updated_at' => now()
                ]);

            $pbenUser = User::getPBENUser();
            $buyerId = $confirm->user_id;  
            $totalPrice = $confirm->unit_price * $confirm->quantity;
            $percentage = DB::table('credit_settings')->value('percentage');
            $creditsToAdd = ($totalPrice * $percentage) / 100;

            if ($confirm->seller_id == $pbenUser->id) {
                DB::table('users')
                    ->where('id', '=', $buyerId)
                    ->increment('credits', $creditsToAdd);
            }

            // ✅ Update critical level
            $this->recalculateCriticalLevel($confirm);
            $buyer = DB::table('users')->where('id', $confirm->user_id)->first();
            if($buyer){
                $notification = Notification::create([
                    'user_id' => $buyer->id,
                    'title' => 'Order Delivered',
                    'message' => 'Your order for "'.$confirm->product_name.'" has been delivered.',
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                event(new \App\Events\NewNotification($notification));
                if($buyer->phone_number){
                    $smsService = app(\App\Services\IprogSmsService::class);
                    $message = 'Your order for "'.$confirm->product_name.'" has been completed.';
                    $response = $smsService->send($buyer->phone_number, $message);
                }
            }
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }

        if (Auth::check() && Auth::user()->role === 'student') {
            return redirect()->route('student.sales', ['filters' => $filters])
                ->with('success', 'Item delivered.');
        } elseif (Auth::check() && Auth::user()->role === 'organization') {
            return redirect()->route('order.page', ['filters' => $filters])
                ->with('success', 'Item delivered.');
        }
    }
}
private function recalculateCriticalLevel($cartItem): void
{
    // If a variant was purchased
    if (!empty($cartItem->selected_variant)) {
        $variant = DB::table('product_variants')
            ->where('product_id', $cartItem->product_id)
            ->where('variant_option', $cartItem->selected_variant)
            ->first();

        if ($variant && $variant->critical_mode === 'automatic') {
            $newCritical = $this->calculateAutomaticCriticalLevelVariant($variant, $cartItem->product_id);
            DB::table('product_variants')
                ->where('id', $variant->id)
                ->update(['critical_level' => $newCritical, 'updated_at' => now()]);
        }
    } else {
        // Non-variant product
        $product = Product::find($cartItem->product_id);
        if ($product && $product->critical_mode === 'automatic') {
            $product->critical_level = $this->calculateAutomaticCriticalLevel($product);
            $product->save();
        }
    }
}

private function calculateAutomaticCriticalLevel(Product $product): int
{
    $daysSinceCreation = now()->diffInDays($product->created_at, false);
    $daysSinceCreation = max(1, abs($daysSinceCreation)); 

    $days = min(15, $daysSinceCreation); 
    $startDate = now()->subDays($days);

    $totalSold = DB::table('cart_items')
        ->where('product_id', $product->product_id)
        ->where('status', 'completed')
        ->where('updated_at', '>=', $startDate)
        ->sum('quantity');

    $averageDailyUsage = $days > 0 ? ($totalSold / $days) : 0;

    return (int) round(($product->lead_time * $averageDailyUsage) + $product->safety_stock);
}

private function calculateAutomaticCriticalLevelVariant($variant, $productId): int
{
    $daysSinceCreation = now()->diffInDays($variant->created_at, false);
    $daysSinceCreation = max(1, abs($daysSinceCreation)); 

    $days = min(15, $daysSinceCreation); 
    $startDate = now()->subDays($days);

    $totalSold = DB::table('cart_items')
        ->where('product_id', $productId)
        ->where('selected_variant', $variant->variant_option)
        ->where('status', 'completed')
        ->where('updated_at', '>=', $startDate)
        ->sum('quantity');

    $averageDailyUsage = $days > 0 ? ($totalSold / $days) : 0;

    return (int) round(($variant->lead_time * $averageDailyUsage) + $variant->safety_stock);
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
    public function updateQuantity(Request $request, $id)
    {
    try {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = DB::table('cart_items')->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json([
                'success' => false, 
                'message' => 'Item not found.'
            ]);
        }

        $product = DB::table('product')->where('product_id', $cartItem->product_id)->first();
        if (!$product) {
            return response()->json([
                'success' => false, 
                'message' => 'Product not found.'
            ]);
        }

        $newQty = min($request->quantity, $product->stock);

        // Update cart item
        DB::table('cart_items')
            ->where('id', $id)
            ->update([
                'quantity' => $newQty,
                'updated_at' => now(),
            ]);

        // Calculate new totals
        $newItemTotal = ($product->price * $newQty) - $cartItem->voucher_applied;

        // Get updated cart total
        $cartTotal = DB::table('cart_items')
            ->where('user_id', Auth::id())
            ->sum(DB::raw('(unit_price * quantity) - voucher_applied'));

        $userId = Auth::id();
        
        $cartItems = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->where('cart_items.user_id', $userId)
            ->where('cart_items.status', '=', 'in_cart')
            ->select(
                'cart_items.id as cart_id',
                'cart_items.updated_at',
                'cart_items.quantity',
                'cart_items.unit_price',
                'cart_items.product_id',
                'product.name as product_name',
                'product.stock as product_stock',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied'
            )
            ->get();

                
        $totalItems = $cartItems->sum('quantity');

        $totalAmount = $cartItems->reduce(function ($carry, $item) {
            return $carry + (($item->unit_price * $item->quantity) - $item->voucher_applied);
        }, 0);
        return response()->json([
            'success' => true,
            'totalQuantity' => $totalItems,
            'newTotal' => number_format($newItemTotal, 2),
            'cartTotal' => number_format($totalAmount, 2),
            'quantity' => $newQty
        ]);

    } catch (\Exception $e) {
        Log::error('Cart update error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the quantity.'
        ]);
    }
}
}
