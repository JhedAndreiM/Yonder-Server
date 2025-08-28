<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaqCategory;
use Illuminate\Support\Facades\Log;

class FaqCategoryController extends Controller
{
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'categoryName' => 'required|string|max:255|unique:faq_categories,name',
        ]);

        try {
            $category = FaqCategory::create([
                'name' => $request->categoryName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Category added successfully!',
                'category' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: '.$e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id) {
    // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        // Find the category by ID
        $category = FaqCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }
        // Update the category name
        $category->name = $request->input('name');
        $category->save();
        return response()->json(['message' => 'Category updated successfully.']);
    }

    public function delete($id)
    {
       $category = FaqCategory::find($id);
       if (!$category) {
           Log::error('Category not found for ID: ' . $id);
           return response()->json(['message' => 'Category not found.'], 404);
       }
       $category->delete();
       return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
