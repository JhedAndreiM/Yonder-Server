<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function dashboard(){
        $products = Product::where('user_id', Auth::id())->get();
        return view('organization.dashboard', compact('products'));
        
    }
    public function update(Request $request){
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
}
