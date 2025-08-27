<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\CheapGlobalSmsService;
use Illuminate\Support\Facades\Log;
use App\Models\Tag;

class AdminController extends Controller
{
    public function dashboard(){
        $featuredImages = DB::table('featured_images')
        ->limit(5)
        ->orderBy('created_at', 'desc')
        ->get();
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
        $users = User::where('role', '!=', 'admin')->get();

        $reports = DB::table('reports')
        ->join('product', 'reports.report_id', '=', 'product.product_id')
        ->join('users', 'product.user_id', '=', 'users.id')
        ->select(
            'reports.id as report_id',
            'reports.report_id as report_id_item',
            'reports.message',
            'product.name as product_name',
            'product.description',
            'product.image_path',
            'users.name as reporter_name',
            'users.last_name as reporter_last_name'
        )
        ->get();
        $productPolicies = DB::table('product_policies')->get();
        $voucherList = DB::table('voucherList')->get();
        $creditPercentage = DB::table('credit_settings')->first();
        return view('admin.dashboard', compact('featuredImages', 'products','notifications', 'users','reports','productPolicies', 'voucherList', 'creditPercentage'));
    }

    public function productPolicy(Request $request){
        $validated=$request->validate([
            'descriptionAllowed' => 'required|string|min:5',
            'descriptionProhibited' => 'required|string|min:5'
        ],[
            'descriptionAllowed.required' => '"Allowed" Listing is required',
            'descriptionAllowed.min' => '"Allowed" Listing  must be at least :min characters',
            'descriptionProhibited.required' => '"Prohibited" Listing is required',
            'descriptionAllowed.min' => '"Prohibited" Listing  must be at least :min characters',
        ]);

        DB::table('product_policies')->updateOrInsert(
            ['type' => 'allowed'],
            [
                'content' => $validated['descriptionAllowed'],
                'updated_at'=> now()
            ]
        );

        DB::table('product_policies')->updateOrInsert(
            ['type' => 'prohibited'],
            [
                'content' => $validated['descriptionProhibited'],
                'updated_at'=> now()
            ]
        );
        return redirect()->back()->with('success', 'Product policies updated successfully!');

    }
    public function approveProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->approved = 'yes';
        $product->save();

        $tags = Tag::whereHas('products', function ($query) {
            $query->where('approved', 'yes');
        })->get();
        
        foreach ($product->tags as $tag) {
            $tag->increment('usage_count');
        }
        Log::info('Parsed tags:', $tags->toArray());

        $user = $product->user_id;
        DB::table('notifications')->insert([
            'user_id' => $user,
            'title' => 'Product Approved',
            'message' => 'Your product "'.$product->name.'" has been approved.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('sms_notifLogs')->insert([
        'from_id' => Auth::id(), 
        'to_id' => $user,
        'message' => 'Your product "' . $product->name . '" has been approved and is now visible to other students.',
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        // $smsService = app(\App\Services\IprogSmsService::class);
        // $response = $smsService->send('09484386078', 'Your verification code is wtf');
        return response()->json(['message' => 'Tags received and product approved']);
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

public function changeUserRole(Request $request)
{
    $user = User::findOrFail($request->user_id);
    $user->role = $request->role;
    $user->save();

    return redirect()->back()->with('success', 'User role updated successfully.');
}
public function allowReport($id)
{
    DB::table('reports')->where('id', $id)->delete();
    return response()->json(['success' => true]);
}

public function deleteProduct($id)
{
    // Delete the product
    DB::table('product')->where('product_id', $id)->delete();

    // Optionally delete related reports
    DB::table('reports')->where('report_id', $id)->delete();

    return response()->json(['success' => true]);
}


public function updateDisabledButton(Request $request)
{
    if (DB::table('disable_buttons')->where('key', 'show_student_org')->exists()) {
        DB::table('disable_buttons')
            ->where('key', 'show_student_org')
            ->update(['value' => $request->has('show_student_org')]);
    } else {
        DB::table('disable_buttons')
            ->insert(['key' => 'show_student_org', 'value' => $request->has('show_student_org')]);
    }

    // Marketplace toggle
    if (DB::table('disable_buttons')->where('key', 'show_marketplace')->exists()) {
        DB::table('disable_buttons')
            ->where('key', 'show_marketplace')
            ->update(['value' => $request->has('show_marketplace')]);
    } else {
        DB::table('disable_buttons')
            ->insert(['key' => 'show_marketplace', 'value' => $request->has('show_marketplace')]);
    }
    session()->forget('topFilter');
    return redirect()->back()->with('success', 'Button visibility settings updated!');
}

public function addVoucherList(Request $request){
    $request->validate([
        'voucherAmount' => 'required|min:1',
        'voucherPrice' => 'required|min:1'
    ]);
    DB::table('voucherList')->insert([
        'amount' => $request->voucherAmount,
        'price' => $request->voucherPrice,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    return redirect()->back()->with('voucher_success', 'New Voucher uploaded successfully!');
}

public function editCreditPercentage(Request $request){
    $request->validate([
        'percentage' => 'required|min:1'
    ]);

    DB::table('credit_settings')
    ->where('id', 1)
    ->update([
        'percentage' => $request->percentage,
        'updated_at' => now()
    ]);
    return back()->with('credit_success', 'Credit percentage updated successfully.');
} 

}
