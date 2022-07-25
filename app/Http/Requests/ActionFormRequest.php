<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionFormRequest extends FormRequest
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
            'action_title' => 'required',
            'action_desc' => 'required',
            'assignee' => 'required',
            'business_unit' => 'required',
            'department' => 'required',
            // 'project' => 'required',
            // 'status' => 'required',
            'priority' => 'required',
            'due_date' => 'required|date'
        ];
    }

    
    public function messages(){
        return [
            'action_title.required' => 'Please Enter Title.',
            'action_desc.required' => 'Please Enter Description.',
            'assignee.required' => 'Please select Assign User.',
            'business_unit.required' => 'Please select business unit.',
            'department.required' => 'Please select department.',
            // 'project.required' => 'Please select project.',
            // 'status.required' => 'Please select Status',
            'priority.required' => 'Please select Priority',
            'due_date.required' => 'Please select valid due date'
        ];
    }

}
