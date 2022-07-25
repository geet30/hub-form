<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Answer;
use App\Models\ChatRoom;
use App\Models\ChatRoomMember;
use App\Models\Comment;
use App\Models\CompletedForm;
use App\Models\Evidence;
use App\Models\Question;
use App\Models\ScopeMethodology;
use App\Models\Section;
use App\Models\Template;
use App\Services\CompletedForm as CompletedFormService;
use App\Services\P2B as P2BService;
use Auth;




use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Mapper;

//Completed Form Controller class




class CompletedFormController extends Controller
{
    public function __construct()
    {
        $this->completedFormService = new CompletedFormService();
        $this->P2bService = new P2BService();
    }

    public function index($value = '')
    {
        //get all completed form listing
        $listing = CompletedForm::with([
            'template' => function($query){
                $query->select('id', 'template_name');
            },
            // 'completed_by' => function($query){
            //     $query->select('id', 'vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email');
            // },
            'business' => function($query){
                $query->select('id', 'vc_short_name');
            },
            'dept_data' => function($query){
                $query->select('id', 'vc_name');
            },
            'project_data' => function($query){
                $query->select('id', 'vc_name');
            },
        ]);

        switch (auth()->user()->user_type) {
            case 'supplier':
                $listing = $listing->whereHas('actions', function (Builder $query) {
                    $query->where('assined_user_id', Auth::id());
                });
                break;
            case 'employee':
                $listing = $listing->whereHas('actions', function (Builder $query) {
                    $query->where('i_ref_assined_role_id', auth()->user()->users_details->i_ref_role_id);
                })->orWhere('i_ref_user_role_id',  auth()->user()->users_details->i_ref_role_id);
            default:
                $listing = $listing->where('status', 2);
                break;
        }

        $listing = $listing->orderBy('id', 'desc')->get();
        //get all Template listing
        $temp_list = Template::orderBy('id', 'desc')->get();

        // $completedBy = $this->P2bService->getallUsers([], ['id', 'vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email']);
        $completedBy=$this->P2bService->getAllCompanyUsers();
        $departments = $this->P2bService->getDepartments();
        $active = 'completed_forms';
        return view('admin.completed_form.index', compact('listing', 'temp_list', 'active', 'completedBy', 'departments'));


    }
 /*Edit complete form */ public function edit(Request $request, $id) {$id = encrypt_decrypt('decrypt', $id);
        $detail = CompletedForm::with([

            'scopeMethodology',
 'sections.questions', 'sections.questions.guides.documents', 'sections.questions.comments',
            'sections.questions.answers.evidences',
            'sections.questions.dropdown_type.options',
        ])->where('id', $id)->first();

        // pr($detail);die;
        $active = 'completed_forms';
        $projects = $this->P2bService->getProjects();
        $departments = $this->P2bService->getDepartments();
        $business_units = $this->P2bService->getBusinessUnits();
 $compnyProfilePic = $this->P2bService->getCompnyProfilePic($detail['company_id']); $compnyProfilePic = $compnyProfilePic['vc_logo'];






        return view('admin.completed_form.edit', compact('detail', 'projects', 'departments', 'business_units', 'active', 'compnyProfilePic'))->with('no', 1);
    }
 /**

s     */
    public function formChat(Request $request, $form_id, $question_id)
    {
        $form_id = encrypt_decrypt('decrypt', $form_id);
        $question_id = encrypt_decrypt('decrypt', $question_id);
        // create or check chat room
        $chatRoom = ChatRoom::whereTypeId($form_id)->whereQuestionId($question_id)->whereTypeModel('form');
    
        if (!$chatRoom->exists()) {
            $chatRoom = ChatRoom::create([
                'type_id' => $form_id,
                'question_id' => $question_id,
                'type_model' => 'form',
                'members_count' => 2,
            ]);
        } else {
            $chatRoom = $chatRoom->first();
        }
        
        // check user is exists
        if (!ChatRoomMember::whereChatRoomId($chatRoom->id)->whereMemberId(Auth::id())->exists()) {
            $chatRoom->chat_room_members()->create([
                'member_id' => Auth::id(),
                'joined_at' => now(),
                'action_id' => 0,
            ]);
        }
        #get question
        $question = Question::select('id', 'text')->whereId($question_id)->firstOrFail();
        #get form data
        $active = 'completed_forms';
        $completed_form = CompletedForm::findOrFail($form_id);
        $action = Action::with([
            // 'user',
            'project',
        ])->whereCompletedFormId($form_id)->where('question_id', $question_id)->latest()->first();

        $action->user=CheckUserType($action->i_ref_user_role_id,$action->user_id);

        $chatRef = $this->initailizeFirebase()->collection('chat');
        $getActionMessages = $chatRef->document('room_' . $action->id)->collection('messages');
        $messagesObj = $getActionMessages->documents();
        $messages = [];
        $tempArrayUsers = [];
        foreach ($messagesObj as $key => $value) {
            $row = $value->data();
            // $senderId = $row['sender_id'];
            // if ($user = $this->searcharray($senderId, 'id', $tempArrayUsers)) {
            //     $senderUserInfo = $user;
            // } else {
            //     $senderUserInfo = $this->P2bService->getUser($senderId);
            //     array_push($tempArrayUsers, $senderUserInfo);
            // }
            // $row['sender_info'] = $senderUserInfo;
            array_push($messages, $row);
        }
        return view('admin.completed_form.chat', compact('completed_form', 'active', 'messages', 'chatRoom', 'question', 'action'));
    }
    /**
     * update chat from
     */
    public function updateFormChat(Request $request, $id)
    {
        try {
            $input = $request->only(['status']);
            $action = Action::find($id);
            $action->fill($input);
            if ($action->save()) {
                return redirect()->back()->with('success', trans('response.completed_forms_save'));
            }
            return redirect()->back()->with('error', trans('response.req_id_not_found'));
        } catch (Exception $ex) {
            return redirect()->back()->with('error', trans('response.something_went_wrong'));
        }
    }
    /* Save as completed forms */
    public function saveAs(Request $request, $id)
    {

        $id = encrypt_decrypt('decrypt', $id);

        if ($id) {
            $now = Carbon::now('utc')->toDateTimeString();
            //get complete form data
            $formData = CompletedForm::with(['scopeMethodology', 'sections.questions.guides.documents'])->where('id', $id)->first();
            $template_id = !empty($formData->template_id) ? $formData->template_id : 1;
            $form_id = $this->completedFormService->getCompleteFormId($template_id);
            $clone = $formData->replicate();
            $clone->save_as_id = $id;
            $clone->form_id = $form_id;
            if ($clone->save()) {
                ///set scopeMethodology relations
                if ($formData->scopeMethodology) {
                    $scopeData = [];
                    foreach ($formData->scopeMethodology as $scope_methodology) {
                        $scope_temp_id = !empty($scope_methodology->template_id) ? $scope_methodology->template_id : 1;
                        $scopeData[] = array(
                            'template_id' => $scope_temp_id,
                            'completed_form_id' => $clone->id,
                            'snm_name' => $scope_methodology->snm_name,
                            'snm_data' => $scope_methodology->snm_data,
                            'type' => $scope_methodology->type,
                            'created_at' => $now,
                        );
                    }
                    // bulk insert
                    ScopeMethodology::insert($scopeData);
                }

                ///set sections relations
                if ($formData->sections) {
                    foreach ($formData->sections as $section) {
                        $res_section = new Section;
                        $res_section->template_id = (!empty($section->template_id)) ? $section->template_id : 1;
                        $res_section->completed_form_id = $clone->id;
                        $res_section->name = $section->name;
                        $res_section->type = $section->type;
                        $res_section->created_at = $now;
                        $res_section->score = $section->score;
                        $res_section->save();

                        ///clone question data
                        if ($section->questions && $res_section) {
                            foreach ($section->questions as $question) {
                                $res_question = new Question;
                                $res_question->template_id = (!empty($question->template_id)) ? $question->template_id : 1;
                                $res_question->section_id = $res_section->id;
                                $res_question->text = $question->text;
                                $res_question->field = $question->field;
                                $res_question->question_type = $question->question_type;
                                $res_question->type_option = $question->type_option;
                                $res_question->required = $question->required;
                                $res_question->type = $question->type;
                                $res_question->created_at = $now;
                                $res_question->save();

                                ///clone answer data
                                // print_r($question->answers);die;
                                if ($question->answers && $res_question) {
                                    //  $answersData = [];
                                    $answer = $question->answers;
                                    // foreach($question->answers as $answer){
                                    $temp_id = !empty($answer->template_id) ? $answer->template_id : 1;
                                    $answersData = array(
                                        'template_id' => $temp_id,
                                        'completed_form_id' => $clone->id,
                                        'section_id' => $res_question->section_id,
                                        'question_id' => $res_question->id,
                                        'type_option' => !empty($answer->type_option) ? $answer->type_option : null,
                                        'required' => $answer->required,
                                        'type' => $answer->type,
                                        'answer' => $answer->answer,
                                        'created_at' => $now,
                                    );
                                    $answersData = new Answer;
                                    $answersData->template_id = $temp_id;
                                    $answersData->completed_form_id = $clone->id;
                                    $answersData->section_id = $res_question->section_id;
                                    $answersData->question_id = $res_question->id;
                                    $answersData->type_option = !empty($answer->type_option) ? $answer->type_option : null;
                                    $answersData->required = $answer->required;
                                    $answersData->type = $answer->type;
                                    $answersData->answer = $answer->answer;
                                    $answersData->created_at = $now;
                                    $answersData->save();
                                    // 'completed_form_id'=>$clone->id,
                                    // 'section_id'=>$res_question->section_id,
                                    // 'question_id'=>$res_question->id,
                                    // 'type_option'=>!empty($answer->type_option)?$answer->type_option:null,
                                    // 'required'=>$answer->required,
                                    // 'type'=>$answer->type,
                                    // 'answer'=>$answer->answer,
                                    // 'created_at'=> $now,
                                    // }
                                    // bulk insert of answers Data
                                    // Answer::insert($answersData);
                                }

                            }
                        }

                    }
                }

                return redirect()->back()->with('success', trans('response.completed_forms_save'));
            }} else {
            return redirect()->back()->withInput()->with('error', trans('response.req_id_not_found'));
        }

    }

