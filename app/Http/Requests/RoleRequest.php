<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'i_ref_bu_id' => 'required',
            'i_ref_level_id' => 'required',
            'permission_id' => 'required',
            'form_permission_id' => 'required'
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
            'i_ref_bu_id.required' => 'Please select Business Unit',
            'i_ref_level_id.required' => 'Please select Level',
            'permission_id.required' => 'Please select Permissions',
            'form_permission_id.required' => 'Please select Form Permissions'
        ];
    }
}
