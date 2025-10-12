<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrganizationController extends Controller
{
    public function dashboard(Request $request)
    {
        $sort = $request->get('sort');
        $query = DB::table('product')
            ->where('product.user_id', Auth::id())
            ->leftJoin('product_images', 'product.product_id', '=', 'product_images.product_id')
            ->select('product.*', 'product_images.image_path')
            ->groupBy('product.product_id');

        // Apply sorting/filter logic
        if (!empty($sort)) {
            if ($sort == 'all') {
                // no additional filter — just show all
            } elseif ($sort == 'pben') {
                $query->where('supplier_type', '=', 'pben');
            } elseif ($sort == 'student-org') {
                $query->where('supplier_type', '=', 'student-org');
            }
        }
        $products = $query->get();
        if ($request->ajax()) {
            return view('partials.adminProducts', compact('products'))->render();
        }
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

        $oldPrice = $product->price;
        $newPrice = $request->price;
        $priceChanged = $oldPrice != $newPrice;

        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        if ($priceChanged) {
            DB::table('cart_items')
            ->where('product_id', $product->product_id)
            ->where('status', 'in_cart')
            ->update(['unit_price' => $newPrice]);
        }
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

            $filenames = [];
            $counter = 0;
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $filename = time() . '_' . $counter . '_' . $originalName;
                $image->move(public_path('images'), $filename);
                $filenames[] = $filename;
                $counter++;
            }

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
        $wishlistCounts=DB::table('product')
        ->leftJoin('wishlists', 'product.product_id', '=', 'wishlists.product_id')
        ->where('product.user_id', Auth::id())
        ->select(
            'product.product_id',
            'product.name',
            DB::raw('COUNT(wishlists.product_id) as wishlist_count')
        )
        ->groupBy('product.product_id', 'product.name')
        ->orderByDesc('wishlist_count')
        ->get();
        $mostWishlisted = $wishlistCounts->first();

        $lowStockProducts = Product::with('variantsData')
        ->where('user_id', Auth::id())
        ->where('approved', 'yes')
        ->get();
        $lowStockFirst = $lowStockProducts->first();

        // Get top seller products
        $salesData = DB::table('cart_items')
        ->join('product', 'cart_items.product_id', '=', 'product.product_id')
        ->where('cart_items.seller_id', Auth::id())
        ->where('cart_items.status', 'completed')
        ->select(
            'product.product_id',
            'product.name as product_name',
            DB::raw('SUM(cart_items.quantity) as total_quantity')
        )
        ->groupBy('product.product_id', 'product.name')
        ->orderByDesc('total_quantity')
        ->get();
        $topSellerProduct = $salesData->first();


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
            return view('organization/orgReport', compact('statusCounts', 'monthlySalesData', 'totalAmount', 'wishlistCounts', 'mostWishlisted', 'lowStockProducts','lowStockFirst', 'topSellerProduct','salesData', 'cartData'));
    }

    public function filterChartData(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $userId = Auth::id();

        // Build date filter
        $dateFilter = '';
        if ($year && $month) {
            $dateFilter = "AND YEAR(cart_items.updated_at) = {$year} AND MONTH(cart_items.updated_at) = {$month}";
        } elseif ($year) {
            $dateFilter = "AND YEAR(cart_items.updated_at) = {$year}";
        }

        // Get filtered sales data
        $salesQuery = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->where('cart_items.seller_id', $userId)
            ->where('cart_items.status', 'completed');

        if ($year && $month) {
            $salesQuery->whereYear('cart_items.updated_at', $year)
                      ->whereMonth('cart_items.updated_at', $month);
        } elseif ($year) {
            $salesQuery->whereYear('cart_items.updated_at', $year);
        }

        $salesData = $salesQuery->select(
            'product.name as product_name',
            DB::raw('SUM(cart_items.quantity) as total_quantity')
        )
        ->groupBy('product.product_id', 'product.name')
        ->orderByDesc('total_quantity')
        ->limit(10)
        ->get();

        // Get filtered wishlist data
        $wishlistQuery = DB::table('product')
            ->leftJoin('wishlists', 'product.product_id', '=', 'wishlists.product_id')
            ->where('product.user_id', $userId);

        if ($year && $month) {
            $wishlistQuery->whereYear('wishlists.created_at', $year)
                         ->whereMonth('wishlists.created_at', $month);
        } elseif ($year) {
            $wishlistQuery->whereYear('wishlists.created_at', $year);
        }

        $wishlistData = $wishlistQuery->select(
            'product.name',
            DB::raw('COUNT(wishlists.product_id) as wishlist_count')
        )
        ->groupBy('product.product_id', 'product.name')
        ->orderByDesc('wishlist_count')
        ->limit(10)
        ->get();

        // Get filtered stock data
        $stockQuery = Product::where('user_id', $userId)
            ->where('approved', 'yes');

        $stockData = $stockQuery->select('name', 'stock')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // Format data for charts
        $salesLabels = $salesData->pluck('product_name')->toArray();
        $salesValues = $salesData->pluck('total_quantity')->toArray();

        $wishlistLabels = $wishlistData->pluck('name')->toArray();
        $wishlistValues = $wishlistData->pluck('wishlist_count')->toArray();

        $stockLabels = $stockData->pluck('name')->toArray();
        $stockValues = $stockData->pluck('stock')->toArray();

        return response()->json([
            'sales' => [
                'labels' => $salesLabels,
                'data' => $salesValues
            ],
            'wishlist' => [
                'labels' => $wishlistLabels,
                'data' => $wishlistValues
            ],
            'stock' => [
                'labels' => $stockLabels,
                'data' => $stockValues
            ]
        ]);
    }

    public function orggetAllNotCartItems(Request $request){
        $userId = Auth::id();
        $filters = $request->get('filter');
        $query = DB::table('cart_items')
            ->join('product', 'cart_items.product_id', '=', 'product.product_id')
            ->join('users as buyers', 'cart_items.user_id', '=', 'buyers.id')
            ->join('users', 'cart_items.user_id', '=', 'users.id')
            ->leftJoin('cancelled_cart_items', 'cancelled_cart_items.original_cart_id', '=', 'cart_items.id')
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
                'cart_items.listing_update',
                'product.name as product_name',
                'product.image_path',
                'product.description',
                'cart_items.voucher_applied',
                'users.name as buyer_name',
                'users.last_name as buyer_lastname',
                'buyers.id as buyer_id',
                'cancelled_cart_items.cancelled_by',
                'cancelled_cart_items.cancel_reason',
                'cancelled_cart_items.custom_reason',
                'cancelled_cart_items.cancelled_at'
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
        if ($request->ajax()) {
            return view('partials.profileProduct', compact('items', 'filters'))->render();
        }
        return view('organization/orderPage', compact('items', 'filters'));
    }

