<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FeaturedImageController extends Controller
{
public function addFeaturedImage(Request $request)
{
    $request->validate([
        'images' => 'required|array|min:1',
        'images.*' => 'image|mimes:jpeg,png,webp'
    ]);

    foreach ($request->file('images') as $image) {
        // Save new image
        $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
        $image->move(public_path('featured'), $imageName);

        FeaturedImage::create(['image_path' => $imageName]);

        // After saving, trim to 5 max
        $this->trimFeaturedImages();
    }

    return redirect()->back()->with('image_success', 'Featured images uploaded successfully!');
}

private function trimFeaturedImages()
{
    $images = FeaturedImage::orderBy('created_at', 'desc')->get();

    if ($images->count() > 5) {
        $excess = $images->slice(5); // everything after the newest 5
        foreach ($excess as $old) {
            $fullPath = public_path('featured/' . $old->image_path);
            if (file_exists($fullPath) && is_file($fullPath)) {
                unlink($fullPath);
            }
            $old->delete();
        }
    }
}
    public function destroy($id)
    {
        $image = DB::table('featured_images')->where('id', $id)->first();

        if ($image) {
            $path = public_path('Featured/' . $image->image_path);

            // Delete physical file
            if (File::exists($path)) {
                File::delete($path);
            }

            // Delete DB record
            DB::table('featured_images')->where('id', $id)->delete();

            return back()->with('image_success', 'Image deleted successfully!');
        }

        return back()->with('image_success', 'Image not found.');
    }
}
