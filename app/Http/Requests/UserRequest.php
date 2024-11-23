<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to perform this request
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => [
                'required',
                'digits:9', // التحقق من أن الهاتف يحتوي على 9 أرقام فقط
                'unique:users,phone_number'
            ],
            'date_of_birth' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'password' => [
                'required',
                'confirmed', // Ensures password matches password_confirmation field
                Password::min(8) // At least 8 characters
                    ->letters() // At least one letter
                    ->mixedCase() // At least one uppercase and one lowercase letter
                    ->numbers() // At least one number
                    ->symbols(), // At least one special symbol
            ],
            'role' => 'required|in:user,admin,super_admin', // Validation for role field
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'This email is already in use.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.unique' => 'This phone number is already in use.',
            'phone_number.digits' => 'The phone number must contain exactly 9 digits.', 
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.before' => 'You must be at least 18 years old.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.letters' => 'The password must contain at least one letter.',
            'password.mixedCase' => 'The password must include both uppercase and lowercase letters.',
            'password.numbers' => 'The password must include at least one number.',
            'password.symbols' => 'The password must include at least one special symbol.',
            'role.required' => 'The role field is required.', // Custom message for role validation
            'role.in' => 'The selected role is invalid.', // Custom message for invalid role value
        ];
    }
}
