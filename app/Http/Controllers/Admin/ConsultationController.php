<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index()
    {
        $consultations = Consultation::with('user', 'category')->withTrashed()->get();
        return view('admin.consultations.index', compact('consultations'));
    }

    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.consultations.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Consultation::create($request->all());
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation created successfully.');
    }

    public function edit(Consultation $consultation)
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.consultations.edit', compact('consultation', 'categories', 'users'));
    }

    public function update(Request $request, Consultation $consultation)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $consultation->update($request->all());
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation updated successfully.');
    }

    public function destroy($id)
    {
        $consultation = Consultation::findOrFail($id);
        $consultation->delete(); // Soft Delete
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation soft deleted successfully.');
    }

    public function restore($id)
    {
        $consultation = Consultation::withTrashed()->findOrFail($id);
        $consultation->restore(); // Restore Soft Deleted Consultation
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation restored successfully.');
    }

    public function forceDelete($id)
    {
        $consultation = Consultation::withTrashed()->findOrFail($id);
        $consultation->forceDelete(); // Force Delete
        return redirect()->route('admin.consultations.index')->with('success', 'Consultation permanently deleted.');
    }
}
