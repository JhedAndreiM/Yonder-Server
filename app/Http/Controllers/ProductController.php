<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'productPrice' => 'required|numeric',
            'productStocks' => 'required|integer',
            'productDescription' => 'required|string',
            'productImage.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'filters' => 'nullable|string',
        ]);

        $imagePaths = [];

foreach($request->file('productImage') as $image){
    $imageName = time().'_'.$image->getClientOriginalName();
    $image->move(public_path('images'), $imageName);
    $imagePaths[] = $imageName;
}
        // $imagePath = null;
        // if ($request->hasFile('productImage')) {
        //     $imagePath = $request->file('productImage')->store('uploads', 'public');
        // }

        $filters = json_decode($request->input('filters'), true) ?? [];

        $product_condition = ['used', 'new', 'like-new'];
        $colleges = ['ccst', 'cea', 'cba', 'ctech', 'cahs', 'cas'];
        $forSaleTrade = ['sale', 'trade'];

        $selected_condition = array_values(array_intersect($filters, $product_condition));
        $selected_colleges = array_values(array_intersect($filters, $colleges));
        $selected_forSaleTrade = array_values(array_intersect($filters, $forSaleTrade));

        $product = new Product();
        $product->name = $validated['productName'];
        $product->price = $validated['productPrice'];
        $product->stock = $validated['productStocks'];
        $product->description = $validated['productDescription'];
        $product->image_path = implode(',', $imagePaths);
        $product->user_id = Auth::id(); 

        
        $product->product_condition = implode(',', $selected_condition);
        $product->colleges = implode(',', $selected_colleges);
        $product->forSaleTrade = implode(',', $selected_forSaleTrade);
        if (Auth::id() == 5) {
            $product->supplier_type = 'verified';
        } else {
            $product->supplier_type = 'students';
        }
        $product->save();

        $user = Auth::user();
        // route to para if studnet or organization nag gawa 
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard')->with('success', 'Product listed successfully!');
        } 
        elseif ($user->role === 'organization') {
        return redirect()->route('organization.dashboard')->with('success', 'Product listed successfully!');
        }
    }

    public function show($id)
    {
        $products = Product::with('user')->findOrFail($id);
        $availableVouchers = Voucher::where('user_id', Auth::id())
        ->where('status', 'available')
        ->where('seller_id', $products->user_id) 
        ->get();

    return view('productDetails', compact('products','availableVouchers'));
    }


}
