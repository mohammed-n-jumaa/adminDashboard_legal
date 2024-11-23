<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'status' => 'required|in:unread,read',
        ]);

        Notification::create($request->all());
        return redirect()->route('admin.notifications.index');
    }

    public function edit(Notification $notification)
    {
        $users = User::all();
        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    public function update(Request $request, Notification $notification)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'status' => 'required|in:unread,read',
        ]);

        $notification->update($request->all());
        return redirect()->route('admin.notifications.index');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index');
    }
}
