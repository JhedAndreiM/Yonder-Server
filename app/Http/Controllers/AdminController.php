<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(){
        $featuredImages = FeaturedImage::latest()->take(5)->get();
        $products = Product::where('approved', 'not')->get();
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
        return view('admin.dashboard', compact('featuredImages', 'products','notifications'));
    }


    public function approveProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->approved = 'yes';
        $product->save();
        $user = $product->user_id;
        DB::table('notifications')->insert([
            'user_id' => $user,
            'title' => 'Product Approved',
            'message' => 'Your product "'.$product->name.'" has been approved.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Product approved!');
    }
    public function reject(Request $request, $id)
    {
    $product = Product::findOrFail($id);
    
    $message = $request->input('message');
    DB::table('product')
        ->where('product_id', $id)
        ->update(['approved' => 'rejected']);
    
    DB::table('product_rejections')->insert([
        'product_id' => $id,
        'message' => $message,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $user = $product->user_id;
    DB::table('notifications')->insert([
            'user_id' => $user,
            'title' => 'Product Rejected',
            'message' => 'Your product "'.$product->name.'" has been rejected. Reason: '.$message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    return response()->json(['success' => true]);
    }
}
