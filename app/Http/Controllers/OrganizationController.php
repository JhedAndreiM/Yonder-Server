<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function dashboard(){
        $products = Product::where('user_id', 3)->get();
        return view('organization.dashboard', compact('products'));
        
    }
    
}
