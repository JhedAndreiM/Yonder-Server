<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\TradeOffer;
use App\Models\TradeOfferItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    /**
     * Display all trade offers (sent and received)
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'received'); // Default to 'received' tab
        
        // Get received trade offers (offers sent to me)
        // Exclude 'countered' status as those are replaced by the counter offer
        // Sort by status (pending first) then by created_at
        $receivedOffers = TradeOffer::with(['sender', 'items.product'])
            ->where('recipient_id', Auth::id())
            ->where('status', '!=', 'countered')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get sent trade offers (offers I sent to others)
        // Exclude 'countered' status as those are replaced by the counter offer
        // Sort by status (pending first) then by created_at
        $sentOffers = TradeOffer::with(['recipient', 'items.product'])
            ->where('sender_id', Auth::id())
            ->where('status', '!=', 'countered')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('trade.offers', [
            'receivedOffers' => $receivedOffers,
            'sentOffers' => $sentOffers,
            'activeTab' => $tab
        ]);
    }

    /**
     * Show the trade offer creation page
     * 
     * @param int $recipientId - The user you want to trade with
     * @param Request $request - Contains optional productId parameter
     */
    public function create($recipientId, Request $request)
    {
        $recipient = User::findOrFail($recipientId);
        
        // Prevent trading with yourself
        if ($recipient->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot trade with yourself.');
        }

        // Get the specific product if provided (from product details page)
        $targetProductId = $request->query('productId');
        $targetProduct = null;
        
        if ($targetProductId) {
            $targetProduct = Product::where('product_id', $targetProductId)
                ->where('user_id', $recipient->id)
                ->where('approved', 'yes')
                ->with(['images' => function($query) {
                    $query->orderBy('id', 'asc')->limit(1);
                }])
                ->first();
        }

        // Get current user's tradeable items (products they own)
        $myItems = Product::where('user_id', Auth::id())
            ->where('approved', 'yes')
            ->where('forSaleTrade', 'trade')
            ->with(['images' => function($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }])
            ->get()
            ->filter(function($product) {
                // Filter out products with 0 stock
                $variants = $product->variants;
                if (!empty($variants) && isset($variants['options']) && count($variants['options']) > 0) {
                    // For variant products, check if ANY variant has stock
                    $totalStock = array_sum(array_map('intval', $variants['optionStocks']));
                    return $totalStock > 0;
                } else {
                    // For non-variant products, check main stock
                    return $product->stock > 0;
                }
            });

        // Get recipient's OTHER tradeable items (excluding the target product)
        $recipientItemsQuery = Product::where('user_id', $recipient->id)
            ->where('approved', 'yes')
            ->where('forSaleTrade', 'trade');
        
        // Exclude the target product from the list (we'll add it separately)
        if ($targetProductId) {
            $recipientItemsQuery->where('product_id', '!=', $targetProductId);
        }
        
        $recipientItems = $recipientItemsQuery->with(['images' => function($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }])
            ->get()
            ->filter(function($product) {
                // Filter out products with 0 stock
                $variants = $product->variants;
                if (!empty($variants) && isset($variants['options']) && count($variants['options']) > 0) {
                    // For variant products, check if ANY variant has stock
                    $totalStock = array_sum(array_map('intval', $variants['optionStocks']));
                    return $totalStock > 0;
                } else {
                    // For non-variant products, check main stock
                    return $product->stock > 0;
                }
            });

        return view('trade.create', compact('recipient', 'myItems', 'recipientItems', 'targetProduct'));
    }

    /**
     * Get user's inventory items via AJAX
     */
    public function getMyItems()
    {
        $items = Product::where('user_id', Auth::id())
            ->where('approved', 'yes')
            ->where('forSaleTrade', 'trade')
            ->with(['images' => function($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->name,
                    'image' => $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png'),
                    'condition' => $item->product_condition,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($item->description), 100),
                ];
            });

        return response()->json($items);
    }

    /**
     * Get recipient's tradeable items via AJAX
     */
    public function getRecipientItems($recipientId)
    {
        $items = Product::where('user_id', $recipientId)
            ->where('approved', 'yes')
            ->where('forSaleTrade', 'trade')
            ->with(['images' => function($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->name,
                    'image' => $item->images->first() ? asset('images/' . $item->images->first()->image_path) : asset('img/placeholder.png'),
                    'condition' => $item->product_condition,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($item->description), 100),
                ];
            });

        return response()->json($items);
    }

    /**
     * Store a new trade offer
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'my_items' => 'required|array|min:1',
            'my_items.*.id' => 'required|exists:product,product_id',
            'my_items.*.quantity' => 'required|integer|min:1',
            'my_items.*.variant_index' => 'nullable|integer',
            'my_items.*.variant_name' => 'nullable|string',
            'my_items.*.price' => 'required|numeric|min:0',
            'their_items' => 'nullable|array',
            'their_items.*.id' => 'required|exists:product,product_id',
            'their_items.*.quantity' => 'required|integer|min:1',
            'their_items.*.variant_index' => 'nullable|integer',
            'their_items.*.variant_name' => 'nullable|string',
            'their_items.*.price' => 'required|numeric|min:0',
        ]);

        // Prevent trading with yourself
        if ($validated['recipient_id'] == Auth::id()) {
            return response()->json(['error' => 'You cannot trade with yourself.'], 400);
        }

        try {
            DB::beginTransaction();

            // Validate stock availability and ownership for sender's items
            foreach ($validated['my_items'] as $item) {
                $product = Product::where('product_id', $item['id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Check stock
                if (isset($item['variant_index']) && $item['variant_index'] !== null) {
                    // Variant stock check
                    $variants = $product->variants;
                    if (!$variants || !isset($variants['optionStocks'][$item['variant_index']])) {
                        throw new \Exception("Invalid variant for product: {$product->name}");
                    }
                    $availableStock = (int)$variants['optionStocks'][$item['variant_index']];
                    if ($item['quantity'] > $availableStock) {
                        throw new \Exception("Insufficient stock for {$product->name} - {$item['variant_name']}. Available: {$availableStock}");
                    }
                } else {
                    // Regular stock check
                    if ($item['quantity'] > $product->stock) {
                        throw new \Exception("Insufficient stock for {$product->name}. Available: {$product->stock}");
                    }
                }
            }

            // Note: We only validate ownership of recipient's items, not stock
            // Stock will be validated when the recipient accepts/declines the offer
            if (!empty($validated['their_items'])) {
                foreach ($validated['their_items'] as $item) {
                    // Just verify the product exists and belongs to the recipient
                    $product = Product::where('product_id', $item['id'])
                        ->where('user_id', $validated['recipient_id'])
                        ->first();
                    
                    if (!$product) {
                        throw new \Exception("One or more requested items don't belong to the recipient or don't exist.");
                    }
                }
            }

            // Create the trade offer
            $tradeOffer = TradeOffer::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $validated['recipient_id'],
                'status' => 'pending',
                'message' => $validated['message'] ?? null,
            ]);

            // Add sender's items (items being offered)
            foreach ($validated['my_items'] as $item) {
                TradeOfferItem::create([
                    'trade_offer_id' => $tradeOffer->id,
                    'product_id' => $item['id'],
                    'side' => 'sender',
                    'quantity' => $item['quantity'],
                    'variant_index' => $item['variant_index'] ?? null,
                    'variant_name' => $item['variant_name'] ?? null,
                    'price_at_time' => $item['price'],
                ]);
            }

            // Add recipient's items (items being requested)
            if (!empty($validated['their_items'])) {
                foreach ($validated['their_items'] as $item) {
                    TradeOfferItem::create([
                        'trade_offer_id' => $tradeOffer->id,
                        'product_id' => $item['id'],
                        'side' => 'recipient',
                        'quantity' => $item['quantity'],
                        'variant_index' => $item['variant_index'] ?? null,
                        'variant_name' => $item['variant_name'] ?? null,
                        'price_at_time' => $item['price'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trade offer sent successfully!',
                'trade_offer_id' => $tradeOffer->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Cancel a trade offer
     */
    public function cancel(Request $request, $offerId)
    {
        try {
            $offer = TradeOffer::findOrFail($offerId);
            
            // Verify the user is the sender
            if ($offer->sender_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to cancel this offer.'
                ], 403);
            }
            
            // Verify the offer is still pending
            if ($offer->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending offers can be cancelled.'
                ], 400);
            }
            
            // Get reason from request
            $reason = $request->input('reason');
            
            // Update status to cancelled
            $offer->status = 'cancelled';
            $offer->cancellation_reason = $reason;
            $offer->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Trade offer cancelled successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel offer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept a trade offer
     * This will deduct stocks from both parties and change status to 'accepted'
     * Both parties then need to confirm receipt before it's marked as 'completed'
     */
    public function accept($offerId)
    {
        try {
            DB::beginTransaction();
            
            $offer = TradeOffer::with(['senderItems.product', 'recipientItems.product'])
                ->findOrFail($offerId);
            
            // Verify the user is the recipient
            if ($offer->recipient_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to accept this offer.'
                ], 403);
            }
            
            // Verify the offer is still pending
            if ($offer->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending offers can be accepted.'
                ], 400);
            }

            // Validate stock availability for all sender's items
            foreach ($offer->senderItems as $item) {
                $product = $item->product;
                
                if ($item->variant_name) {
                    // For variant products, check variant stock
                    // Handle both string and array cases
                    $variants = $product->variants;
                    if (is_string($variants)) {
                        $variants = json_decode($variants, true);
                    }
                    
                    // Check if variants is properly set
                    if (!is_array($variants) || empty($variants)) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Product {$product->name} has no variants configured"
                        ], 400);
                    }
                    
                    $variantFound = false;
                    $variantStock = 0;
                    
                    // Check for new format: {name, options, optionStocks}
                    if (isset($variants['options']) && isset($variants['optionStocks'])) {
                        $optionIndex = array_search($item->variant_name, $variants['options']);
                        if ($optionIndex !== false && isset($variants['optionStocks'][$optionIndex])) {
                            $variantFound = true;
                            $variantStock = (int)$variants['optionStocks'][$optionIndex];
                        }
                    } else {
                        // Old format: array of {name, stock} objects
                        foreach ($variants as $variant) {
                            // Skip if variant is not properly structured
                            if (!is_array($variant)) {
                                continue;
                            }
                            
                            if (isset($variant['name']) && $variant['name'] === $item->variant_name) {
                                $variantFound = true;
                                $variantStock = isset($variant['stock']) ? (int)$variant['stock'] : 0;
                                break;
                            }
                        }
                    }
                    
                    if (!$variantFound) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Variant {$item->variant_name} not found for {$product->name}"
                        ], 400);
                    }
                    
                    if ($variantStock < $item->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for {$product->name} ({$item->variant_name}). Available: {$variantStock}, Requested: {$item->quantity}"
                        ], 400);
                    }
                } else {
                    // For non-variant products, check main stock
                    if ($product->stock < $item->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}, Requested: {$item->quantity}"
                        ], 400);
                    }
                }
            }

            // Validate stock availability for all recipient's items
            foreach ($offer->recipientItems as $item) {
                $product = $item->product;
                
                if ($item->variant_name) {
                    // For variant products, check variant stock
                    // Handle both string and array cases
                    $variants = $product->variants;
                    if (is_string($variants)) {
                        $variants = json_decode($variants, true);
                    }
                    
                    // Check if variants is properly set
                    if (!is_array($variants) || empty($variants)) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Product {$product->name} has no variants configured"
                        ], 400);
                    }
                    
                    $variantFound = false;
                    $variantStock = 0;
                    
                    // Check for new format: {name, options, optionStocks}
                    if (isset($variants['options']) && isset($variants['optionStocks'])) {
                        $optionIndex = array_search($item->variant_name, $variants['options']);
                        if ($optionIndex !== false && isset($variants['optionStocks'][$optionIndex])) {
                            $variantFound = true;
                            $variantStock = (int)$variants['optionStocks'][$optionIndex];
                        }
                    } else {
                        // Old format: array of {name, stock} objects
                        foreach ($variants as $variant) {
                            // Skip if variant is not properly structured
                            if (!is_array($variant)) {
                                continue;
                            }
                            
                            if (isset($variant['name']) && $variant['name'] === $item->variant_name) {
                                $variantFound = true;
                                $variantStock = isset($variant['stock']) ? (int)$variant['stock'] : 0;
                                break;
                            }
                        }
                    }
                    
                    if (!$variantFound) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Variant {$item->variant_name} not found for {$product->name}"
                        ], 400);
                    }
                    
                    if ($variantStock < $item->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for {$product->name} ({$item->variant_name}). Available: {$variantStock}, Requested: {$item->quantity}"
                        ], 400);
                    }
                } else {
                    // For non-variant products, check main stock
                    if ($product->stock < $item->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "Insufficient stock for {$product->name}. Available: {$product->stock}, Requested: {$item->quantity}"
                        ], 400);
                    }
                }
            }

            // All validations passed - now deduct stocks
            // Deduct sender's items
            foreach ($offer->senderItems as $item) {
                $product = $item->product;
                
                if ($item->variant_name) {
                    // Handle both string and array cases
                    $variants = $product->variants;
                    if (is_string($variants)) {
                        $variants = json_decode($variants, true);
                    }
                    
                    if (is_array($variants)) {
                        // Check for new format: {name, options, optionStocks}
                        if (isset($variants['options']) && isset($variants['optionStocks'])) {
                            $optionIndex = array_search($item->variant_name, $variants['options']);
                            if ($optionIndex !== false && isset($variants['optionStocks'][$optionIndex])) {
                                $variants['optionStocks'][$optionIndex] = (int)$variants['optionStocks'][$optionIndex] - $item->quantity;
                            }
                        } else {
                            // Old format: array of {name, stock} objects
                            foreach ($variants as &$variant) {
                                // Skip if variant is not properly structured
                                if (!is_array($variant)) {
                                    continue;
                                }
                                
                                if (isset($variant['name']) && $variant['name'] === $item->variant_name) {
                                    if (isset($variant['stock'])) {
                                        $variant['stock'] -= $item->quantity;
                                    }
                                    break;
                                }
                            }
                            unset($variant); // Break reference
                        }
                        
                        $product->variants = $variants; // Will be auto-encoded by Laravel
                        
                        // ALSO deduct from overall product stock
                        $product->stock -= $item->quantity;
                        $product->save();
                    }
                } else {
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            // Deduct recipient's items
            foreach ($offer->recipientItems as $item) {
                $product = $item->product;
                
                if ($item->variant_name) {
                    // Handle both string and array cases
                    $variants = $product->variants;
                    if (is_string($variants)) {
                        $variants = json_decode($variants, true);
                    }
                    
                    if (is_array($variants)) {
                        // Check for new format: {name, options, optionStocks}
                        if (isset($variants['options']) && isset($variants['optionStocks'])) {
                            $optionIndex = array_search($item->variant_name, $variants['options']);
                            if ($optionIndex !== false && isset($variants['optionStocks'][$optionIndex])) {
                                $variants['optionStocks'][$optionIndex] = (int)$variants['optionStocks'][$optionIndex] - $item->quantity;
                            }
                        } else {
                            // Old format: array of {name, stock} objects
                            foreach ($variants as &$variant) {
                                // Skip if variant is not properly structured
                                if (!is_array($variant)) {
                                    continue;
                                }
                                
                                if (isset($variant['name']) && $variant['name'] === $item->variant_name) {
                                    if (isset($variant['stock'])) {
                                        $variant['stock'] -= $item->quantity;
                                    }
                                    break;
                                }
                            }
                            unset($variant); // Break reference
                        }
                        
                        $product->variants = $variants; // Will be auto-encoded by Laravel
                        
                        // ALSO deduct from overall product stock
                        $product->stock -= $item->quantity;
                        $product->save();
                    }
                } else {
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            // Update offer status to accepted
            $offer->status = 'accepted';
            $offer->responded_at = now();
            $offer->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Trade offer accepted! Both parties need to confirm receipt to complete the trade.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to accept offer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Decline a trade offer
     */
    public function decline(Request $request, $offerId)
    {
        try {
            $offer = TradeOffer::findOrFail($offerId);
            
            // Verify the user is the recipient
            if ($offer->recipient_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to decline this offer.'
                ], 403);
            }
            
            // Verify the offer is still pending
            if ($offer->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending offers can be declined.'
                ], 400);
            }
            
            // Get reason from request
            $reason = $request->input('reason');
            
            // Update status to declined
            $offer->status = 'declined';
            $offer->decline_reason = $reason;
            $offer->responded_at = now();
            $offer->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Trade offer declined successfully.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decline offer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the counter offer page
     * Pre-populated with the original offer details
     */
    public function counterCreate($offerId)
    {
        try {
            $originalOffer = TradeOffer::with(['sender', 'recipient', 'senderItems.product.images', 'recipientItems.product.images'])
                ->findOrFail($offerId);
            
            // Verify the user is the recipient of the original offer
            if ($originalOffer->recipient_id !== Auth::id()) {
                return redirect()->route('trade.offers')->with('error', 'You can only counter offers sent to you.');
            }
            
            // Verify the offer is still pending
            if ($originalOffer->status !== 'pending') {
                return redirect()->route('trade.offers')->with('error', 'You can only counter pending offers.');
            }
            
            // The recipient of the original offer becomes the sender of the counter offer
            $recipient = $originalOffer->sender;
            
            // Get current user's tradeable items (products they own)
            $myItems = Product::where('user_id', Auth::id())
                ->where('approved', 'yes')
                ->where('forSaleTrade', 'trade')
                ->with(['images' => function($query) {
                    $query->orderBy('id', 'asc')->limit(1);
                }])
                ->get()
                ->filter(function($product) {
                    // Filter out products with 0 stock
                    $variants = $product->variants;
                    if (!empty($variants) && isset($variants['options']) && count($variants['options']) > 0) {
                        // For variant products, check if ANY variant has stock
                        $totalStock = array_sum(array_map('intval', $variants['optionStocks']));
                        return $totalStock > 0;
                    } else {
                        // For non-variant products, check main stock
                        return $product->stock > 0;
                    }
                });

            // Get recipient's tradeable items
            $recipientItems = Product::where('user_id', $recipient->id)
                ->where('approved', 'yes')
                ->where('forSaleTrade', 'trade')
                ->with(['images' => function($query) {
                    $query->orderBy('id', 'asc')->limit(1);
                }])
                ->get()
                ->filter(function($product) {
                    // Filter out products with 0 stock
                    $variants = $product->variants;
                    if (!empty($variants) && isset($variants['options']) && count($variants['options']) > 0) {
                        // For variant products, check if ANY variant has stock
                        $totalStock = array_sum(array_map('intval', $variants['optionStocks']));
                        return $totalStock > 0;
                    } else {
                        // For non-variant products, check main stock
                        return $product->stock > 0;
                    }
                });

            // Counter offers don't have a target product
            $targetProduct = null;

            return view('trade.create', compact('recipient', 'myItems', 'recipientItems', 'originalOffer', 'targetProduct'));
            
        } catch (\Exception $e) {
            return redirect()->route('trade.offers')->with('error', 'Failed to load counter offer: ' . $e->getMessage());
        }
    }

    /**
     * Store a counter offer
     */
    public function counterStore(Request $request, $offerId)
    {
        // Validate the request
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'my_items' => 'required|array|min:1',
            'my_items.*.id' => 'required|exists:product,product_id',
            'my_items.*.quantity' => 'required|integer|min:1',
            'my_items.*.variant_index' => 'nullable|integer',
            'my_items.*.variant_name' => 'nullable|string',
            'my_items.*.price' => 'required|numeric|min:0',
            'their_items' => 'nullable|array',
            'their_items.*.id' => 'required|exists:product,product_id',
            'their_items.*.quantity' => 'required|integer|min:1',
            'their_items.*.variant_index' => 'nullable|integer',
            'their_items.*.variant_name' => 'nullable|string',
            'their_items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $originalOffer = TradeOffer::findOrFail($offerId);
            
            // Verify the user is the recipient of the original offer
            if ($originalOffer->recipient_id !== Auth::id()) {
                return response()->json(['error' => 'You can only counter offers sent to you.'], 403);
            }
            
            // Verify the original offer is still pending
            if ($originalOffer->status !== 'pending') {
                return response()->json(['error' => 'You can only counter pending offers.'], 400);
            }

            DB::beginTransaction();

            // Validate stock availability for sender's items (same as store method)
            foreach ($validated['my_items'] as $item) {
                $product = Product::where('product_id', $item['id'])
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

                // Check stock
                if (isset($item['variant_index']) && $item['variant_index'] !== null) {
                    $variants = $product->variants;
                    if (!$variants || !isset($variants['optionStocks'][$item['variant_index']])) {
                        throw new \Exception("Invalid variant for product: {$product->name}");
                    }
                    $availableStock = (int)$variants['optionStocks'][$item['variant_index']];
                    if ($item['quantity'] > $availableStock) {
                        throw new \Exception("Insufficient stock for variant of {$product->name}");
                    }
                } else {
                    if ($item['quantity'] > $product->stock) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                }
            }
            
            // Note: We don't validate stock for 'their_items' because they belong to the recipient
            // The recipient is responsible for their own stock management

            // Create the counter offer
            $counterOffer = TradeOffer::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $validated['recipient_id'],
                'parent_offer_id' => $offerId,
                'status' => 'pending',
                'message' => $validated['message'] ?? null,
            ]);

            // Add sender's items to the counter offer
            foreach ($validated['my_items'] as $item) {
                TradeOfferItem::create([
                    'trade_offer_id' => $counterOffer->id,
                    'product_id' => $item['id'],
                    'side' => 'sender',
                    'quantity' => $item['quantity'],
                    'variant_index' => $item['variant_index'] ?? null,
                    'variant_name' => $item['variant_name'] ?? null,
                    'price_at_time' => $item['price'],
                ]);
            }

            // Add recipient's items to the counter offer
            if (!empty($validated['their_items'])) {
                foreach ($validated['their_items'] as $item) {
                    TradeOfferItem::create([
                        'trade_offer_id' => $counterOffer->id,
                        'product_id' => $item['id'],
                        'side' => 'recipient',
                        'quantity' => $item['quantity'],
                        'variant_index' => $item['variant_index'] ?? null,
                        'variant_name' => $item['variant_name'] ?? null,
                        'price_at_time' => $item['price'],
                    ]);
                }
            }

            // Update the original offer status to 'countered'
            $originalOffer->status = 'countered';
            $originalOffer->responded_at = now();
            $originalOffer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Counter offer sent successfully!',
                'redirect' => route('trade.offers', ['tab' => 'sent'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm receipt of items
     * When both parties confirm, the trade is marked as completed
     */
    public function confirmReceipt($offerId)
    {
        try {
            $offer = TradeOffer::findOrFail($offerId);
            $userId = Auth::id();
            
            // Verify the user is part of this trade
            if ($offer->sender_id !== $userId && $offer->recipient_id !== $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not part of this trade.'
                ], 403);
            }
            
            // Verify the offer is accepted (not pending or completed)
            if ($offer->status !== 'accepted') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only accepted offers can be confirmed.'
                ], 400);
            }

            // Check if user already confirmed
            if ($offer->hasUserConfirmed($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already confirmed receipt.'
                ], 400);
            }

            // Mark user's confirmation
            if ($userId === $offer->sender_id) {
                $offer->sender_confirmed = true;
                $offer->sender_confirmed_at = now();
            } else {
                $offer->recipient_confirmed = true;
                $offer->recipient_confirmed_at = now();
            }

            // Check if both parties have confirmed
            if ($offer->isBothConfirmed()) {
                $offer->status = 'completed';
                $offer->completed_at = now();
                $message = 'Trade completed successfully! Both parties have confirmed receipt.';
            } else {
                $message = 'Receipt confirmed. Waiting for the other party to confirm.';
            }

            $offer->save();
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'bothConfirmed' => $offer->isBothConfirmed()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm receipt: ' . $e->getMessage()
            ], 500);
        }
    }
}
