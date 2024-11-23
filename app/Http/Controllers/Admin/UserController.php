<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index()
    {
        // جلب المستخدمين مع التحقق من دور المستخدم الحالي
        $users = User::query();
    
        // إذا كان المستخدم الحالي "Admin"، استبعد "Admins" و "Super Admins"
        if (auth()->user()->role === 'admin') {
            $users->where('role', 'user');
        }
    
        $users = $users->withTrashed()->get();
    
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();
    
        // تعيين القيمة الافتراضية للحقل role إذا لم يتم تمريره
        $role = auth()->user()->role === 'super_admin' ? $validated['role'] ?? 'user' : 'user';
    
        // التأكد من صحة رقم الهاتف وإضافة البادئة +962
        $phoneNumber = '+962' . $validated['phone_number'];
    
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
    
        // إنشاء المستخدم الجديد
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'phone_number' => $phoneNumber,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'profile_picture' => $profilePicturePath,
        ]);
    
        return redirect()->route('admin.users.index')->with([
            'alert-type' => 'success',
            'message' => 'User added successfully!'
        ]);
    }
    

    

public function update(Request $request, $id)
{
    $user = User::withTrashed()->findOrFail($id);

    $rules = [
        'name' => 'nullable|string|max:255', 
        'email' => 'nullable|email|unique:users,email,' . $user->id, 
        'phone_number' => 'nullable|regex:/^\d{9}$/|unique:users,phone_number,' . $user->id, 
        'date_of_birth' => 'nullable|date|before:' . now()->subYears(18)->format('Y-m-d'), 
        'role' => 'nullable|in:user,admin,super_admin',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
    ];

    $validated = $request->validate($rules);

    $data = [];
    if ($request->filled('name')) {
        $data['name'] = $validated['name'];
    }
    if ($request->filled('email')) {
        $data['email'] = $validated['email'];
    }
    if ($request->filled('phone_number')) {
        $data['phone_number'] = '+962' . $validated['phone_number'];
    }
    if ($request->filled('date_of_birth')) {
        $data['date_of_birth'] = $validated['date_of_birth'];
    }
    if ($request->filled('role')) {
        $data['role'] = $validated['role'];
    }

    if ($request->hasFile('profile_picture')) {
        $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
    }

    $user->update($data);

    return redirect()->route('admin.users.index')->with([
        'alert-type' => 'success',
        'message' => 'User updated successfully!'
    ]);
}




    public function destroy($id)
    {
        $user = User::findOrFail($id); 
        $user->delete(); 
    
        return redirect()->route('admin.users.index')->with([
            'alert-type' => 'success',
            'message' => 'User deleted successfully!',
        ]);
    }
    
    
    
    

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('admin.users.index')->with([
            'alert-type' => 'info',
            'message' => 'User restored successfully!'
        ]);
    }

    public function forceDelete($id)
{
    $user = User::withTrashed()->findOrFail($id); 
    $user->forceDelete(); 
    return redirect()->route('admin.users.index')->with([
        'alert-type' => 'success',
        'message' => 'User permanently deleted successfully!',
    ]);
}

}