    /* archive completed forms */
    public function archive(Request $request)
    {
        if ($request->id) {
            $id = encrypt_decrypt('decrypt', $request->id);
            $data = CompletedForm::find($id);
            $data->delete();
            $request->session()->flash('success', trans('response.completed_forms_archived'));
            return;

        } else {
            $request->session()->flash('error', trans('response.req_id_not_found'));
            return;

        }

    }

    /* get Archive Listing */
    public function getArchiveListing(Request $request)
    {
        $listing = CompletedForm::onlyTrashed()->with(['Template', 'completed_by', 'business', 'dept_data', 'project_data']);
        if (auth()->check() && auth()->user()->user_type != 'company') {
            if(auth()->user()->user_type=="supplier"){
                $listing = $listing->whereUserId(Auth::id());
            }else{
                $roleID=auth()->user()->users_details->i_ref_role_id;
                $listing = $listing->where("i_ref_user_role_id",$roleID);
            }

        }
        $listing = $listing->get();
        $temp_list = Template::orderBy('id', 'desc')->get();
        $completedBy = $this->P2bService->getallUsers();
        $departments = $this->P2bService->getDepartments();
        $active = 'archive';
        return view('admin.completed_form.deleted', compact('listing', 'temp_list', 'active', 'completedBy', 'departments'));

    }
    /* restore completed forms */
    public function restore(Request $request)
    {
        if ($request->id) {
            $data = CompletedForm::withTrashed()->find($request->id);
            $data->restore();
            $request->session()->flash('success', trans('response.completed_forms_restore'));
            return;

        } else {
            $request->session()->flash('error', trans('response.req_id_not_found'));
            return;

        }

    }
    /*View complete form */
    public function show(Request $request, $id)
    {
        $id = encrypt_decrypt('decrypt', $id);

        $detail = CompletedForm::with(['Template', 'scopeMethodology', 'sections.questions.guides.documents', 'sections.questions.answers', 'business', 'dept_data', 'project_data'])->where('id', $id)->first();
        $active = 'completed_forms';
        return view('admin.completed_form.details', compact('detail', 'active'))->with('no', 1);
    }

