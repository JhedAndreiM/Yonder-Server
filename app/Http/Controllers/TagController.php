<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Validation\Rule;
class TagController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255','unique:tags,name']
        ]);

        $tag = Tag::create([
            'name' => trim($validated['name']),
            'usage_count' => 0,
            'is_admin' => 1, // admin-created
        ]);

        // Works with your current flash message pattern
        return back()->with('tag_success', 'Tag created successfully!');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => [
                'required','string','max:255',
                Rule::unique('tags','name')->ignore($tag->id)
            ]
        ]);

        $tag->update(['name' => trim($validated['name'])]);

        return response()->json([
            'status' => 'success',
            'data' => $tag,
            'message' => 'Tag updated successfully'
        ]);
    }

    public function destroy(Tag $tag)
    {
        $tag->products()->detach();

        $tag->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tag deleted successfully'
        ]);
    }
}
