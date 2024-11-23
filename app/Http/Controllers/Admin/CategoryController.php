<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories, including soft-deleted ones.
     */
    public function index()
    {
        $categories = Category::withTrashed()->get(); // Includes soft-deleted records
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created category in the database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validatedData);

        return response()->json(['success' => 'Category added successfully']);
    }

    /**
     * Update the specified category in the database.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::withTrashed()->findOrFail($id);
        $category->update($validatedData);

        return response()->json(['success' => 'Category updated successfully']);
    }

    /**
     * Soft delete the specified category.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    
        return response()->json(['success' => 'Category soft-deleted successfully']);
    }
    
    

    /**
     * Restore a soft-deleted category.
     */
    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
    
        return response()->json(['success' => 'Category restored successfully']);
    }
    

    /**
     * Permanently delete a category from the database.
     */
    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();
    
        return response()->json(['success' => 'Category permanently deleted']);
    }
    
}
