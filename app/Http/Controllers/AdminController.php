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
use App\Models\FaqQuestion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\Tag;
use App\Models\Notification;
use App\Events\NewNotification;
use App\Models\UserReport;

class AdminController extends Controller
{
    public function dashboard(){
        $featuredImages = FeaturedImage::with('product')
        ->limit(5)
        ->orderBy('created_at', 'desc')
        ->get();
        $products = Product::where('approved', 'not')->get();
        $approvedProducts = Product::where('approved', 'yes')->get();
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
            ->leftJoin('product_images', 'product.product_id', '=', 'product_images.product_id')
            ->select(
                'reports.report_id as report_id',
                'reports.message',
                'product.product_id',
                'product.name as product_name',
                'product.description',
                'users.name as reporter_name',
                'users.last_name as reporter_last_name',
                DB::raw('GROUP_CONCAT(product_images.image_path) as images') // all images as one string
            )
            ->groupBy(
                'reports.report_id',
                'reports.message',
                'product.product_id',
                'product.name',
                'product.description',
                'users.name',
                'users.last_name'
            )
            ->get();
        $productPolicies = DB::table('product_policies')->get();
        $voucherList = DB::table('voucherList')->get();
        $collegeList = DB::table('colleges')->get();
        $studOrgList = DB::table('student_orgs')->get();
        $categoryList = DB::table('faq_categories')->get();
        $questions = FaqQuestion::with('category')->get();
        $creditPercentage = DB::table('credit_settings')->first();
        $userReports = UserReport::with(['reportedUser', 'reporter'])->get();
        $tagss = Tag::orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('featuredImages', 'products', 'approvedProducts', 'notifications', 'users','reports','productPolicies', 'voucherList', 'creditPercentage', 'collegeList', 'studOrgList', 'categoryList', 'questions', 'userReports', 'tagss'));
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
        return redirect()->back()->with('product_policy_success', 'Product policies updated successfully!');

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

        DB::table('sms_notifLogs')->insert([
        'from_id' => Auth::id(), 
        'to_id' => $product->user_id,
        'message' => 'Your product "' . $product->name . '" has been approved and is now visible to other students.',
        'created_at' => now(),
        'updated_at' => now(),
        ]);

