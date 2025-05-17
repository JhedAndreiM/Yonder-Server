<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('organization.dashboard', compact('products'));
    }
    public function update(Request $request)
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

        return back()->with('success', 'Product updated successfully with images.');
    }

    public function showChart()
    {
        $statusCounts = [
            'pending' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'pending')
                ->count(),

            'completed' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'completed')
                ->count(),

            'cancelled' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'cancelled')
                ->count(),

            'in_cart' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'in_cart')
                ->count(),

            'receive' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'receive')
                ->count()

        ];

        $currentYear = Carbon::now()->year;

        // Get monthly completed sales for current year
        $monthlySales = DB::table('cart_items')
            ->select(
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->whereYear('updated_at', $currentYear)
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->pluck('total', 'month');

        // Initialize all 12 months to 0
        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[] = $monthlySales->get($i, 0);
        }


        $cartItems = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->where('cart_items.seller_id', Auth::id())
            ->where('cart_items.status', '=', 'completed')
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
            $totalAmount = $cartItems->reduce(function ($carry, $item) {
            return $carry + (($item->unit_price * $item->quantity) - $item->voucher_applied);
            }, 0);

        // Get total wishlist items
        $totalWishlistItems = DB::table('wishlists')
            ->join('product', 'wishlists.product_id', '=', 'product.product_id')
            ->where('product.user_id',  Auth::id())
            ->select('wishlists.*')
            ->count();

        // Get stocks with less than 10
        $lowStockProducts = DB::table('product')
            ->where('user_id', Auth::id())
            ->where('stock', '<', 10)
            ->where('approved', 'yes')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // Get top seller products
        $topSellerProduct = DB::table('cart_items')
    ->join('product', 'cart_items.product_id', '=', 'product.product_id')
    ->where('cart_items.seller_id', Auth::id())
    ->where('cart_items.status', 'completed')
    ->select(
        'product.name as product_name',
        DB::raw('SUM(cart_items.quantity) as total_quantity')
    )
    ->groupBy('product.product_id', 'product.name')
    ->orderByDesc('total_quantity')
    ->limit(5)
    ->first();



    // for recent 
            $userId = Auth::id();
        $query = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->join('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
            ->join('users', 'cart_items.user_id', '=', 'users.id')
            ->where('cart_items.seller_id', '=', $userId)
            ->where('cart_items.status', '=', 'completed')
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
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as seller_name',
                'buyers.name as buyer_name',
                'buyers.id as buyer_id'
            )
            ->orderBy('cart_items.updated_at', 'desc');
            $cartData = $query->get();
        return view('organization/orgReport', compact('statusCounts', 'monthlySalesData', 'totalAmount', 'totalWishlistItems', 'lowStockCount', 'topSellerProduct', 'cartData'));
    }

    public function orggetAllNotCartItems(Request $request){
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
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters'))->render();
        }
        return view('organization/orderPage', compact('items', 'filters'));
    }

    public function reviews()
    {
        $userId = Auth::id(); // currently logged-in user

$reviews = DB::table('reviews')
    ->join('users', 'reviews.user_id', '=', 'users.id') // reviewer info
    ->join('product', 'reviews.product_id', '=', 'product.product_id')
    ->where('product.user_id', $userId) // only your products
    ->select(
        'users.name',
        'users.last_name',
        'product.image_path',
        'users.avatar',
        'reviews.rating',
        'reviews.comment',
        'reviews.created_at'
    )
    ->orderBy('reviews.created_at', 'desc')
    ->get()
    ->map(function ($review) {
        // Get first image
        $images = explode(',', $review->image_path);
        $review->first_image = $images[0];

        // Format the date
        $review->formatted_date = Carbon::parse($review->created_at)->format('F j, Y');

        return $review;
    });

        return view('organization/reviews', compact('reviews'));
    }
}
