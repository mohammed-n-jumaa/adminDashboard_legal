<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LawyerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:lawyers,email',
            'phone_number' => 'required|digits:9|unique:lawyers,phone_number',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'gender' => 'required|in:male,female',
            'specialization' => 'required|string|max:255',
            'lawyer_certificate' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'syndicate_card' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already in use.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.digits' => 'Phone number must be 9 digits.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'date_of_birth.required' => 'Date of birth is required.',
            'gender.required' => 'Gender is required.',
            'specialization.required' => 'Specialization is required.',
            'lawyer_certificate.required' => 'Lawyer certificate is required.',
            'syndicate_card.required' => 'Syndicate card is required.',
        ];
    }
}
