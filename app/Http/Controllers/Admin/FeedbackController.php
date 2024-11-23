<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Lawyer;
use App\Models\User;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('lawyer', 'user')->get();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        $lawyers = Lawyer::all();
        $users = User::all();
        return view('admin.feedback.create', compact('lawyers', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'required|exists:lawyers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        Feedback::create($request->all());
        return redirect()->route('admin.feedback.index');
    }

    public function edit(Feedback $feedback)
    {
        $lawyers = Lawyer::all();
        $users = User::all();
        return view('admin.feedback.edit', compact('feedback', 'lawyers', 'users'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        $request->validate([
            'lawyer_id' => 'required|exists:lawyers,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string',
        ]);

        $feedback->update($request->all());
        return redirect()->route('admin.feedback.index');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('admin.feedback.index');
    }
}
