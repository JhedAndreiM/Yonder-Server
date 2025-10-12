<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
   
    public function showMainPage(Request $request)
    {
        $page=$request->get('page', 1);
        $filters = $request->get('filters', []);
        $topFilter = $request->query('topFilter');
        if ($topFilter) {
        session(['topFilter' => $topFilter]); 
        } else {
            $topFilter = session('topFilter', 'featured'); 
        }
        if (
        ($topFilter === 'marketplace' && !\App\Models\disableButtons::getValue('show_marketplace')) ||
        ($topFilter === 'student-org' && !\App\Models\disableButtons::getValue('show_student_org'))
        ) {
            session()->forget('topFilter');
            $topFilter = 'featured';
            session(['topFilter' => $topFilter]);
        }
        $minPrice = (float) $request->get('price', ['min' => null])['min'];  
        $maxPrice = (float) $request->get('price', ['max' => null])['max'];
        $sort = $request->get('sort');
        $search = request('searching');
        $search = trim($search, '"');
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
        } 
        $filters = array_map('trim', $filters);
        if ($topFilter) {
            session(['topFilter' => $topFilter]); 
        } else {
            $topFilter = session('topFilter', 'featured'); 
        }
        $query = Product::query();
        $query->where('product.user_id', '!=', Auth::id());
        // Exclude products from users who are banned or suspended
        $query->whereHas('user', function($q){
            $q->whereNotIn('role', ['banned', 'suspended']);
        });

        // Filter by role 'approved'
        $query->where('product.approved', 'yes');
        if ($topFilter) {
            if($topFilter==="featured"){
                $query->where('product.supplier_type', 'pben');
            }
            if($topFilter==="student-org"){
                $query->where('product.supplier_type', 'student-org');
            }
            if($topFilter==="marketplace"){
                $query->where('product.supplier_type', 'marketplace');
            }
        }
        else{
            $query->where('product.supplier_type', 'verified');
        }

        if($search !== ""){
            $query->where(function($q) use ($search) {
        $q->where('product.name', 'like', '%' . $search . '%')
          ->orWhere('product.description', 'like', '%' . $search . '%')
          ->orWhereHas('tags', function($tagQuery) use ($search) {
              $tagQuery->where('name', 'like', '%' . $search . '%');
          });
        });
        }

        //sort filter
        if($sort !== null){
            if($sort=="lowToHigh"){
                $query->orderBy('product.price', 'asc');
            }
            if($sort=="highToLow"){
                $query->orderBy('product.price', 'desc');
            }
            if($sort=="newFirst"){
                $query->orderBy('product.created_at', 'desc');
            }
            if($sort=="oldFirst"){
                $query->orderBy('product.created_at', 'asc');
            }
        }
        // price filter
        if ($minPrice !== null) {
            $query->where('product.price', '>=', $minPrice);
        }
        if ($maxPrice !== 0.0) {
            $query->where('product.price', '<=', $maxPrice);
        }
        //supplier type
        
        $supplierTypeFilters=['verified', 'students'];
        $selectedSupplierTypes=array_intersect($filters,$supplierTypeFilters);
        if(!empty($selectedSupplierTypes)){
            $query->where(function ($q) use ($selectedSupplierTypes) {
                foreach ($selectedSupplierTypes as $Types) {
                    $q->orWhere('product.supplier_type', 'LIKE', "%$Types%");
                }
            });
        }
        
        //condition
        $conditionFilters=['used', 'new', 'like-new'];
        $selectedConditions=array_intersect($filters, $conditionFilters);
        if(!empty($selectedConditions)){
            $query->where(function ($q) use ($selectedConditions) {
                foreach ($selectedConditions as $condition) {
                    $q->orWhere('product.product_condition', 'LIKE', "%$condition%");
                }
            });
        }
        
        //mde of transaction
        $transactionFilters=['pickup', 'deliver', 'meetup'];
        $selectedTransaction=array_intersect($filters, $transactionFilters);
        if(!empty($selectedTransaction)){
            $query->where(function ($q) use ($selectedTransaction) {
                foreach ($selectedTransaction as $transaction) {
                    $q->orWhere('product.mode_of_transaction', 'LIKE', "%$transaction%");
                }
            });
        }
        
         //colleges - now filtering by tags instead of college column
         $collegeFilters = array_map('strtolower', DB::table('colleges')->pluck('code')->toArray());
         $selectedColleges = array_intersect($filters, $collegeFilters);
         
         if (!empty($selectedColleges)) {
            $query->whereHas('tags', function ($tagQuery) use ($selectedColleges) {
                $tagQuery->where(function ($q) use ($selectedColleges) {
                    foreach ($selectedColleges as $college) {
                        // Get the full college name for more comprehensive matching
                        $collegeRecord = DB::table('colleges')->where('code', $college)->first();
                        $collegeName = $collegeRecord ? strtolower($collegeRecord->name) : '';
                        
                        // Match by college code
                        $q->orWhere('name', 'LIKE', "%$college%");
                        
                        if ($collegeName) {
                            // Bidirectional matching for college names
                            // 1. Tag contains part of college name (e.g., tag "College of Engineering" matches "College of Engineering and Architecture")
                            $q->orWhere('name', 'LIKE', "%$collegeName%");
                            
                            // 2. College name contains part of tag (e.g., college "College of Engineering and Architecture" matches tag "College of Engineering")
                            // Use whereRaw with proper parameter binding
                            $q->orWhereRaw('? LIKE CONCAT("%", LOWER(name), "%")', [$collegeName]);
                            
                            // 3. Split college name into words for more flexible matching
                            $collegeWords = explode(' ', $collegeName);
                            if (count($collegeWords) > 1) {
                                foreach ($collegeWords as $word) {
                                    $word = trim($word);
                                    if (strlen($word) > 2) { // Skip small words like "of", "and"
                                        $q->orWhere('name', 'LIKE', "%$word%");
                                    }
                                }
                            }
                        }
                    }
                });
            });
        }


        // for org filtering
        $studentOrgFilters = DB::table('student_orgs')->pluck('id')->toArray();
        $selectedStudentOrgs = array_intersect($filters, $studentOrgFilters);
        if (!empty($selectedStudentOrgs)) {
            $query->where(function ($q) use ($selectedStudentOrgs) {
                $q->whereIn('product.organization_id', $selectedStudentOrgs);
            });
        }

        //for
        $saleTradeFilters = ['sale', 'trade'];
        $selectedSaleTradeFilter=array_intersect($filters, $saleTradeFilters);
        if(!empty($selectedSaleTradeFilter)){
            $query->where(function ($q) use ($selectedSaleTradeFilter) {
                foreach ($selectedSaleTradeFilter as $saleTrade) {
                    $q->orWhere('product.forSaleTrade', 'LIKE', "%$saleTrade%");
                }
            });
        }

        // Default sorting by sales count (high-selling products first) when no specific sort is applied
        if ($sort === null) {
            $query->leftJoin('cart_items', function($join) {
                $join->on('product.product_id', '=', 'cart_items.product_id')
                     ->where('cart_items.status', '=', 'completed');
            })
            ->select('product.*', DB::raw('COUNT(cart_items.id) as sales_count'))
            ->groupBy('product.product_id')
            ->orderByDesc('sales_count')
            ->orderByDesc('product.created_at'); // Secondary sort by newest if sales are equal
        }

        //dd($query);
        $wishlist = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();
        $featuredImages = FeaturedImage::with('product')->latest()->take(5)->get();
        $products = $query->get();
        foreach ($products as $product) {
          $product->average_rating=DB::table('reviews')
          ->where('product_id', $product->product_id)
          ->avg('rating');  
        
        }
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
            $notifications = $notifications->map(function($notification) {
            return [
                'title' => $notification->title,
                'message' => $notification->message,
                'time_ago' => Carbon::parse($notification->created_at)->diffForHumans(),
            ];
        });
        if ($request->ajax()) {
            return view('partials.productList', compact('products','featuredImages','wishlist', 'notifications'))->render();
        }
        
        return view('mainPage', compact('products', 'featuredImages','wishlist', 'notifications'));
    }

    
}