    /*edit update complete form */
    public function update(Request $request)
    {
        if ($request->id) {
            $completed_form = CompletedForm::findOrFail($request->id);
            $input = array(
                'id' => $request->id,
                'business_unit_name' => $request->business_unit_name,
                'department_name' => $request->department_name,
                'project_name' => $request->project_name,
            );
            $completed_form->fill($input)->update();
            return redirect()->route('completed_forms')->with('success', trans('response.update'));
        } else {
            return redirect()->back()->withInput()->with('error', trans('response.req_id_not_found'));
        }

    }
    /*
    Answer update complete form
    @param:answer and id
    response:sucees
     */
    public function update_answer(Request $request)
    {
        if ($request->id) {
            $answer_data = Answer::findOrFail($request->id);
            if ($answer_data) {
                if ($request->arr && !empty($request->arr)) {
                    $answer_data->type_option = json_encode($request->arr);
                } else {
                    $answer_data->answer = $request->answer;
                }
                if ($answer_data->save()) {
                    // $evidences = Evidence::where('answer_id', $request->id)->where('section_id', null)->delete();
                    if (isset($request->file) && !empty($request->file)) {
                        foreach ($request->file as $file) {
                            //upload file in uploads folder
                            if (isset($file) && $file !== "") {
                                $fileName = time() . '.' . $file->extension();
                                if ($file->move(public_path('uploads'), $fileName)) {
                                    $doc_type = $file->getClientMimeType();
                                    if (!empty($doc_type)) {
                                        $type = explode("/", $doc_type);
                                        if ($type[0] == 'image') {
                                            $file_type = 1;
                                        } elseif ($type[0] == 'audio') {
                                            $file_type = 2;
                                        } elseif ($type[0] == 'application' && $type[1] == 'pdf') {
                                            $file_type = 3;
                                        } elseif ($type[0] == 'application' && $type[1] != 'pdf') {
                                            $file_type = 5;
                                        } elseif ($type[0] == 'video') {
                                            $file_type = 4;
                                        } else {
                                            $file_type = '';
                                        }
                                    }
                                    $Evidence = new Evidence();
                                    $Evidence->section_id = null;
                                    $Evidence->answer_id = $request->id;
                                    $Evidence->file_name = $fileName;
                                    $Evidence->file_type = !empty($file_type) ? $file_type : null;
                                    $Evidence->type = 2;
                                    $Evidence->save();
                                }
                            }
                        }
                        return 1;
                    } else {
                        return 1;
                    }
                }
                // $request->session()->flash('success', trans('response.answer_update'));
            }
            return 0;

        } else {
            return 0;
        }

    }

