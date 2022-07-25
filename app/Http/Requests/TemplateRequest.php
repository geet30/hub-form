<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
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
            'template_name' => 'required',
            'template_prefix' => 'required',
            'color_pin' => 'required',
            // 'scope_methodology' => 'required'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'template_name.required' => 'Please enter the template name',
            'template_prefix.required' => 'Please enter the template prefix',
            'color_pin.required' => 'Please select the color pin',
            // 'scope_methodology.required' => 'Please enter the scope and methodology'
        ];
    }
}
