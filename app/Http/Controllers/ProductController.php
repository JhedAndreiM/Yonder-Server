<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'productName' => 'required|string|max:255',
            'productPrice' => 'required|numeric',
            'productStocks' => 'required|integer',
            'productDescription' => 'required|string',
            'productImage' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'filters' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('productImage')) {
            $imagePath = $request->file('productImage')->store('uploads', 'public');
        }

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
        $product->image_path = $imagePath;
        $product->user_id = Auth::id(); 

        
        $product->product_condition = implode(',', $selected_condition);
        $product->colleges = implode(',', $selected_colleges);
        $product->forSaleTrade = implode(',', $selected_forSaleTrade);

        $product->save();

        return redirect()->route('student.dashboard')->with('success', 'Product listed successfully!');
    }
}
