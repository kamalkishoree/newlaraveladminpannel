<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'dial_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:15|unique:users,mobile',
            // 'password' => 'nullable|string|min:8|confirmed',
            'joining_referal' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid string.',
            'first_name.max' => 'First name cannot be longer than 255 characters.',
            
            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid string.',
            'last_name.max' => 'Last name cannot be longer than 255 characters.',
            
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid string.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email address is already registered.',
            
            'dial_code.required' => 'Dial code is required.',
            'dial_code.string' => 'Dial code must be a valid string.',
            'dial_code.max' => 'Dial code cannot be longer than 10 characters.',
            
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
            'phone_number.max' => 'Phone number cannot be longer than 15 characters.',
            'phone_number.unique' => 'This phone number is already registered.',
            
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            
            'joining_referal.string' => 'Referral code must be a valid string.',
            'joining_referal.max' => 'Referral code cannot be longer than 255 characters.',
        ];
    }
}
