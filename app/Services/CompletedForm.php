<?php
namespace App\Services;

use App;
use App\Models\Action as ActionModel;
use App\Models\Answer as AnswerModel;
use App\Models\CompletedForm as CompletedFormModel;
use App\Models\Evidence as EvidenceModel;
use App\Models\Guide as GuideModel;
use App\Models\Question as QuestionModel;
use App\Models\ScopeMethodology as ScopeMethodologyModel;
use App\Models\Section as SectionModel;
use App\Models\Template as TemplateModel;
use App\Services\Action as ActionService;
use App\Services\P2B as P2BService;
use App\Models\DropdownOption;
use App\Models\DropdownType;

// use Carbon\Carbon;

class CompletedForm
{

    public function __construct()
    {
        $this->P2bService = new P2BService();
        $this->actionService = new ActionService();
    }

    /**
     * get LegalReminder
     * @return collection
     */
    public function getListing($form_status, $title = null, $request)
    {

        if (!empty($request->user_id)) {
            $conditions[] = ['user_id', $request->user_id];
            if (!empty($form_status)) {
                array_push($conditions, ['status', '=', $form_status]);
            }
            if (!empty($title)) {
                array_push($conditions, ['title', 'like', '%' . $title . '%']);
            }

            $listing = CompletedFormModel::with(['Template'])->withCount('actions')->where($conditions)->orderBy('id', 'desc')->paginate(10);
            return $listing;
        }
    }



    public function getListingByRole($form_status, $title = null, $request)
    {   
        $roleid=auth()->user()->users_details->i_ref_role_id;
        if (!empty($request->user_id)) {
            $conditions[] = ['i_ref_user_role_id', $roleid];
            if (!empty($form_status)) {
                array_push($conditions, ['status', '=', $form_status]);
            }
            if (!empty($title)) {
                array_push($conditions, ['title', 'like', '%' . $title . '%']);
            }

            $listing = CompletedFormModel::with(['Template'])->withCount('actions')->where($conditions)->orderBy('id', 'desc')->paginate(10);
            return $listing;
        }
    }

    /**
     * get completed form
     */
    public function getMapData($where = array(), $select = array(), $with = array())
    {
        $rows = CompletedFormModel::latest();
        if (is_array($select) && !empty($select)) {
            $rows = $rows->select($select);
        }
        if (is_array($with) && !empty($with)) {
            $rows = $rows->with($with);
        }
        if (is_array($where) && !empty($where)) {
            $rows = $rows->where($where);
        }
        return $rows->get();
    }

    /**
     * get template unique id
     * @return complete form id
     */
    public function getCompleteFormId($template_id)
    {
        $template = TemplateModel::withTrashed()->where('id', $template_id)->first();
        $count = CompletedFormModel::withTrashed()->where('template_id', $template_id)->count();
        $count = str_pad($count, 3, '0', STR_PAD_LEFT);
        if (!empty($template)) {
            $complete_form_id = $template->template_prefix . '-' . $count;
        } else {
            $complete_form_id = $count;
        }
        return $complete_form_id;
    }

