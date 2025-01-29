<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class smsRequest extends FormRequest
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
    public function rules():array
    {
        return [
            'provider_name' => 'required|string',
            'sms_from'      => 'required|string',
            'api_key'       => 'required|string',
            'api_secret'    => 'required|string',
            'app_id'    => 'required|string',
        ];
    }

    public function messages():array
    {
        
        return [
                'provider_name.required' => __('Provider name is required'),
                'api_key.required'       => __('Api key is required'),
                'api_secret.required'    => __('Api secret is required'),
                'app_id'    => __('App ID is required'),

        ];
    }
}