    /*
    SaveComments on complete form
    @param:comment,question_id etc.
    response:sucees response.
     */
    public function save_comments(Request $request)
    {
        //For insert new comments
        if ($request->comment) {
            if ($request->type == 1) {

                $comment = new Comment();
                $comment->template_id = $request->template_id;
                $comment->completed_form_id = $request->form_id;
                $comment->section_id = $request->section_id;
                $comment->question_id = $request->question_id;
                $comment->answer_id = $request->answer_id;
                $comment->answer_id = $request->answer_id;
                $comment->comment = $request->comment;
                $comment->save();
                // $request->session()->flash('success', trans('response.commnet_add'));
                return 1;
            }
        } else {
            // $request->session()->flash('error', trans('response.commnet_required'));
            return 0;
        }
    }

    /**
     * display multiple forms
     * on google map
     * with different color pin
     */
    public function google_map()
    {

        $details = CompletedForm::with([
            'Template' => function ($query) {
                $query->select('id', 'color_pin', 'template_prefix');
            },
        ]);
        if (auth()->user()->user_type == 'employee') {
            $details = $details->whereHas('actions', function ($query) {
                $query->where('i_ref_assined_role_id', auth()->user()->users_details->i_ref_role_id);
            });
            $details = $details->orWhere('i_ref_user_role_id', auth()->user()->users_details->i_ref_role_id);
        } else {
            $details = $details->where('status', 2);
        }
        $details = $details->select('id', 'latitude', 'longitude', 'template_id');
        $details = $details->where('latitude', "!=", null)->where('longitude', "!=", null);
        $details = $details->get();

        $latitude = "";
        $longitude = "";

        if (count($details) > 0) {
            $latitude = $details[0]->latitude;
            $longitude = $details[0]->longitude;
            if (!empty($latitude) && !empty($longitude)) {
                Mapper::map($latitude, $longitude, ['marker' => false]);
            }
        }

        // Add information window for each address
        foreach ($details as $key => $detail) {
            if (!empty($detail->latitude) && !empty($detail->longitude)) {
                Mapper::marker($detail->latitude, $detail->longitude, [
                    'icon' => [
                        'path' => 'M10.5,0C4.7,0,0,4.7,0,10.5c0,10.2,9.8,19,10.2,19.4c0.1,0.1,0.2,0.1,0.3,0.1s0.2,0,0.3-0.1C11.2,29.5,21,20.7,21,10.5 C21,4.7,16.3,0,10.5,0z M10.5,5c3,0,5.5,2.5,5.5,5.5S13.5,16,10.5,16S5,13.5,5,10.5S7.5,5,10.5,5z',
                        'fillColor' => '#' . $detail->Template->color_pin,
                        'fillOpacity' => 1,
                        'strokeWeight' => 0,
                        'anchor' => [0, 0],
                        'origin' => [0, 0],
                        'size' => [21, 30],
                    ],
                    'label' => [
                        'text' => $detail->Template->template_prefix,
                        'color' => '#' . $detail->Template->color_pin,
                        'fontFamily' => 'Arial',
                        'fontSize' => '13px',
                        'fontWeight' => 'bold',
                    ],
                    'clickable' => true,
                    'eventClick' => 'window.location.href ="' . route('show', $detail->id_decrypted) . '"',
                ]);
            }
        }

        $active = 'completed_forms';
        return view('admin.completed_form.google_map', compact('details', 'active'));
    }