    /**
     * get completed form id and
     * show detail of
     * completed form with details
     * of Buiseness usnit, projects
     * and department
     */
    public function getCompleteFormDetail($id)
    {
        $details = CompletedFormModel::with(['Template', 
        'scopeMethodology', 'sections.questions.guides.documents', 
        'sections.questions.comments', 'sections.questions.answers.evidences',
        'sections.questions.dropdown_type.options',
        'business' => function($query){
            $query->selectRaw('id ,vc_short_name as vc_name');
        },
        'dept_data'=> function($query){
            $query->select('id','vc_name');
        }, 
        'project_data'=> function($query){
            $query->select('id','vc_name');
        }])->where('id', $id)->first();
        $actioncount=CompletedFormModel::with('actions')->where('id', $id)->first();
		if(!empty($actioncount)){
			$details->actions_count=count($actioncount->actions);
		}
        return $details;
    }
    /**
     * save completed form data
     * with complete data in
     * related tables
     */
    public function saveCompleteFormData($request)
    {
        $template_id = $request['template_id'];
        if (!empty($template_id)) {
            $template = TemplateModel::with(['scopeMethodology', 'sections.questions.guides.documents'])->where('id', $template_id)->first();
            $previous_form = CompletedFormModel::where('template_id', $template_id)->count();
            $previous_temp_form = ($previous_form != 0) ? $previous_form + 1 : '1';
            $template_prefix = $template['template_prefix'];

            $CompletedForm = new CompletedFormModel();
            $CompletedForm->save_as_id = $request->save_as_id;
            $CompletedForm->form_id = $template_prefix . "-00" . $previous_temp_form;
            $CompletedForm->title = $request->title;
            $CompletedForm->template_id = $template_id;
            $CompletedForm->user_id = $request->user_data['id'];
            $CompletedForm->user_name = $request->user_data['vc_fname'] . ' ' . $request->user_data['vc_mname'];
            $CompletedForm->company_id = $request->company['id'];
            $CompletedForm->company_name = $request->company['vc_company_name'];
            $CompletedForm->business_unit_id = $request->selected_bu['id'];
            $CompletedForm->business_unit_name = $request->selected_bu['vc_name'];
            $CompletedForm->department_id = $request->selected_department['id'];
            $CompletedForm->department_name = $request->selected_department['vc_name'];
            $CompletedForm->project_id = $request->project['id'];
            $CompletedForm->project_name = $request->project['vc_name'];
            $CompletedForm->status = $request->status;
            $CompletedForm->location_name = $request->selected_location['formatted_address'];
            $CompletedForm->latitude = $request->selected_location['latitude'];
            $CompletedForm->longitude = $request->selected_location['longitude'];
            $CompletedForm->selected_date = $request->selected_date;

            if ($CompletedForm->save()) {
                $completedForm_id = $CompletedForm->id;
                if (!empty($completedForm_id)) {
                    // $scope_methodology = $template->scopeMethodology;
                    //Save scope and methodology data
                    if (!empty($template->scopeMethodology)) {
                        foreach ($template->scopeMethodology as $scope_methodology) {
                            $Scope = new ScopeMethodologyModel();
                            $Scope->template_id = 0;
                            $Scope->completed_form_id = $completedForm_id;
                            $Scope->snm_name = $scope_methodology->snm_name;
                            $Scope->snm_data = $scope_methodology->snm_data;
                            $Scope->type = '2';
                            $Scope->save();
                        }
                    }
                    //save section data
                    if (!empty($template->sections)) {
                        foreach ($template->sections as $sec_key => $section) {
                            if (!empty($section->score)) {
                                $score_value = $section->score;
                            } else {
                                $score_value = 0;
                            }

                            $Section = new SectionModel();
                            $Section->template_id = 0;
                            $Section->completed_form_id = $completedForm_id;
                            $Section->name = $section->name;
                            $Section->score = $score_value;
                            $Section->type = '2';

                            if ($Section->save()) {
                                $section_id = $Section->id;
                                if (!empty($section_id)) {
                                    // save questions data
                                    if (!empty($section->questions)) {
                                        foreach ($section->questions as $ques_key => $question) {
                                            if (isset($question->required) && $question->required == '1') {
                                                $required_value = $question->required;
                                            } else {
                                                $required_value = 0;
                                            }

                                            if (!empty($question->type_option)) {
                                                $option = $question->type_option;
                                            } else {
                                                $option = null;
                                            }
                                            $Question = new QuestionModel();
                                            $Question->template_id = 0;
                                            $Question->section_id = $section_id;
                                            $Question->text = $question->text;
                                            $Question->question_type = !empty($question->question_type) ? $question->question_type : null;
                                            $Question->type_option = !empty($option) ? $option : null;
                                            $Question->required = $required_value;
                                            $Question->type = '2';
                                            if ($Question->save()) {
                                                $question_id = $Question->id;
                                                if (!empty($question_id)) {
                                                    if (!empty($question->guides)) {
                                                        foreach ($question->guides as $guide_key => $guides) {
                                                            $file = isset($guides->document_name) ? $guides->document_name : '';
                                                            $document_id = isset($guides->document_id) ? $guides->document_id : null;
                                                            $notes = isset($guides->notes) ? $guides->notes : null;
                                                            $document_type = isset($guides->document_type) ? $guides->document_type : null;
                                                            $guide_type = isset($guides->guide_type) ? $guides->guide_type : null;
                                                            $fileName = "";

                                                            if (!empty($file) || !empty($document_id) || !empty($notes)) {
                                                                $Guide = new GuideModel();
                                                                $Guide->question_id = $question_id;
                                                                $Guide->notes = $notes;
                                                                $Guide->document_id = $document_id;
                                                                $Guide->document_name = !empty($file) ? $file : null;
                                                                $Guide->document_type = $document_type;
                                                                $Guide->guide_type = $guide_type;
                                                                $Guide->type = '2';
                                                                $Guide->save();
                                                            }
                                                        }
                                                    }

                                                    // save answers data
                                                    $answer_data = $request->formatted_answers[$sec_key];

                                                    if (isset($answer_data)) {
                                                        if (isset($answer_data[$ques_key]['required']) && $answer_data[$ques_key]['required'] == '1') {
                                                            $required_ans = $answer_data[$ques_key]['required'];
                                                        } else {
                                                            $required_ans = 0;
                                                        }
                                                        // print_r($required_ans);die;
                                                        $ans_note = $answer_data[$ques_key]['notes'];
                                                        $note = !empty($ans_note) ? $ans_note : null;

                                                        $question_data = QuestionModel::find($question_id);
                                                        $media_data = $answer_data[$ques_key]['media'];
                                                        if ($answer_data[$ques_key]['true_false_ans'] != '') {
                                                            $ans = $answer_data[$ques_key]['true_false_ans'];
                                                            $type_option = null;
                                                        } elseif (isset($answer_data[$ques_key]['mcq_ans'])) {
                                                            $opt_val = $question_data->type_option;
                                                            $ans_value = $answer_data[$ques_key]['mcq_ans'];
                                                            $ans = array_key_exists($ans_value, $opt_val) ? $opt_val[$ans_value] : null;
                                                            $type_option = null;
                                                        } elseif (isset($answer_data[$ques_key]['dropdown_value']) && isset($answer_data[$ques_key]['dropdown_color'])) {
                                                            $answer_value = $answer_data[$ques_key]['dropdown_value'];
                                                            $dropdown_color = $answer_data[$ques_key]['dropdown_color'];
                                                            $type_option = null;
                                                            $ans = null;
                                                            $dropdown_ans_id = !empty($answer_value) ? $answer_value : null;
                                                        } elseif (!empty($answer_data[$ques_key]['checkbox_values'])) {
                                                            $ans_value = $answer_data[$ques_key]['checkbox_values'];
                                                            $type_option = !empty($ans_value) ? $ans_value : null;
                                                            $ans = null;
                                                        }elseif (!empty($answer_data[$ques_key]['signature_link'])) {
                                                            $ans_value = $answer_data[$ques_key]['signature_link'];
                                                            $type_option =  null;
                                                            $ans = !empty($ans_value) ? $ans_value : null;
                                                        } else {
                                                            $ans = $answer_data[$ques_key]['text_ans'];
                                                            $type_option = null;
                                                        }
                                                        $answer = new AnswerModel();
                                                        $answer->template_id = 0;
                                                        $answer->completed_form_id = $completedForm_id;
                                                        $answer->section_id = $section_id;
                                                        $answer->question_id = $question_id;
                                                        $answer->dropdown_color = isset($dropdown_color) && !empty($dropdown_color) ? $dropdown_color : null;
                                                        $answer->dropdown_ans_id = isset($dropdown_ans_id) && !empty($dropdown_ans_id) ? $dropdown_ans_id : null;
                                                        $answer->type_option = $type_option;
                                                        $answer->required = $required_ans;
                                                        $answer->notes = $note;
                                                        $answer->type = '2';
                                                        $answer->answer = $ans;
                                                        $answer->save();
                                                        $answer_id = $answer->id;

                                                        /**
                                                         * save dropdown data
                                                         */
                                                        $saveType = [];
                                                        $saveOption = [];

                                                        if(!empty($answer_data[$ques_key]['dropdown_type']) && count($answer_data[$ques_key]['dropdown_type']) > 0){
                                                            foreach($answer_data[$ques_key]['dropdown_type'] as $dropdown_type){
                                                                $dropdownType = DropdownType::create([
                                                                    'type_name' => $dropdown_type['type_name'],
                                                                    'selected_type' => 1,
                                                                    'ques_id' => $question_id
                                                                ]);

                                                                if(!empty($dropdown_type['options']) && count($dropdown_type['options']) > 0){
                                                                    foreach($dropdown_type['options'] as $options){
                                                                        $dropdownOption = DropdownOption::create([
                                                                            'type_id' => $dropdownType->id,
                                                                            "option_name" => $options['option_name'],
                                                                            "failed_item" => $options['failed_item'],
                                                                            "color_code" => $options['color_code'],
                                                                        ]);
                                                                    }
                                                                }
                                                            }
                                                        }

                                                        /**
                                                         * Save Action
                                                         */
                                                        if (isset($answer_data[$ques_key]) && isset($answer_data[$ques_key]['action']) && !empty($answer_data[$ques_key]['action'])) {
                                                            $old_actions = ActionModel::withTrashed()->count();
                                                            $nextAction = (int) $old_actions + 1;
                                                            $actionInput = $answer_data[$ques_key]['action'];
                                                            $actionInput['action_id'] = 'A00'. $nextAction;
                                                            $actionInput['question_id'] = $question_id;
                                                            $actionInput['section_id'] = $section_id;
                                                            $actionInput['completed_form_id'] = $completedForm_id;
                                                            $resultAction = ActionModel::create($actionInput);
                                                        }
                                                        /**
                                                         * End Save Action
                                                         */
                                                        if (isset($media_data) && !empty($answer_id)) {
                                                            foreach ($media_data as $media_key => $media) {
                                                                $media_file = !empty($media['file_name']) ? $media['file_name'] : '';
                                                                if (isset($media_file) && $media_file !== "") {
                                                                    $Evidence = new EvidenceModel();
                                                                    $Evidence->section_id = $section_id;
                                                                    $Evidence->answer_id = $answer_id;
                                                                    $Evidence->file_name = $media_file;
                                                                    $Evidence->file_type = !empty($media['file_type']) ? $media['file_type'] : null;
                                                                    $Evidence->type = 2;
                                                                    $Evidence->save();
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                return array('message' => "saved", 'completed_form_id' => $completedForm_id, 'form_id' => $CompletedForm->form_id, 'form_title'=>$CompletedForm->title);
            } else {
                return "not_saved";
            }
        } else {
            return "not_saved";
        }
    }

    /**
     * edit completed form data
     * with complete data in
     * related tables
     */
    public function editCompleteFormData($request)
    {

        $completedForm_id = $request['id'];
        $template_id = $request['template_id'];

        if (empty($completedForm_id)) {
            return "form_id_required";
        }
        if (!empty($completedForm_id)) {
            $completdForm_data = CompletedFormModel::with(['Template', 'scopeMethodology', 'sections.questions.guides.documents', 'sections.questions.answers'])->where('id', $completedForm_id)->first();
            $template = TemplateModel::with(['scopeMethodology', 'sections.questions.guides.documents'])->where('id', $template_id)->first();
            $template_prefix = $template->template_prefix;
            $form_id = !empty($request['form_id']) ? $request['form_id'] : $template_prefix . "-001";

            $CompletedForm = CompletedFormModel::find($completedForm_id);
            $CompletedForm->save_as_id = $request->save_as_id;
            $CompletedForm->form_id = $form_id;
            $CompletedForm->title = $request->title;
            $CompletedForm->template_id = $template_id;
            $CompletedForm->user_id = $request->user_data['id'];

            $CompletedForm->i_ref_user_role_id = auth()->user()->users_details->i_ref_role_id;
            
            $CompletedForm->user_name = $request->user_data['vc_fname'] . ' ' . $request->user_data['vc_mname'];
            $CompletedForm->company_id = $request->company['id'];
            $CompletedForm->company_name = $request->company['vc_company_name'];
            $CompletedForm->business_unit_id = $request->selected_bu['id'];
            $CompletedForm->business_unit_name = $request->selected_bu['vc_name'];
            $CompletedForm->department_id = $request->selected_department['id'];
            $CompletedForm->department_name = $request->selected_department['vc_name'];
            $CompletedForm->project_id = $request->project['id'];
            $CompletedForm->project_name = $request->project['vc_name'];
            $CompletedForm->status = $request->status;
            $CompletedForm->location_name = $request->selected_location['formatted_address'];
            $CompletedForm->latitude = $request->selected_location['latitude'];
            $CompletedForm->longitude = $request->selected_location['longitude'];
            $CompletedForm->selected_date = $request->selected_date;
            // print_r($CompletedForm);die;
            if ($CompletedForm->update()) {

                // save answers data
                $answers = $request->answer;

                if (!empty($answers)) {
                    foreach ($answers as $ans_key => $answer_data) {
                        $ans_note = $answer_data['notes'];
                        $note = !empty($ans_note) ? $ans_note : null;
                        $answer_id = $answer_data['id'];
                        $question_id = $answer_data['question_id'];
                        $section_id = $answer_data['section_id'];
                        $question_data = QuestionModel::find($question_id);

                        if ($answer_data['true_false_ans'] != '') {
                            $ans = $answer_data['true_false_ans'];
                            $type_option = null;
                        } elseif (isset($answer_data['mcq_ans'])) {
                            $opt_val = $question_data->type_option;
                            $ans_value = $answer_data['mcq_ans'];
                            $ans = array_key_exists($ans_value, $opt_val) ? $opt_val[$ans_value] : null;
                            $type_option = null;
                        } elseif (isset($answer_data['dropdown_value']) && isset($answer_data['dropdown_color'])) {
                            $answer_value = $answer_data['dropdown_value'];
                            $dropdown_color = $answer_data['dropdown_color'];
                            $type_option = null;
                            $ans = null;
                            $dropdown_ans_id = !empty($answer_value) ? $answer_value : null;
                        } elseif (!empty($answer_data['checkbox_values'])) {
                            $ans_value = $answer_data['checkbox_values'];
                            $type_option = !empty($ans_value) ? $ans_value : null;
                            $ans = null;
                        }elseif (!empty($answer_data['signature_link'])) {
                            $ans_value = $answer_data['signature_link'];
                            $type_option =  null;
                            $ans = !empty($ans_value) ? $ans_value : null;
                        } else {
                            $ans = $answer_data['text_ans'];
                            $type_option = null;
                        }
                        $required_value = !empty($answer_data['required']) ? $answer_data['required'] : 0;
                        if (!empty($answer_id)) {
                            $answer = AnswerModel::find($answer_id);
                            $answer->template_id = 0;
                            $answer->completed_form_id = $completedForm_id;
                            $answer->section_id = $section_id;
                            $answer->question_id = $question_id;
                            $answer->dropdown_color = isset($dropdown_color) && !empty($dropdown_color) ? $dropdown_color : null;
                            $answer->dropdown_ans_id = isset($dropdown_ans_id) && !empty($dropdown_ans_id) ? $dropdown_ans_id : null;
                            $answer->type_option = $type_option;
                            $answer->required = $required_value;
                            $answer->notes = $note;
                            $answer->type = '2';
                            $answer->answer = $ans;
                            $answer->update();
                            if (!empty($answer_data['media']) && !empty($answer_id)) {
                                foreach ($answer_data['media'] as $media_key => $media) {
                                    $media_file = !empty($media['file_name']) ? $media['file_name'] : '';

                                    if (isset($media_file) && $media_file !== "" && empty($media['id'])) {
                                        $Evidence = new EvidenceModel();
                                        $Evidence->section_id = $section_id;
                                        $Evidence->answer_id = $answer_id;
                                        $Evidence->file_name = $media_file;
                                        $Evidence->file_type = !empty($media['file_type']) ? $media['file_type'] : null;
                                        $Evidence->type = 2;
                                        $Evidence->save();
                                    } elseif (isset($media_file) && $media_file !== "" && !empty($media['id'])) {
                                        $Evidence = EvidenceModel::find($media['id']);
                                        $Evidence->section_id = $section_id;
                                        $Evidence->answer_id = $answer_id;
                                        $Evidence->file_name = $media_file;
                                        $Evidence->file_type = !empty($media['file_type']) ? $media['file_type'] : null;
                                        $Evidence->type = 2;
                                        $Evidence->update();
                                    }
                                }
                            }
                        } else {
                            $answer = new AnswerModel();
                            $answer->template_id = 0;
                            $answer->completed_form_id = $completedForm_id;
                            $answer->section_id = $section_id;
                            $answer->question_id = $question_id;
                            $answer->dropdown_color = isset($dropdown_color) && !empty($dropdown_color) ? $dropdown_color : null;
                            $answer->type_option = $type_option;
                            $answer->required = $required_value;
                            $answer->notes = $note;
                            $answer->type = '2';
                            $answer->answer = $answer;
                            $answer->save();
                            $answer_id = $answer->id;
                            if (!empty($answer_data['media']) && !empty($answer_id)) {
                                foreach ($answer_data['media'] as $media_key => $media) {
                                    $media_file = !empty($media['file_name']) ? $media['file_name'] : '';

                                    if (isset($media_file) && $media_file !== "") {

                                        $Evidence = new EvidenceModel();
                                        $Evidence->section_id = $section_id;
                                        $Evidence->answer_id = $answer_id;
                                        $Evidence->file_name = $media_file;
                                        $Evidence->file_type = !empty($media['file_type']) ? $media['file_type'] : null;
                                        $Evidence->type = 2;
                                        $Evidence->save();
                                    }
                                }
                            }
                        }
                    }
                }
                return array('message' => "saved", 'completed_form_id' => $completedForm_id, 'form_id' => $CompletedForm->form_id, 'form_title' => $CompletedForm->title);
            } else {
                return "not_saved";
            }
        }
    }

    /**
     * upload media files
     * in upload folder in
     * public
     */

    public function uploadMediaFiles($request)
    {
        $file = $request->file('file');
        if (isset($file)) {
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            if ($file->move(public_path('uploads'), $fileName)) {
                // return public_path('uploads/'.$fileName);
                return $fileName;
            }
        }
    }

    /**
     * deleted data of
     * media files
     * in database
     */

    public function delete_media($request)
    {
        $id = $request['id'];
        if (!empty($id)) {
            $data = EvidenceModel::find($id);
            if ($data->delete()) {
                return "deleted";
            }
        }
    }

    /**
     * upload media files
     * in upload folder in
     * public
     */

    public function shareCompletedForm($request, $id)
    {
        $detail = CompletedFormModel::with([
            'Template', 
            'scopeMethodology', 
            'sections.questions.guides.documents', 
            'sections.questions.answers.evidences', 
            // 'sections.questions.actions.assignee_user',
            'sections.questions.actions',
            // 'sections.questions.actions.user', 
            'business', 
            'dept_data', 
            'project_data'
        ])->where('id', $id)->first();
        $compnyProfilePic = $this->P2bService->getCompnyProfilePic($detail['company_id']);
        $compnyProfilePic = $compnyProfilePic['vc_logo'];

        if(!empty($detail->sections->questions)){
			foreach($detail->sections->questions as $key => $value){
				if(!empty($value->actions)){
					$assigneeRoleId=$value->actions->i_ref_assined_role_id;
					$userRoleId=$value->actions->i_ref_user_role_id;
					$userID=$value->actions->user_id;
					$assigneeID=$value->actions->assined_user_id;
					$value->actions->assignee_user=CheckUserType($assigneeRoleId,$assigneeID);
					$value->actions->user=CheckUserType($userRoleId,$userID);
				}
		
			}
		}
        // pr($detail);
        // exit;
        // $failled_items are those question that dosnot have answers
        $failled_items = 0;
        $failled_items_list = [];
        $actions = 0;
        $actions_list = [];
        $total_questions = 0;
        $form_score = 0;
        $section_wise_score = 0;
        $total_section_score = 0;
        $total_actions = 0;

        foreach ($detail['sections'] as $key => $sections) {
            $total_questions = $total_questions + count($sections['questions']);
            foreach ($sections['questions'] as $key_ques => $questions) {
                if (count($questions['actions']) > 0) {
                    $total_actions++;
                    $actions_list[$key_ques]['question'] = $questions->toarray();
                    $actions_list[$key_ques]['section_name'] = $sections->name;
                }

                if (!empty($questions['answers'])) {
                    if ($questions['question_type'] == 5 && $questions['answers']['answer'] == 0) { // if type 5 and anser is 'No' then skip this question
                        $failled_items++;
                        $failled_items_list[$key_ques] = $questions->toarray();
                        $failled_items_list[$key_ques]['section_name'] = $sections->name;
                        continue;
                    }
                    $form_score = $form_score + 1;
                    $total_section_score = $total_section_score + 1;
                } else {
                    $failled_items++;
                    $failled_items_list[$key_ques] = $questions->toarray();
                    $failled_items_list[$key_ques]['section_name'] = $sections->name;
                }
            }
            // array_push($section_wise_score, $total_section_score);
            $section_wise_score = $section_wise_score + $total_section_score;
            // rset $total_section_score after looping every section
            $total_section_score = 0;
        }

        // print_r($actions_list);
        // die;

        // GET USER DETAILS FOR EACH ACTION
        foreach ($actions_list as $key_act => $action) {
            
            $user = !empty($action['user'])?$action['user']:'';
            // $user = $this->P2bService->getUser($action['actions'][0]['user_id']);
            $actions_list[$key_act]['user'] = (!empty($user) ? $user->vc_fname . ' ' . $user->vc_lname : null);
            $assigned_user = !empty($action['assignee_user'])?$action['assignee_user']:'';
            // $assigned_user = $this->P2bService->getUser($action['actions'][0]['assined_user_id']);
            $actions_list[$key_act]['aasigned_user'] = (!empty($assigned_user) ? $assigned_user->vc_fname . ' ' . $assigned_user->vc_lname : null);
        }
        
        $data['total_questions'] = $total_questions;
        $data['form_score'] = $form_score;
        $data['section_wise_score'] = $section_wise_score;
        $form_score_percent = $form_score / $total_questions * 100;
        $data['form_score_percent'] = round($form_score_percent, 2); //ex: 10 is what percent of 100?
        $data['total_actions'] = $total_actions;
        $data['actions_list'] = $actions_list;
        $data['failled_items'] = $failled_items;
        $data['failled_items_list'] = $failled_items_list;
        $data['report_filter'] = ["app_completed_form"];

        // print_r($data);die;
        if ($data != "") {
            $data = array('data' => $data, 'detail' => $detail, 'active' => 'completed_forms', 'compnyProfilePic' => $compnyProfilePic);
            return $data;
            // $pdf = PDF::loadView('pdf.complete_form_report', array('data'=>$data,'detail'=>$detail, 'compnyProfilePic'=>$compnyProfilePic));
            // return $pdf;
            // return $pdf->download('complete_form_report.pdf');
        } else {
            return "Something went wrong. Please try again";
            // return redirect()->back()->withErrors(['Something went wrong. Please try again']);
        }
    }

    /**
     * get all the 
     * action of form
     */
    public function form_action_data($id)
    {
        if(!empty($id)){
            $actions = ActionModel::with(['completedForm', 'questions',
                // 'assignee_user' => function ($query) {
                // $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
                // }, 
                // 'user' => function ($query) {
                // $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
                // },
                'evidences', 'close_by' => function ($query) {
                $query->select('id', 'vc_fname', 'vc_mname', 'vc_lname');
                },
                'business_unit' => function($query){
                    $query->select('id', 'vc_short_name');
                },
                'department' => function($query){
                    $query->select('id', 'vc_name');
                },
                'project' => function($query){
                    $query->select('id', 'vc_name');
                }
            ])->where('completed_form_id', $id)->get();

            $assigneeRoleId=$actions->i_ref_assined_role_id;
            $userRoleId=$actions->i_ref_user_role_id;
            $userID=$actions->user_id;
            $assigneeID=$actions->assined_user_id;
            $actions->assignee_user=CheckUserType($assigneeRoleId,$assigneeID);
            $actions->user=CheckUserType($userRoleId,$userID);
         

            return $actions;
        }
    }
}
