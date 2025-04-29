<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function dashboard(){
        $products = Product::where('user_id', Auth::id())->get();
        return view('organization.dashboard', compact('products'));
        
    }
    
}
