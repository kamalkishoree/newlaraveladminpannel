<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
    public function rules()
    {
        return [
            'dial_code' => 'required|string|max:4', // Ensure the dial code is a string and no more than 10 characters
            'phone_number' => 'required|string|max:15|', // Ensure phone number is unique and fits the format
            'otp' => 'required|digits:4', // OTP should be exactly 6 digit
        ];
    }


    public function messages()
    {
        return [
            'dial_code.required' => 'Dial code is required.',
            'dial_code.string' => 'Dial code must be a valid string.',
            'dial_code.max' => 'Dial code cannot be longer than 3 numbers.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.string' => 'Phone number must be a valid string.',
            'phone_number.max' => 'Phone number cannot be longer than 15 characters.',
            'phone_number.unique' => 'This phone number is already registered.',
            'otp.required' => 'OTP is required.',
            'otp.digits' => 'OTP must be exactly 4  digits.',
        ];
    }

}
