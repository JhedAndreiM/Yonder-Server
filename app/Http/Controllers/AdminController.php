<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\FeaturedImage;

class AdminController extends Controller
{
    public function dashboard(){
        $featuredImages = FeaturedImage::latest()->take(5)->get();
        $products = Product::where('approved', 'not')->get();
    
        return view('admin.dashboard', compact('featuredImages', 'products'));
    }


    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->approved = 'yes';
        $product->save();

        return redirect()->back()->with('success', 'Product approved!');
    }
}
