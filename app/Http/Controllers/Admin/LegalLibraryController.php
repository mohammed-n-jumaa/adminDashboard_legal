<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalLibrary;
use App\Models\Category;
use Illuminate\Http\Request;

class LegalLibraryController extends Controller
{
    public function index()
    {
        $libraries = LegalLibrary::with('category')->get();
        return view('admin.legal-library.index', compact('libraries'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.legal-library.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        LegalLibrary::create($request->all());
        return redirect()->route('admin.legal-library.index');
    }

    public function edit(LegalLibrary $legalLibrary)
    {
        $categories = Category::all();
        return view('admin.legal-library.edit', compact('legalLibrary', 'categories'));
    }

    public function update(Request $request, LegalLibrary $legalLibrary)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $legalLibrary->update($request->all());
        return redirect()->route('admin.legal-library.index');
    }

    public function destroy(LegalLibrary $legalLibrary)
    {
        $legalLibrary->delete();
        return redirect()->route('admin.legal-library.index');
    }
}