public function reviews(Request $request)
{
    $userId = Auth::id(); 
    $search = request('searching');

    $query = DB::table('reviews')
        ->join('users', 'reviews.user_id', '=', 'users.id')
        ->join('product', 'reviews.product_id', '=', 'product.product_id')
        ->leftJoin('product_images', function ($join) {
            $join->on('product.product_id', '=', 'product_images.product_id')
                 ->whereRaw('product_images.id = (select MIN(id) from product_images where product_images.product_id = product.product_id)');
        }) // joins only the FIRST image for each product
        ->where('product.user_id', $userId)
        ->select(
            'users.name',
            'users.last_name',
            'users.avatar',
            'reviews.rating',
            'reviews.comment',
            'reviews.created_at',
            'product.name as product_name',
            'product_images.image_path as first_image'
        )
        ->orderBy('reviews.created_at', 'desc');

    // Apply search filter
    if (!empty($search) && $search !== 'undefined') {
        $query->where(function($q) use ($search) {
            $q->where('users.name', 'like', '%' . $search . '%')
              ->orWhere('users.last_name', 'like', '%' . $search . '%')
              ->orWhere('reviews.comment', 'like', '%' . $search . '%')
              ->orWhere('product.name', 'like', '%' . $search . '%');
        });
    }

    $reviews = $query->get()->map(function ($review) {
        // Format the date
        $review->formatted_date = Carbon::parse($review->created_at)->format('F j, Y');
        return $review;
    });

    if ($request->ajax()) {
        return view('partials.reviewCards', compact('reviews'));
    }

    return view('organization/reviews', compact('reviews'));
}


public function updateStockSettings(Request $request)
{
    $rules = [
        'lead_time'      => 'required|integer|min:0|max:365',
        'safety_stock'   => 'required|integer|min:0|max:10000',
        'critical_mode'  => 'required|in:automatic,manual',
        'critical_level' => 'required_if:critical_mode,manual|nullable|integer|min:0',
    ];

    if ($request->filled('variant_id')) {
        $rules['variant_id'] = 'required|exists:product_variants,id';
    } else {
        $rules['product_id'] = 'required|exists:product,product_id';
    }

    $validated = $request->validate($rules);

    if ($request->filled('variant_id')) {
        // Update a variant
        $variant = ProductVariant::findOrFail($validated['variant_id']);
        $variant->lead_time = $validated['lead_time'];
        $variant->safety_stock = $validated['safety_stock'];
        $variant->critical_mode = $validated['critical_mode'];

        if ($validated['critical_mode'] === 'manual') {
            $variant->critical_level = $validated['critical_level'];
        } else {
            $variant->critical_level = $this->calculateAutomaticCriticalLevel($variant);
        }

        $variant->save();

    } else {
        // Update a whole product (no variants)
        $product = Product::findOrFail($validated['product_id']);
        $product->lead_time = $validated['lead_time'];
        $product->safety_stock = $validated['safety_stock'];
        $product->critical_mode = $validated['critical_mode'];

        if ($validated['critical_mode'] === 'manual') {
            $product->critical_level = $validated['critical_level'];
        } else {
            $product->critical_level = $this->calculateAutomaticCriticalLevel($product);
        }

        $product->save();
    }

    // Check for low stock notifications after updating settings
    $this->checkLowStockNotifications();

    return redirect()->back()->with('success', 'Stock settings updated successfully.');
}

