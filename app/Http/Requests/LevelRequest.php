<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelRequest extends FormRequest
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
            'vc_name' => 'required',
            'i_start_limit' => 'required',
            'i_end_limit' => 'required'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'vc_name.required' => 'Please enter Name',
            'i_start_limit.required' => 'Please enter Minimum Budget',
            'i_end_limit.required' => 'Please enter Maximum Budget'
        ];
    }
}
