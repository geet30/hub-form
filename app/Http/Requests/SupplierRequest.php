<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'bussiness_name' => 'required',
            'vc_fname' => 'required',
            'vc_lname' => 'required',
            'email' => 'required|email',
            'vc_DOPAS' => 'required'
            // 'swift_code' => 'required',
            // 'bank_BSB_number' => 'required',
            // 'tax_File_number' => 'required',
            // 'australlian_business_number' => 'required',
            // 'company_business_number' => 'required',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(){
        return [
            'bussiness_name.required' => 'Please enter Business Name',
            'vc_fname.required' => 'Please enter First Name',
            'vc_lname.required' => 'Please enter Last Name',
            'email.required' => 'Please enter Email',
            'email.email' => 'Please enter valid Email',
            'vc_DOPAS.required' => 'Please enter Descriptions Of Products and Services'
            // 'swift_code.required' => 'Please enter SWIFT Code',
            // 'bank_BSB_number.required' => 'Please enter Bank BSB Number',
            // 'tax_File_number.required' => 'Please enter Tax File Number',
            // 'australlian_business_number.required' => 'Please enter Australlian Business Number',
            // 'company_business_number.required' => 'Please enter Company Business Number',
        ];
    }
}
