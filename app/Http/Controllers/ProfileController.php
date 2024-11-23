<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account by ID.
     */
    public function destroy($id): RedirectResponse
    {
        // تحقق من وجود المستخدم باستخدام المعرّف (ID)
        $user = User::findOrFail($id);

        // تسجيل الخروج من الحساب إذا كان المستخدم الحالي هو نفسه
        if (Auth::id() === $user->id) {
            Auth::logout();

            // حذف المستخدم
            $user->delete();

            // إبطال الجلسة وتجديد التوكن
            session()->invalidate();
            session()->regenerateToken();

            // إعادة التوجيه إلى الصفحة الرئيسية
            return Redirect::to('/')->with('status', 'Your account has been deleted successfully.');
        }

        // إذا لم يكن المستخدم الحالي هو صاحب الحساب المطلوب الحذف
        return Redirect::back()->withErrors('You are not authorized to delete this account.');
    }
}