    public function report(Request $request, $id)
    {

        // print_r($_POST);die;
        $id = encrypt_decrypt('decrypt', $id);

        $detail = CompletedForm::with(['Template', 'scopeMethodology', 'sections.questions.guides.documents', 'sections.questions.answers.evidences', 'sections.questions.actions', 'business', 'dept_data', 'project_data'])->where('id', $id)->first();
        $compnyProfilePic = $this->P2bService->getCompnyProfilePic($detail['company_id']);
        $compnyProfilePic = $compnyProfilePic['vc_logo'];

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
            // $section_wise_score = + $section_wise_score;
            foreach ($sections['questions'] as $key_ques => $questions) {
                if (count($questions['actions']) > 0) {
                    $total_actions++;
                    $actions_list[$total_actions] = $questions->toarray();
                    $actions_list[$total_actions]['section_name'] = $sections->name;
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
            $section_wise_score = $section_wise_score + $total_section_score;
            // array_push($section_wise_score, $total_section_score);
            // rset $total_section_score after looping every section
            $total_section_score = 0;
        }

        // GET USER DETAILS FOR EACH ACTION
        foreach ($actions_list as $key_act => $action) {
            $user = '';

            $user =CheckUserType($action['actions'][0]['i_ref_user_role_id'],$action['actions'][0]['user_id']);
            $actions_list[$key_act]['user'] = (!empty($user) ? $user['vc_fname'] . ' ' . $user['vc_lname'] : null);
            $assigned_user = '';
            $assigned_user =CheckUserType($action['actions'][0]['i_ref_assined_role_id'],$action['actions'][0]['assined_user_id']);
            $actions_list[$key_act]['aasigned_user'] = (!empty($assigned_user) ? $assigned_user['vc_fname'] . ' ' . $assigned_user[''] : null);
     
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
        

        // set the report prefrence filters
        $data['report_filter'] = ['Actions', 'Failed_Items', 'Marks', 'Media_Summery', 'scope_method', 'Color_Coded'];
        $data['report_filter_string'] = implode(" ", $data['report_filter']);

        // set the report prefrence filters after POST
        if ($request->isMethod('post')) {
            $request = array_keys($request->toarray());
            $data['report_filter'] = $request;
            $data['report_filter_string'] = implode(" ", $data['report_filter']);

        }
        $active = 'completed_forms';
        return view('admin.completed_form.report', compact('detail', 'active', 'data', 'compnyProfilePic'))->with('no', 1);
    }

    public function report_pdf(Request $request, $id)
    {
        $detail = CompletedForm::with([
            'Template', 
            'scopeMethodology', 
            'sections.questions.guides.documents', 
            'sections.questions.answers.evidences', 
            'sections.questions.actions.assignee_user',
            'sections.questions.actions.user', 
            'business', 
            'dept_data', 
            'project_data'
        ])->where('id', $id)->first();
        $compnyProfilePic = $this->P2bService->getCompnyProfilePic($detail['company_id']);
        $compnyProfilePic = $compnyProfilePic['vc_logo'];

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
            $section_wise_score = $section_wise_score + $total_section_score;
            // array_push($section_wise_score, $total_section_score);
            // rset $total_section_score after looping every section
            $total_section_score = 0;
        }

        // print_r($actions_list);
        // die;

        // GET USER DETAILS FOR EACH ACTION
        foreach ($actions_list as $key_act => $action) {
            
      
            $user =CheckUserType($action['actions'][0]['i_ref_user_role_id'],$action['actions'][0]['user_id']);
            $actions_list[$key_act]['user'] = (!empty($user) ? $user['vc_fname'] . ' ' . $user['vc_lname'] : null);
            $assigned_user = '';
            $assigned_user =CheckUserType($action['actions'][0]['i_ref_assined_role_id'],$action['actions'][0]['assined_user_id']);
            $actions_list[$key_act]['aasigned_user'] = (!empty($assigned_user) ? $assigned_user['vc_fname'] . ' ' . $assigned_user[''] : null);
     
      
      
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
        $data['report_filter'] = explode(" ", $request->report_filter_string);
        if ($data != "") {
            $pdf = PDF::loadView('pdf.complete_form_report', array('data' => $data, 'detail' => $detail, 'active' => 'completed_forms', 'compnyProfilePic' => $compnyProfilePic));
            // return view('pdf.complete_form_report', array('data' => $data, 'detail' => $detail, 'active' => 'completed_forms', 'compnyProfilePic' => $compnyProfilePic));
            return $pdf->download('complete_form_report.pdf');
            // return $pdf->stream("Halloa.pdf");
        } else {
            return redirect()->back()->withErrors(['Something went wrong. Please try again']);
        }
    }

    public function delete_evidence(Request $request)
    {

        if ($request->id) {
            $id = $request->id;
            $data = Evidence::find($id);
            if ($data->delete()) {
                return 1;
            } else {
                return 0;
            }
            // $request->session()->flash('success', trans('response.completed_forms_archived'));
        } else {
            // $request->session()->flash('error', trans('response.req_id_not_found'));
            return 0;

        }
    }

    /**
     * Upload evidence
     */
    public function uploadEvidence(Request $request)
    {
        try {

            $input = $request->only(['section_id', 'answer_id']);

            if ($request->hasFile('file')) {
                $file = $request->file;
                $name = $this->nameToUnique($file->getClientOriginalName());
                $file->move(public_path('uploads'), $name);
                $input['file_name'] = $name;
            }
            $file_type = $file->getClientMimeType();

            $evidence_type = Evidence::TYPE_DOCUMENT;
            if (str_contains($file_type, 'image')) {
                $evidence_type = Evidence::TYPE_IMAGE;
            } else if (str_contains($file_type, 'audio')) {
                $evidence_type = Evidence::TYPE_AUDIO;
            } else if (str_contains($file_type, 'pdf')) {
                $evidence_type = Evidence::TYPE_PDF;
            } else if (str_contains($file_type, 'video')) {
                $evidence_type = Evidence::TYPE_VIDEO;
            }

            $input['file_type'] = $evidence_type;
            $input['user_id'] = Auth::id();
            $input['type'] = Evidence::EVIDENCE_COMPLETED_FORM;

            $row = Evidence::create($input);
            $row = $row->setAppends(['file_url']);

            $evidencesRows = Evidence::where('answer_id', $input['answer_id'])->get();
            $evidences = view('partials.evidences', compact('evidencesRows'))->render();
            return $this->returnResponse(HTTP_STATUS_OK, true, "Done.", ['evidences' => $evidences, 'answer_id' => $input['answer_id']]);
        } catch (\Exception $ex) {
            return $this->returnResponse(HTTP_STATUS_SERVER_ERROR, false, $ex->getMessage());
        }
    }
}





