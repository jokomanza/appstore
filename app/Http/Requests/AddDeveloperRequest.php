<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDeveloperRequest extends FormRequest
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
            'app_id' => 'required|numeric|exists:apps,id',
            'user_registration_number' => 'required|string|exists:users,registration_number',
        ];
    }
}
