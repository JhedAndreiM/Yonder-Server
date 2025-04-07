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
        $supplierTypeFilters=['verified', 'students'];
        $selectedSupplierTypes=array_intersect($filters,$supplierTypeFilters);
        if(!empty($selectedSupplierTypes)){
            $query->whereIn('supplier_type',$selectedSupplierTypes);
        }

        //condition
        $conditionFilters=['used', 'new', 'like-new'];
        $selectedConditions=array_intersect($filters, $conditionFilters);
        if(!empty($selectedConditions)){
            $query->whereIn('product_condition', $selectedConditions);
        }
        
        //mde of transaction
        $transactionFilters=['pickup', 'deliver', 'meetup'];
        $selectedTransaction=array_intersect($filters, $transactionFilters);
        if(!empty($selectedTransaction)){
            $query->whereIn('mode_of_transaction', $selectedTransaction);
        }
        
         //colleges
         $collegeFilters = ['ccst', 'cea', 'cba', 'ctech', 'cahs', 'cas'];
         $selectedColleges = array_intersect($filters, $collegeFilters);
         
         if (!empty($selectedColleges)) {
             $query->whereIn('colleges', $selectedColleges);
         }


        //for
        $saleTradeFilters = ['sale', 'trade'];
        $selectedSaleTradeFilter=array_intersect($filters, $saleTradeFilters);
        if(!empty($selectedSaleTradeFilter)){
            $query->whereIn('forSaleTrade', $selectedSaleTradeFilter);
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
