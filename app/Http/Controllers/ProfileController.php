<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserReport;
use App\Models\Notification;
use App\Events\NewNotification;

class ProfileController extends Controller
{
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $items = Product::where('user_id', $id)->get();

        // Ratings Overall
        $ratings = DB::table('reviews')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $id)
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.rating) as total_reviews')
            ->first();

        // Reviews
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->join('product', 'reviews.product_id', '=', 'product.product_id')
            ->where('product.user_id', $id)
            ->select(
                'users.name',
                'users.last_name',
                'product.image_path',
                'users.avatar',
                'reviews.rating',
                'reviews.comment',
                'reviews.created_at'
            )
            ->orderBy('reviews.created_at', 'desc')
            ->get()
            ->map(function ($review) {
                $images = explode(',', $review->image_path);
                $review->first_image = $images[0];
                $review->formatted_date = Carbon::parse($review->created_at)->format('F j, Y');
                return $review;
            });

        // Products 
        $productsQuery = Product::where('user_id', $id)
        ->where('approved', '=', 'yes');

        if ($request->filled('searching')) {
            $search = $request->searching;

            $productsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('tags', function($tagQuery) use ($search) {
                    $tagQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        if ($request->filled('stock')) {
            if ($request->stock === 'available') {
                $productsQuery->where('stock', '>', 0);
            } elseif ($request->stock === 'out') {
                $productsQuery->where('stock', '=', 0);
            }
        }
        if ($request->filled('sort')) {
            if ($request->sort === 'asc') {
                $productsQuery->orderBy('price', 'asc');
            } elseif ($request->sort === 'desc') {
                $productsQuery->orderBy('price', 'desc');
            }
        }
        $products = $productsQuery->get();

        $productIds = $products->pluck('product_id');

        $images = DB::table('product_images')
            ->whereIn('product_id', $productIds)
            ->get();

        if ($request->ajax()) {
            return view('partials.stalkableProfile', compact('products'))->render();
        }

        return view('profileView', compact('user', 'items', 'ratings', 'reviews', 'products', 'images'));
    }

public function storeUserReport(Request $request)
{
    try {
        $validated = $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'reporter_id'      => 'required|exists:users,id',
            'reason'           => 'required|string',
            'details'          => 'required|string',
            'evidence'         => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:5120',
        ]);

        if ($request->hasFile('evidence')) {
            $file     = $request->file('evidence');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Ensure directory exists
            if (!file_exists(public_path('storage/user-reports'))) {
                mkdir(public_path('storage/user-reports'), 0775, true);
            }

            $file->move(public_path('storage/user-reports'), $filename);

            $validated['evidence'] = 'storage/user-reports/' . $filename;
        }

        $report = UserReport::create($validated);
        $adminUserId = User::where('role', 'admin')->first();
        
        // Get the user names for the notification
        $reportedUser = User::find($validated['reported_user_id']);
        $reporter = User::find($validated['reporter_id']);
        
        $notification = Notification::create([
            'user_id' => $adminUserId->id,
            'title' => 'User Report',
            'message' => 'User "' . $reportedUser->name . " " . $reportedUser->last_name . '" is reported by "' . $reporter->name . " " . $reporter->last_name . '".',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->back()->with('successfull', 'Report submitted successfully');

    } catch (\Throwable $e) {
        return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
    }
}
}
