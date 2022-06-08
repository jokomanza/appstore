<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppRequest extends FormRequest
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
            'name' => 'nullable|string|min:3|max:30',
            'package_name' => 'nullable|string',
            'description' => 'nullable|string|max:300',
            'repository_url' => 'nullable|url',
            'icon_file' => 'nullable|mimes:jpeg,jpg,png|max:10000',
            'user_documentation_file' => 'nullable|mimes:pdf|max:10000',
            'developer_documentation_file' => 'nullable|mimes:pdf|max:10000',
        ];
    }
}