private function calculateAutomaticCriticalLevel(Product|ProductVariant $item): int
{
    // normalize values depending on whether it's a Product or a Variant
    if ($item instanceof ProductVariant) {
        $productId      = $item->product_id;
        $leadTime       = $item->lead_time;
        $safetyStock    = $item->safety_stock;
        $createdAt      = $item->created_at;
        $variantOption  = $item->variant_option; // e.g. "Medium"
    } else {
        $productId      = $item->product_id;
        $leadTime       = $item->lead_time;
        $safetyStock    = $item->safety_stock;
        $createdAt      = $item->created_at;
        $variantOption  = null;
    }

    // days since created
    $daysSinceCreation = now()->diffInDays($createdAt, false);
    $daysSinceCreation = max(1, abs($daysSinceCreation));

    $days = min(15, $daysSinceCreation);
    $startDate = now()->subDays($days);

    // base query
    $query = DB::table('cart_items')
        ->where('product_id', $productId)
        ->where('status', 'completed')
        ->where('updated_at', '>=', $startDate);

    // ✅ filter by selected_variant if it's a variant
    if ($variantOption) {
        $query->where('selected_variant', $variantOption);
    }

    $totalSold = $query->sum('quantity');
    $averageDailyUsage = $days > 0 ? ($totalSold / $days) : 0;

    return (int) round(($leadTime * $averageDailyUsage) + $safetyStock);
}

    // Public endpoint to check low stock (can be called by cron/scheduler)
    public function checkLowStockEndpoint()
    {
        $this->checkLowStockNotifications();
        return response()->json(['message' => 'Low stock check completed']);
    }

        // Check and send low stock notifications
    public function checkLowStockNotifications()
    {
        // Get PBEN user (organization with pben@bpsu.edu.ph email)
        $pbenUser = \App\Models\User::where('email', 'pben@bpsu.edu.ph')
            ->where('role', 'organization')
            ->first();
        if (!$pbenUser || !$pbenUser->phone_number) {
            \Illuminate\Support\Facades\Log::warning('⚠️ LOW STOCK CHECK SKIPPED - No PBEN user or phone number', [
                'pben_user_found' => $pbenUser ? 'Yes' : 'No',
                'phone_number_exists' => $pbenUser && $pbenUser->phone_number ? 'Yes' : 'No',
                'timestamp' => now()->toDateTimeString()
            ]);
            return;
        }

        \Illuminate\Support\Facades\Log::info('🔍 LOW STOCK CHECK STARTED', [
            'recipient' => $pbenUser->phone_number,
            'recipient_email' => $pbenUser->email,
            'timestamp' => now()->toDateTimeString()
        ]);

        // Check if we've sent a low stock summary in the last 24 hours
        $recentSummaryNotification = DB::table('sms_notifLogs')
            ->where('to_id', $pbenUser->id)
            ->where('message', 'like', '%LOW STOCK SUMMARY%')
            ->where('created_at', '>', now()->subHours(24))
            ->exists();

        if ($recentSummaryNotification) {
            \Illuminate\Support\Facades\Log::info('⏰ LOW STOCK CHECK SKIPPED - Recent notification sent', [
                'recipient' => $pbenUser->phone_number,
                'recipient_email' => $pbenUser->email,
                'reason' => 'Summary already sent within last 24 hours',
                'timestamp' => now()->toDateTimeString()
            ]);
            return; // Don't send another summary within 24 hours
        }

        // Get all products with variants and check critical levels
        $lowStockItems = collect();

        // Check products with variants
        $productsWithVariants = Product::with('variantsData')
            ->where('user_id', $pbenUser->id)
            ->where('approved', 'yes')
            ->whereHas('variantsData')
            ->get();

        foreach ($productsWithVariants as $product) {
            foreach ($product->variantsData as $variant) {
                if ((int) $variant->stock <= (int) $variant->critical_level) {
                    $lowStockItems->push([
                        'type' => 'variant',
                        'product_name' => $product->name,
                        'variant_info' => $variant->variant_name . ': ' . $variant->variant_option,
                        'current_stock' => $variant->stock,
                        'critical_level' => $variant->critical_level
                    ]);
                }
            }
        }

        // Check products without variants
        $productsWithoutVariants = Product::where('user_id', $pbenUser->id)
            ->where('approved', 'yes')
            ->whereDoesntHave('variantsData')
            ->get();

        foreach ($productsWithoutVariants as $product) {
            if ((int) $product->stock <= (int) $product->critical_level) {
                $lowStockItems->push([
                    'type' => 'product',
                    'product_name' => $product->name,
                    'variant_info' => null,
                    'current_stock' => $product->stock,
                    'critical_level' => $product->critical_level
                ]);
            }
        }

        // Send ONE summarized SMS notification if there are low stock items
        if ($lowStockItems->isNotEmpty()) {
            \Illuminate\Support\Facades\Log::info('📦 LOW STOCK ITEMS FOUND', [
                'total_items' => $lowStockItems->count(),
                'items_list' => $lowStockItems->map(function($item) {
                    return ($item['variant_info'] ? $item['product_name'] . ' — ' . $item['variant_info'] : $item['product_name']) . ' (Stock: ' . $item['current_stock'] . ')';
                })->toArray(),
                'timestamp' => now()->toDateTimeString()
            ]);
            $totalItems = $lowStockItems->count();
            
            if ($totalItems == 1) {
                // Single item - send detailed message
                $item = $lowStockItems->first();
                $itemName = $item['variant_info'] 
                    ? $item['product_name'] . ' — ' . $item['variant_info']
                    : $item['product_name'];

                $message = 'LOW STOCK ALERT: ' . $itemName . ' is running low (Stock: ' . $item['current_stock'] . ', Critical Level: ' . $item['critical_level'] . '). Please restock soon.';
            } else {
                // Multiple items - send summary
                $message = 'LOW STOCK SUMMARY: ' . $totalItems . ' items are below critical level. ';
                
                // Add first few items as examples (limit to keep SMS short)
                $exampleItems = $lowStockItems->take(3);
                $examples = [];
                
                foreach ($exampleItems as $item) {
                    $itemName = $item['variant_info'] 
                        ? $item['product_name'] . ' — ' . $item['variant_info']
                        : $item['product_name'];
                    $examples[] = $itemName . ' (' . $item['current_stock'] . ')';
                }
                
                $message .= 'Examples: ' . implode(', ', $examples);
                
                if ($totalItems > 3) {
                    $message .= ' and ' . ($totalItems - 3) . ' more. Check your dashboard for full details.';
                } else {
                    $message .= '. Please restock soon.';
                }
            }

            // Log before attempting to send
            \Illuminate\Support\Facades\Log::info('=== LOW STOCK SMS ATTEMPT ===', [
                'recipient' => $pbenUser->phone_number,
                'recipient_email' => $pbenUser->email,
                'total_low_stock_items' => $totalItems,
                'message_preview' => substr($message, 0, 100) . '...',
                'message_length' => strlen($message),
                'timestamp' => now()->toDateTimeString()
            ]);

            try {
                $smsService = app(\App\Services\IprogSmsService::class);
                $response = $smsService->send($pbenUser->phone_number, $message);

                // Log the SMS notification in database
                DB::table('sms_notifLogs')->insert([
                    'from_id' => 1, // System notification
                    'to_id' => $pbenUser->id,
                    'message' => $message,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                \Illuminate\Support\Facades\Log::info('✅ LOW STOCK SMS SUCCESS ✅', [
                    'recipient' => $pbenUser->phone_number,
                    'total_items' => $totalItems,
                    'message_length' => strlen($message),
                    'sms_service_response' => $response ?? 'No response data',
                    'timestamp' => now()->toDateTimeString()
                ]);

            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('❌ LOW STOCK SMS FAILED ❌', [
                    'recipient' => $pbenUser->phone_number,
                    'recipient_email' => $pbenUser->email,
                    'total_items' => $totalItems,
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'error_file' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'full_message' => $message,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }
        } else {
            \Illuminate\Support\Facades\Log::info('✨ LOW STOCK CHECK COMPLETED - No items below critical level', [
                'recipient' => $pbenUser->phone_number,
                'recipient_email' => $pbenUser->email,
                'products_checked' => 'All approved products',
                'timestamp' => now()->toDateTimeString()
            ]);
        }
    }

    // delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Optional: also delete image
        if ($product->image_path && file_exists(public_path('images/' . $product->image_path))) {
            unlink(public_path('images/' . $product->image_path));
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }

}
