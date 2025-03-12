<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:255',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'branch_address' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ];
    }
} 