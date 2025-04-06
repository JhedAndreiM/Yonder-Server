<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function showMainPage(Request $request)
    {
        $page=$request->get('page', 1);
        $filters = $request->get('filters', []);
        
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
        } 
        $filters = array_map('trim', $filters);
        $query = Product::query();

        //supplier type
        if(in_array('verified',$filters)){
            $query->where('supplierTypes','verified');
            
        }
        if(in_array('students',$filters)){
            $query->where('supplierTypes','students');
        }

        //production
        if(in_array('used',$filters)){
            $query->where('product_condition','used');
            
        }
        if(in_array('new',$filters)){
            $query->where('product_condition','new');
        }
        if(in_array('like-new',$filters)){
            $query->where('product_condition','like-new');
        }
        //mde of transaction
        if(in_array('pickup',$filters)){
            $query->where('mode_of_transaction','pickup');
            
        }
        if(in_array('deliver',$filters)){
            $query->where('mode_of_transaction','deliver');
        }
        if(in_array('meetup',$filters)){
            $query->where('mode_of_transaction','meetup');
        }

         //colleges
        if(in_array('ccst',$filters)){
            $query->where('college','ccst');
        }
        if(in_array('cea',$filters)){
            $query->where('college','cea');
        }
        if(in_array('cba',$filters)){
            $query->where('college','cba');
        }
        if(in_array('ctech',$filters)){
            $query->where('college','ctech');
        }
        if(in_array('cahs',$filters)){
            $query->where('college','cahs');
        }
        if(in_array('cas',$filters)){
            $query->where('college','cas');
        }
        $products = $query->paginate(8);
        if ($request->ajax()) {
            return view('partials.productList', compact('products'))->render();
        }
        return view('mainPage', compact('products'));
        // if ($request->ajax()) {
            
        //     $page = $request->get('page', 1);

            
        //     $products = Product::paginate(8, ['*'], 'page', $page);

            
        //     return view('partials.productList', compact('products'))->render();
        // }

        
        // $products = Product::paginate(8);

        // return view('mainPage', compact('products'));
    }

    
}