        // Create notification and fire event at the end
        $notification = Notification::create([
            'user_id' => $product->user_id,
            'title' => 'Product Approved',
            'message' => 'Your product "' . $product->name . '" has been approved.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new NewNotification($notification));
        $user = User::find($product->user_id);
        if($user && $user->phone_number){
            try {
                $smsService = app(\App\Services\IprogSmsService::class);
                $message = 'Your product "' . $product->name . '" has been approved and is now visible in the marketplace.';
                $response = $smsService->send($user->phone_number, $message);
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'to' => $user->phone_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else {
            Log::warning('No phone number found for user', ['user_id' => $product->user_id]);
        }
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
        // Create notification and fire event at the end
        $notification = Notification::create([
            'user_id' => $product->user_id,
            'title' => 'Product Rejected',
            'message' => 'Your product "' . $product->name . '" has been rejected. Reason: ' . $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new NewNotification($notification));
        $user = User::find($product->user_id);
        if($user && $user->phone_number){
            try {
                $smsService = app(\App\Services\IprogSmsService::class);
                $message = 'Your product "' . $product->name . '" has been rejected. Reason: ' . $message;
                $response = $smsService->send($user->phone_number, $message);
            } catch (\Exception $e) {
                Log::error('SMS sending failed', [
                    'to' => $user->phone_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        else {
            Log::warning('No phone number found for user', ['user_id' => $product->user_id]);
        }
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
    DB::table('reports')->where('report_id', $id)->delete();
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

public function deleteVoucher($id)
{
    DB::table('voucherList')->where('id', $id)->delete();
    return response()->json(['success' => true, 'message' => 'Voucher deleted successfully.']);
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

public function addCollege(Request $request){
    try {
        // Validate input
        $request->validate([
            'code' => 'required|unique:colleges,code',
            'name' => 'required'
        ]);

        // Insert into database
        DB::table('colleges')->insert([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('college_success', 'College added successfully!');

    } catch (ValidationException $e) {
        // Handles validation errors
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (QueryException $e) {
        // Handles database errors (like constraint violations)
        return redirect()->back()->with('college_error', 'Database error: ' . $e->getMessage());
    }

}

    public function updateUserName(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->save();

        return redirect()->back()->with('success', 'User name updated successfully.');
    }

    public function updateUserGender(Request $request, int $id)
    {
        $validated = $request->validate([
            'gender' => 'nullable|string|in:male,female,other',
        ]);

        $user = User::findOrFail($id);
        $user->gender = $validated['gender'] ?? null;
        $user->save();

        return redirect()->back()->with('success', 'User gender updated successfully.');
    }

    public function updateUserPassword(Request $request, int $id)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($validated['password']);
        if (isset($user->password_changed)) {
            $user->password_changed = true;
        }
        $user->save();

        return redirect()->back()->with('success', 'User password updated successfully.');
    }

    public function updateUserDetails(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'middle_name' => 'nullable|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->middle_name = $validated['middle_name'];
        $user->last_name = $validated['last_name'];
        $user->gender = $validated['gender'] ?? null;
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            if (isset($user->password_changed)) {
                $user->password_changed = true;
            }
        }
        $user->save();

        return redirect()->back()->with('user_success', 'User details updated successfully.');
    }
public function updateCollege(Request $request, $id)
{
    try {
        $request->validate([
            'code' => 'required|unique:colleges,code,' . $id,
            'name' => 'required'
        ]);

        DB::table('colleges')->where('id', $id)->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'College updated successfully!',
            'data' => [
                'id' => $id,
                'code' => $request->input('code'),
                'name' => $request->input('name')
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors()
        ], 422);
    }
}
public function deleteCollege($id)
{
    try {
        DB::table('colleges')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}
public function addStudOrg(Request $request){
    try {
        // Validate input
        $request->validate([
            'code' => 'required|unique:student_orgs,code',
            'name' => 'required'
        ]);

        // Insert into database
        DB::table('student_orgs')->insert([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('studOrg_success', 'Student Organization added successfully!');

    } catch (ValidationException $e) {
        // Handles validation errors
        return redirect()->back()->withErrors($e->errors())->withInput();

    } catch (QueryException $e) {
        // Handles database errors (like constraint violations)
        return redirect()->back()->with('studOrg_success', 'Database error: ' . $e->getMessage());
    }

}
public function updateStudOrg(Request $request, $id)
{
    try {
       $request->validate([
        'code' => 'required|unique:student_orgs,code,' . $id,
        'name' => 'required'
        ]);

        DB::table('student_orgs')->where('id', $id)->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Student Organization updated successfully!',
            'data' => [
                'id' => $id,
                'code' => $request->input('code'),
                'name' => $request->input('name')
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'errors' => $e->errors()
        ], 422);
    }
}
public function deleteStudOrg($id)
{
    try {
        DB::table('student_orgs')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

    // ========== Reported Users Actions ==========
    public function allowUserReport(int $id)
    {
        $report = UserReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }
        $report->delete();
        return response()->json(['success' => true, 'message' => 'Report removed']);
    }

    public function banUserFromReport(int $id)
    {
        $report = UserReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $user = User::find($report->reported_user_id);
        if ($user) {
            $user->role = 'banned';
            $user->save();

            // Notify user
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'Account Banned',
                'message' => 'Your account has been banned due to reported policy violations.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new NewNotification($notification));
        }

        $report->delete();
        return response()->json(['success' => true, 'message' => 'User banned and report removed']);
    }

    public function suspendUserFromReport(Request $request, int $id)
    {
        $request->validate([
            'duration' => 'required|integer|min:1|max:8760' // 1 hour to 1 year
        ]);

        $report = UserReport::find($id);
        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Report not found'], 404);
        }

        $user = User::find($report->reported_user_id);
        if ($user) {
            // Use the new suspend method with duration
            $user->suspend($request->duration);

            $durationText = $this->formatDuration($request->duration);

            // Notify user with suspension duration
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'Account Suspended',
                'message' => "Your account has been suspended for {$durationText} due to reported policy violations. Your account will be automatically reactivated on " . $user->suspension_until->format('M j, Y \a\t g:i A'),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new NewNotification($notification));
        }

        $report->delete();
        return response()->json([
            'success' => true, 
            'message' => "User suspended for {$durationText} and report removed"
        ]);
    }

    private function formatDuration(int $hours): string
    {
        if ($hours < 24) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '');
        } elseif ($hours < 168) { // Less than a week
            $days = intval($hours / 24);
            return $days . ' day' . ($days > 1 ? 's' : '');
        } elseif ($hours < 720) { // Less than a month
            $weeks = intval($hours / 168);
            return $weeks . ' week' . ($weeks > 1 ? 's' : '');
        } else {
            $months = intval($hours / 720);
            return $months . ' month' . ($months > 1 ? 's' : '');
        }
    }

    // ========== User Management Actions ==========
    public function unbanUser(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($user->role !== 'banned') {
            return response()->json(['success' => false, 'message' => 'User is not banned'], 400);
        }

        $user->role = 'student'; // Reset to default role
        $user->save();

        // Notify user
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Account Unbanned',
            'message' => 'Your account has been unbanned. You can now access the platform again.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new NewNotification($notification));

        return response()->json(['success' => true, 'message' => 'User has been unbanned successfully']);
    }

    public function unsuspendUser(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        if ($user->role !== 'suspended') {
            return response()->json(['success' => false, 'message' => 'User is not suspended'], 400);
        }

        $user->role = 'student'; // Reset to default role
        $user->suspension_until = null; // Clear suspension timestamp
        $user->save();

        // Notify user
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Suspension Lifted',
            'message' => 'Your account suspension has been lifted early. You can now access the platform again.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new NewNotification($notification));

        return response()->json(['success' => true, 'message' => 'User suspension has been lifted successfully']);
    }

    // ========== Featured Image Product Linking ==========
    public function linkFeaturedImageToProduct(Request $request, int $imageId)
    {
        \Log::info('Link product request', [
            'image_id' => $imageId,
            'product_id' => $request->product_id,
            'request_data' => $request->all()
        ]);

        $request->validate([
            'product_id' => 'required|exists:product,product_id'
        ]);

        $featuredImage = FeaturedImage::find($imageId);
        if (!$featuredImage) {
            \Log::error('Featured image not found', ['image_id' => $imageId]);
            return response()->json(['success' => false, 'message' => 'Featured image not found'], 404);
        }

        // Check if product is already linked to another featured image
        $existingLink = FeaturedImage::where('product_id', $request->product_id)
                                   ->where('id', '!=', $imageId)
                                   ->first();
        
        if ($existingLink) {
            \Log::error('Product already linked to another image', [
                'product_id' => $request->product_id,
                'existing_image_id' => $existingLink->id,
                'current_image_id' => $imageId
            ]);
            return response()->json(['success' => false, 'message' => 'This product is already linked to another featured image'], 400);
        }

        $product = Product::where('product_id', $request->product_id)
                         ->where('approved', 'yes')
                         ->first();
        
        if (!$product) {
            \Log::error('Product not found or not approved', ['product_id' => $request->product_id]);
            return response()->json(['success' => false, 'message' => 'Product not found or not approved'], 404);
        }

        $featuredImage->product_id = $request->product_id;
        $saved = $featuredImage->save();

        \Log::info('Featured image save result', [
            'saved' => $saved,
            'image_id' => $imageId,
            'product_id' => $request->product_id,
            'featured_image' => $featuredImage->toArray()
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Featured image linked to product successfully',
            'product_name' => $product->name
        ]);
    }

    public function unlinkFeaturedImageFromProduct(int $imageId)
    {
        $featuredImage = FeaturedImage::find($imageId);
        if (!$featuredImage) {
            return response()->json(['success' => false, 'message' => 'Featured image not found'], 404);
        }

        $productName = $featuredImage->product ? $featuredImage->product->name : 'product';
        
        $featuredImage->product_id = null;
        $featuredImage->save();

        return response()->json([
            'success' => true, 
            'message' => 'Featured image unlinked from product successfully'
        ]);
    }
}
