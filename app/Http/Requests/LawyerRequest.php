<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LawyerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:lawyers,email',
            'phone_number' => 'required|regex:/^[0-9]{9}$/',
            'lawyer_certificate' => 'required|image|max:2048',
            'syndicate_card' => 'required|image|max:2048',
            'profile_picture' => 'nullable|image|max:2048',
            'gender' => 'required|in:male,female',
            'specialization' => 'nullable|string|max:191',
            'date_of_birth' => 'required|date|before:-18 years',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'The email address is already in use.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.regex' => 'The phone number must be exactly 9 digits.',
            'lawyer_certificate.required' => 'The lawyer certificate is required.',
            'syndicate_card.required' => 'The syndicate card is required.',
            'gender.required' => 'The gender is required.',
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.before' => 'The lawyer must be at least 18 years old.',
            'password.required' => 'The password is required.',
            'password.regex' => 'Password must include uppercase, lowercase, numbers, and symbols.',
        ];
    }
}
