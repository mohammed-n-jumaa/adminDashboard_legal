<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->get();
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:new,read,resolved',
        ]);

        Message::create($request->all());
        return redirect()->route('admin.messages.index');
    }

    public function edit(Message $message)
    {
        $users = User::all();
        return view('admin.messages.edit', compact('message', 'users'));
    }

    public function update(Request $request, Message $message)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:new,read,resolved',
        ]);

        $message->update($request->all());
        return redirect()->route('admin.messages.index');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index');
    }
}
