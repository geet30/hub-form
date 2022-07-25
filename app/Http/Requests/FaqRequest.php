<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
        if ($this->request->get('action') == 'edit') {
            $valid = array(
                'faqs' => 'required|custom_unique:faqs,faqs,' . $this->request->get('idedit') . ',_id|min:3|max:100',
                'answer' => 'required|min:2|max:10000',
                'status' => 'required|'
            );
        } else {
            $valid = array(
                'faqs' => 'required|min:5|max:100|custom_unique:faqs,faqs',
                'answer' => 'required|Min:2|max:10000',
                'status' => 'required|'
            );
        }
        return $valid;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'faqs.required' => 'Enter question name',
            'faqs.custom_unique' => 'Question already exists',
            'faqs.min' => 'Please enter question name between 5-40 characters.',
            'faqs.max' => 'Please enter question name between 5-40 characters.',
            'answer.required' => 'Please enter answer',
            'status' => 'Select Status',
        ];
    }
}
