<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function dashboard()
    {
        $products = Product::where('user_id', Auth::id())->get();
        return view('organization.dashboard', compact('products'));
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:product,product_id',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }
        $product = Product::find($request->product_id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->save();

        if ($request->hasFile('images')) {
            // Step 1: Delete old images
            if ($product->image_path) {
                $oldImages = explode(',', $product->image_path);
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('images/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            // Step 2: Save new images
            $filenames = [];
            $counter = 0;
            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();
                $filename = time() . '_' . $counter . '_' . $originalName;
                $image->move(public_path('images'), $filename);
                $filenames[] = $filename;
                $counter++;
            }

            // Step 3: Update product record
            $product->image_path = implode(',', $filenames);
        }

        $product->save();

        return back()->with('success', 'Product updated successfully with images.');
    }

    public function showChart()
    {
        $statusCounts = [
            'pending' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'pending')
                ->count(),

            'completed' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'completed')
                ->count(),

            'cancelled' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'cancelled')
                ->count(),

            'in_cart' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'in_cart')
                ->count(),

            'receive' => DB::table('cart_items')
                ->where('seller_id', Auth::id())
                ->where('status', 'receive')
                ->count()

        ];

        $currentYear = Carbon::now()->year;

        // Get monthly completed sales for current year
        $monthlySales = DB::table('cart_items')
            ->select(
                DB::raw('MONTH(updated_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('seller_id', Auth::id())
            ->where('status', 'completed')
            ->whereYear('updated_at', $currentYear)
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->pluck('total', 'month');

        // Initialize all 12 months to 0
        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[] = $monthlySales->get($i, 0);
        }
        return view('organization/orgReport', compact('statusCounts', 'monthlySalesData'));
    }
}
