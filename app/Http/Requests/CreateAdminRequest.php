<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            'registration_number' => 'required|string|max:255|unique:admins|regex:/[A-Z]{1}[0-9]{4}$/',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
        ];
    }
}
