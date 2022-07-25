<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            // 'vc_title' => 'required',
            'vc_fname' => 'required',
            'vc_lname' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'i_ref_country_id' => 'required',
            'i_ref_state_id' => 'required',
            'vc_city' => 'required',
            'i_ref_bu_id' => 'required',
            'i_ref_dep_id' => 'required',
            'i_ref_role_id' => 'required',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            // 'vc_title.required' => 'Please select Title',
            'vc_fname.required' => 'Please enter First Name',
            'vc_lname.required' => 'Please enter Last Name',
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter valid Email',
            'address.required' => 'Please enter Adress',
            'i_ref_country_id.required' => 'Please select Country',
            'i_ref_state_id.required' => 'Please select State',
            'vc_city.required' => 'Please enter City',
            'i_ref_bu_id.required' => 'Please select Business Unit',
            'i_ref_dep_id.required' => 'Please select Department',
            'i_ref_role_id.required' => 'Please select Role',
        ];
    }
}
