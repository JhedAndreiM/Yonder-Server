<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FeaturedImage;
use Illuminate\Support\Facades\Storage;

class FeaturedImageController extends Controller
{
    public function addFeaturedImage(Request $request)
{
    $request->validate([
        'image.*' => 'required|image|max:51200',
    ]);

    $images = $request->file('image');

    foreach ($images as $image) {
        // Remove oldest if limit reached
        if (FeaturedImage::count() >= 5) {
            $oldest = FeaturedImage::oldest()->first();
            if ($oldest && $oldest->image_path) {
                $fullPath = public_path('featured/' . $oldest->image_path);
                if (file_exists($fullPath) && is_file($fullPath)) {
                    unlink($fullPath);
                }
                $oldest->delete();
            }
        }

        $imageName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
        $image->move(public_path('featured'), $imageName);

        $featured = new FeaturedImage();
        $featured->image_path = $imageName;
        $featured->save();
    }

    return redirect()->back()->with('image_success', 'Featured images uploaded successfully!');
}
}
