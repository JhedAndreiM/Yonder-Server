<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\ProductImage;
use App\Models\Tag;
use App\Models\College;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Events\NewNotification;
use App\Models\User;

class ProductController extends Controller
{
public function store(Request $request)
{
      //dd($request->all(), $request->file('images'));
        $validated = $request->validate([
            'name'=> 'required|string|max:50',
            'price'=> 'required|numeric|min:0',
            'stock'=> 'required|integer|min:0',
            'description'=> 'required|string',
            'supplier_type'=> 'required|in:pben,student-org,marketplace',
            'organization_id'=> 'required_if:supplier_type,student_org|exists:student_orgs,id',
            'variants_json'=> 'nullable|string',
            'colleges_json'=> 'nullable|string',
            'tags_json'=> 'nullable|string',
            'tradeOrSell'=> 'string',
            'productQuality' => 'string',
            'images'=> 'required|array|min:1',
            'images.*'=> 'image|mimes:jpeg,png,webp',
        ]);

    $variantsData = null;
    if (!empty($validated['variants_json'])) {
        $variantsData = json_decode($validated['variants_json'], true);
    }

   // adding of info :P
    $product = new Product();
    $product->supplier_type = $validated['supplier_type'];
    $product->name = $validated['name'];
    $product->description = $validated['description'];
    $product->variants = $variantsData;
    $product->stock = $validated['stock'];
    $product->price = $validated['price'];
    $product->forSaleTrade = $validated['tradeOrSell'] ?? 'sale';
    $product->product_condition = $validated['productQuality'] ?? 'new';
    $product->approved = Auth::user()->role === 'organization' ? "yes" : "not";
    $product->organization_id = $validated['organization_id'] ?? null;
    $product->user_id = Auth::id(); 
    $product->save();
    $adminUserId=User::where('role', 'admin')->first();
    $notification = Notification::create([
        'user_id' => $adminUserId->id,
        'title' => 'Product Approval',
        'message' => 'Product "' . $product->name . '" is ready for approval.',
        'is_read' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    event(new NewNotification($notification));
    if ($variantsData
        && isset($variantsData['name'], $variantsData['options'])
        && is_array($variantsData['options'])) {


        $optionStocks = $variantsData['optionStocks'] ?? [];
        foreach ($variantsData['options'] as $i => $option) {
        \App\Models\ProductVariant::create([
        'product_id' => $product->product_id,
        'variant_name' => $variantsData['name'],
        'variant_option' => $option,
        'stock' => (int) ($optionStocks[$i] ?? 0),
        'critical_level' => 0,
        'lead_time' => 7,
        'safety_stock' => 10,
        'critical_mode' => 'automatic',
        ]);
        }
    }
   // adding of image in product_image
   if ($request->hasFile('images')) {
        $imagePaths = [];
        try {
            foreach($request->file('images') as $image){
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            DB::table('product_images')->insert([
                'product_id' => $product->product_id,
                'image_path' =>  $imageName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['productImage' => 'Attach Image!']);
        }
    }

    if (!empty($validated['tags_json'])) {
    $tags = json_decode($validated['tags_json'], true); 
    $tagIds = [];

    foreach ($tags as $tagItem) {
    $tagName = is_array($tagItem) ? ($tagItem['name'] ?? '') : $tagItem;
    $tagName = strtolower(trim($tagName));

    if ($tagName === '') continue;

    $tag = Tag::firstOrCreate(
        ['name' => $tagName],
        [
            'usage_count' => 0,
            'is_admin' => Auth::user()->role === 'admin',
        ]
    );

    $tagIds[] = $tag->id;
    //$tag->increment('usage_count');
    }

    $product->tags()->syncWithoutDetaching($tagIds);
    }

    // Para sa subject ni Sir Ross
    // para to icheck muna if my gantong filename naba sa FileForProduct
    $filename = "FileForProduct/".date('M_Y').".txt";
    if(file_exists($filename)){
        $listOfItemsThisMonth = fopen("FileForProduct/".date('M_Y').".txt", "a") or die("Unable to open file!");
    }
    else{
        $listOfItemsThisMonth = fopen("FileForProduct/".date('M_Y').".txt", "w") or die("Unable to open file!");
        $header = str_pad("Product Name", 50) . str_pad("Stock", 10) . str_pad("Price", 10). "\n";
        fwrite($listOfItemsThisMonth, $header);
    }

    $productPerLine= str_pad($validated['name'], 50).str_pad($validated['stock'], 10).str_pad("P ". $validated['price'], 10);
    fwrite($listOfItemsThisMonth, $productPerLine);
    fclose($listOfItemsThisMonth);
    // End ng para sa subject ni Sir Ross

     $user = Auth::user();
        // route to para if studnet or organization nag gawa 
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard')->with('success', 'Product listed successfully!');
        } 
        elseif ($user->role === 'organization') {
        return redirect()->route('organization.dashboard')->with('success', 'Product listed successfully!');
        }
    }
    
    // part ng act kay sir ross
    public function uploadFile(Request $request){
        $upload_folder="FileForProduct/";
        $uploaded_file= $upload_folder . basename($_FILES["myfile"]["name"]);

        if(file_exists($uploaded_file)){
            echo 'The file already exists';
            return redirect()->back()->with('alreadyExists', 'The file already exists!');
        }

        if(move_uploaded_file($_FILES["myfile"]["tmp_name"], $uploaded_file)){
            echo 'File has been successfully uploaded';
            return redirect()->back()->with('success', 'File has been successfully uploaded!');
        }
        else{
            return redirect()->back()->with('error', 'File has failed to upload!');
        }
    }
    
    public function edit($id)
    {
        $items = \DB::table('product')
        ->where('product_id', $id)
        ->where('user_id', auth()->id())
        ->first();

        if (!$items) {
            abort(403);
        }

        $images = DB::table('product_images')
            ->where('product_id', $id)
            ->get();
        $itemTags = DB::table('product_tag')
        ->join('tags', 'product_tag.tag_id', '=', 'tags.id')
        ->where('product_tag.product_id', $items->product_id)
        ->select('tags.id', 'tags.name')
        ->get();
        return view('editListing', compact('items', 'images', 'itemTags'));
    }

    public function update(Request $request, $id){
        $validated = $request->validate([
            'name'=> 'required|string|max:50',
            'price'=> 'required|numeric|min:0',
            'stock'=> 'required|integer|min:0',
            'description'=> 'required|string',
            'supplier_type'=> 'required|in:pben,student-org,marketplace',
            'organization_id'=> 'required_if:supplier_type,student_org|exists:student_orgs,id',
            'variants_json'=> 'nullable|string',
            'colleges_json'=> 'nullable|string',
            'tags_json'=> 'nullable|string',
            'tradeOrSell'=> 'required|string',
            'productQuality' => 'required|string',
            'images'=> 'required|array|min:1',
            'images.*'=> 'image|mimes:jpeg,png,webp',
        ]);
        $product = Product::findOrFail($id);
        // Handle variants
        if (!empty($validated['variants_json'])) {
            $variantsData = json_decode($validated['variants_json'], true);
        }

        // Handle colleges
        $colleges = $validated['colleges_json'] ?? '[]';
        $collegesArray = json_decode($colleges, true); 
        $collegesString = is_array($collegesArray) ? implode(',', $collegesArray) : '';

        // Update fields
        $product->supplier_type = $validated['supplier_type'];
        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->variants = $variantsData ?? null;
        $product->stock = $validated['stock'];
        $product->price = $validated['price'];
        $product->forSaleTrade = $validated['tradeOrSell'];
        $product->product_condition = $validated['productQuality'];
        $product->organization_id = $validated['organization_id'] ?? null;
        $product->approved = Auth::user()->role === 'organization' ? "yes" : "not";
        $product->updated_at = now();
        $product->save();

        // IMAGE HANDLING
        if ($request->hasFile('images')) {
            // option A: delete old images first
            DB::table('product_images')->where('product_id', $product->product_id)->delete();

            foreach($request->file('images') as $image){
                $imageName = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('images'), $imageName);

                DB::table('product_images')->insert([
                    'product_id' => $product->product_id,
                    'image_path' => $imageName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ✅ TAG HANDLING
        if (!empty($validated['tags_json'])) {
            $tags = json_decode($validated['tags_json'], true); 
            $tagIds = [];

            foreach ($tags as $tagItem) {
                $tagName = is_array($tagItem) ? ($tagItem['name'] ?? '') : $tagItem;
                $tagName = strtolower(trim($tagName));

                if ($tagName === '') continue;

                $tag = Tag::firstOrCreate(
                    ['name' => $tagName],
                    [
                        'usage_count' => 0,
                        'is_admin' => Auth::user()->role === 'admin',
                    ]
                );

                $tagIds[] = $tag->id;
            }

            // overwrite old tags with new ones
            $product->tags()->sync($tagIds); 
        }

        // Redirect based on role
        $user = Auth::user();
        if ($user->role === 'student') {
            return redirect()->route('listing.seller')->with('success', 'Product updated successfully!');
        } elseif ($user->role === 'organization') {
            return redirect()->route('organization.dashboard')->with('success', 'Product updated successfully!');
        }

    }



    public function show($id)
    {
        $products = Product::with('user')->findOrFail($id);
        $availableVouchers = Voucher::where('user_id', Auth::id())
        ->where('status', 'available')
        ->where('seller_id', $products->user_id) 
        ->get();

        $reviews = DB::table('reviews')
    ->join('users', 'reviews.user_id', '=', 'users.id')
    ->join('product', 'reviews.product_id', '=', 'product.product_id')
    ->where('product.product_id', $id)
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
    $sellerId = $products->user_id;
    $sellerRating = DB::table('reviews')
        ->join('product', 'reviews.product_id', '=', 'product.product_id')
        ->where('product.user_id', $sellerId)
        ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
        ->first();


    return view('productDetails', compact('products','availableVouchers', 'reviews','sellerRating', 'sellerId'));
    }

    public function dashboardForUserSeller()
    {
        $products = Product::where('user_id', Auth::id())->get();
        $productIds = $products->pluck('product_id');
        $images = DB::table('product_images')
            ->whereIn('product_id', $productIds)
            ->get();
        
        // Get rejection messages for rejected products
        $rejections = DB::table('product_rejections')
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');
        
        $sellerId = Auth::id();
        $sellerRating = DB::table('reviews')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $sellerId)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
            ->first();
        return view('listings', compact('products', 'sellerRating', 'images', 'rejections'));
    }

    public function destroyListing(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->delete();

        $products = Product::where('user_id', Auth::id())->get();
        return redirect()->route('listing.seller')->with('success', 'Product listed successfully!');
    }
}
