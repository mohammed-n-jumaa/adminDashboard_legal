<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LawyerRequest;
use App\Models\Lawyer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LawyerController extends Controller
{
    public function index()
    {
        $lawyers = Lawyer::all(); // اجلب جميع المحامين
        return view('admin.lawyers.index', compact('lawyers'));
    }
    

    public function store(LawyerRequest $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:lawyers,email',
            'phone_number' => 'required|digits:9|unique:lawyers,phone_number',
            'password' => 'required|confirmed|min:8',
            'date_of_birth' => 'required|date|before:18 years ago',
            'gender' => 'required|in:male,female',
            'specialization' => 'nullable|string|max:191',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'lawyer_certificate' => 'required|file|mimes:pdf,jpeg,png|max:2048',
            'syndicate_card' => 'required|file|mimes:pdf,jpeg,png|max:2048',
        ]);

        // File Uploads
        $profilePicturePath = $request->hasFile('profile_picture') 
            ? $request->file('profile_picture')->store('profile_pictures', 'public') 
            : null;
        $certificatePath = $request->file('lawyer_certificate')->store('lawyer_certificates', 'public');
        $syndicateCardPath = $request->file('syndicate_card')->store('syndicate_cards', 'public');

        Lawyer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'specialization' => $request->specialization,
            'profile_picture' => $profilePicturePath,
            'lawyer_certificate' => $certificatePath,
            'syndicate_card' => $syndicateCardPath,
        ]);

        return redirect()->route('admin.lawyers.index')->with('success', 'Lawyer added successfully!');
    }

    public function update(LawyerRequest $request, Lawyer $lawyer)
    {
        // التحقق من المدخلات
        $request->validate([
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:lawyers,email,' . $lawyer->id,
            'phone_number' => 'required|digits:9|unique:lawyers,phone_number,' . $lawyer->id,
            'date_of_birth' => 'required|date|before:18 years ago',
            'gender' => 'required|in:male,female',
            'specialization' => 'nullable|string|max:191',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'lawyer_certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'syndicate_card' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
        ]);
        
        // استخراج البيانات المطلوبة من الطلب
        $data = $request->only([
            'first_name', 'last_name', 'email', 'phone_number', 'date_of_birth', 'gender', 'specialization'
        ]);
        
        if ($request->hasFile('profile_picture')) {
            if ($lawyer->profile_picture && Storage::exists('public/' . $lawyer->profile_picture)) {
                Storage::delete('public/' . $lawyer->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }
        
        
        // تحديث شهادة المحامي إذا تم رفعها
        if ($request->hasFile('lawyer_certificate')) {
            // حذف الشهادة القديمة إذا كانت موجودة
            if ($lawyer->lawyer_certificate && Storage::exists('public/' . $lawyer->lawyer_certificate)) {
                Storage::delete('public/' . $lawyer->lawyer_certificate);
            }
            // تخزين الشهادة الجديدة
            $data['lawyer_certificate'] = $request->file('lawyer_certificate')->store('lawyer_certificates', 'public');
        }
        
        // تحديث بطاقة النقابة إذا تم رفعها
        if ($request->hasFile('syndicate_card')) {
            // حذف البطاقة القديمة إذا كانت موجودة
            if ($lawyer->syndicate_card && Storage::exists('public/' . $lawyer->syndicate_card)) {
                Storage::delete('public/' . $lawyer->syndicate_card);
            }
            // تخزين البطاقة الجديدة
            $data['syndicate_card'] = $request->file('syndicate_card')->store('syndicate_cards', 'public');
        }
        
        // تحديث بيانات المحامي في قاعدة البيانات
        $lawyer->update($data);
        
        // إعادة التوجيه إلى صفحة المحاميين مع رسالة نجاح
        return redirect()->route('admin.lawyers.index')->with('success', 'Lawyer updated successfully.');
    }
    
    

    public function destroy(Lawyer $lawyer)
    {
        $lawyer->delete();
        return redirect()->route('admin.lawyers.index')->with('success', 'Lawyer soft deleted successfully.');
    }

    public function restore($id)
    {
        $lawyer = Lawyer::onlyTrashed()->findOrFail($id);
        $lawyer->restore();

        return redirect()->route('admin.lawyers.index')->with('success', 'Lawyer restored successfully.');
    }

    public function forceDelete($id)
    {
        $lawyer = Lawyer::onlyTrashed()->findOrFail($id);
        $lawyer->forceDelete();

        return redirect()->route('admin.lawyers.index')->with('success', 'Lawyer permanently deleted.');
    }
    public function show($id)
{
    $lawyer = Lawyer::findOrFail($id);

    // إعادة بيانات المحامي بصيغة JSON
    return response()->json($lawyer);
}

}
