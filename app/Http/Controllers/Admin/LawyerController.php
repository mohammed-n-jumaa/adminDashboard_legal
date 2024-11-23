<?php

namespace App\Http\Controllers;

use App\Models\Lawyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LawyerRequest;

class LawyerController extends Controller
{
    public function index()
    {
        $lawyers = Lawyer::withTrashed()->get(); // جلب المحامين بما فيهم المحذوفين ناعماً
        return view('lawyers.index', compact('lawyers'));
    }

    public function store(LawyerRequest $request)
    {
        $validated = $request->validated();

        // التأكد من صحة رقم الهاتف
        $phoneNumber = '+962' . $validated['phone_number'];

        // حفظ الصور (الشهادة وكرت النقابة) مع التحقق
        $certificatePath = $request->file('lawyer_certificate')->store('lawyer_certificates', 'public');
        $syndicateCardPath = $request->file('syndicate_card')->store('syndicate_cards', 'public');
        $profilePicturePath = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : ($validated['gender'] === 'male'
                ? 'default/male.png'
                : 'default/female.png');

        Lawyer::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $phoneNumber,
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'specialization' => $validated['specialization'],
            'profile_picture' => $profilePicturePath,
            'lawyer_certificate' => $certificatePath,
            'syndicate_card' => $syndicateCardPath,
        ]);

        return response()->json(['message' => 'Lawyer added successfully!'], 200);
    }

    public function update(Request $request, $id)
    {
        $lawyer = Lawyer::withTrashed()->findOrFail($id);

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:lawyers,email,' . $lawyer->id,
            'phone_number' => 'required|digits:9|unique:lawyers,phone_number,' . $lawyer->id,
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'gender' => 'required|in:male,female',
            'specialization' => 'required|string|max:255',
            'lawyer_certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'syndicate_card' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validated = $request->validate($rules);

        $data = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => '+962' . $validated['phone_number'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'specialization' => $validated['specialization'],
        ];

        // التحقق من وجود ملفات جديدة
        if ($request->hasFile('lawyer_certificate')) {
            $data['lawyer_certificate'] = $request->file('lawyer_certificate')->store('lawyer_certificates', 'public');
        }

        if ($request->hasFile('syndicate_card')) {
            $data['syndicate_card'] = $request->file('syndicate_card')->store('syndicate_cards', 'public');
        }

        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $lawyer->update($data);

        return response()->json(['message' => 'Lawyer updated successfully!'], 200);
    }

    public function destroy($id)
    {
        $lawyer = Lawyer::findOrFail($id);
        $lawyer->delete(); // الحذف الناعم
        return response()->json(['message' => 'Lawyer deleted successfully!'], 200);
    }

    public function restore($id)
    {
        $lawyer = Lawyer::withTrashed()->findOrFail($id);
        $lawyer->restore(); // الاسترجاع
        return response()->json(['message' => 'Lawyer restored successfully!'], 200);
    }

    public function forceDelete($id)
    {
        $lawyer = Lawyer::withTrashed()->findOrFail($id);
        $lawyer->forceDelete(); // الحذف الدائم
        return response()->json(['message' => 'Lawyer permanently deleted!'], 200);
    }
}
