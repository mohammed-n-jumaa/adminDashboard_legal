<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('user', 'lawyer', 'consultation')->withTrashed()->get();
        return view('admin.comments.index', compact('comments'));
    }

    public function softDelete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete(); // Soft delete
        return response()->json(['message' => 'Comment soft deleted successfully.']);
    }

    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->restore(); // Restore soft deleted comment
        return response()->json(['message' => 'Comment restored successfully.']);
    }

    public function forceDelete($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->forceDelete(); // Permanently delete
        return response()->json(['message' => 'Comment permanently deleted successfully.']);
    }
}
