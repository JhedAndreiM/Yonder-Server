<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
   
    public function showMainPage(Request $request)
    {
        $page=$request->get('page', 1);
        $filters = $request->get('filters', []);
        $minPrice = (float) $request->get('price', ['min' => null])['min'];  
        $maxPrice = (float) $request->get('price', ['max' => null])['max'];
        $sort = $request->get('sort');
        $search = request('searching');
        $search = trim($search, '"');
        
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
        } 
        $filters = array_map('trim', $filters);
        
        $query = Product::query();

        // Filter by role 'approved'
        $query->where('approved', 'yes');

        if($search !== ""){
            $query->where('name', 'like', '%' . $search . '%');
        }
        //sort filter
        if($sort !== null){
            if($sort=="lowToHigh"){
                $query->orderBy('price', 'asc');
            }
            if($sort=="highToLow"){
                $query->orderBy('price', 'desc');
            }
            if($sort=="newFirst"){
                $query->orderBy('created_at', 'desc');
            }
            if($sort=="oldFirst"){
                $query->orderBy('created_at', 'asc');
            }
        }
        // price filter
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== 0.0) {
            $query->where('price', '<=', $maxPrice);
        }
        //supplier type
        
        $supplierTypeFilters=['verified', 'students'];
        $selectedSupplierTypes=array_intersect($filters,$supplierTypeFilters);
        if(!empty($selectedSupplierTypes)){
            $query->where(function ($q) use ($selectedSupplierTypes) {
                foreach ($selectedSupplierTypes as $Types) {
                    $q->orWhere('supplier_type', 'LIKE', "%$Types%");
                }
            });
        }
        
        //condition
        $conditionFilters=['used', 'new', 'like-new'];
        $selectedConditions=array_intersect($filters, $conditionFilters);
        if(!empty($selectedConditions)){
            $query->where(function ($q) use ($selectedConditions) {
                foreach ($selectedConditions as $condition) {
                    $q->orWhere('product_condition', 'LIKE', "%$condition%");
                }
            });
        }
        
        //mde of transaction
        $transactionFilters=['pickup', 'deliver', 'meetup'];
        $selectedTransaction=array_intersect($filters, $transactionFilters);
        if(!empty($selectedTransaction)){
            $query->where(function ($q) use ($selectedTransaction) {
                foreach ($selectedTransaction as $transaction) {
                    $q->orWhere('mode_of_transaction', 'LIKE', "%$transaction%");
                }
            });
        }
        
         //colleges
         $collegeFilters = ['ccst', 'cea', 'cba', 'ctech', 'cahs', 'cas'];
         $selectedColleges = array_intersect($filters, $collegeFilters);
         
         if (!empty($selectedColleges)) {
            $query->where(function ($q) use ($selectedColleges) {
                foreach ($selectedColleges as $college) {
                    $q->orWhere('colleges', 'LIKE', "%$college%");
                }
            });
        }


        //for
        $saleTradeFilters = ['sale', 'trade'];
        $selectedSaleTradeFilter=array_intersect($filters, $saleTradeFilters);
        if(!empty($selectedSaleTradeFilter)){
            $query->where(function ($q) use ($selectedSaleTradeFilter) {
                foreach ($selectedSaleTradeFilter as $saleTrade) {
                    $q->orWhere('colleges', 'LIKE', "%$saleTrade%");
                }
            });
        }

        //dd($query);
        $products = $query->paginate(8);
        if ($request->ajax()) {
            return view('partials.productList', compact('products'))->render();
        }
        $featuredImages = FeaturedImage::latest()->take(5)->get();
        return view('mainPage', compact('products', 'featuredImages'));
    }

    
}
