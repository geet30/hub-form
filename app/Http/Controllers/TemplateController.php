<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Guide;
use App\Models\Users;
use App\Mail\SendMail;
use App\Models\Section;
use App\Models\Document;
use App\Models\Question;
use App\Models\Template;
use App\Models\UserDetail;
use App\Models\Admin\Folder;
use App\Models\DropdownType;
use Illuminate\Http\Request;
use App\Models\ShareTemplate;
use Illuminate\Http\Response;
use App\Models\DropdownOption;
use App\Models\ScopeMethodology;
use App\Services\P2B as P2BService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\TemplateRequest;
use App\Http\Controllers\FolderController;

use function GuzzleHttp\json_decode;

class TemplateController extends Controller
{
    public $ids = [];
    public $parent_data = [];

    public function __construct()
    {
        $this->P2bService = new P2BService();
    }

    public function index(Request $request)
    {
        //get the templates
        $userId = Auth::id();
        $temp_listings = Template::select('id', 'user_id', 'template_name', 'template_prefix', 'created_at', 'published')->with('scope')->orderBy('id', 'desc');
        if ($request->user() && $request->user()->user_type == 'employee') {
            $userId = auth()->user()->users_details->i_ref_role_id;
            $temp_listings = $temp_listings->where('i_ref_user_role_id', $userId);
            $temp_listings = $temp_listings->orWhereHas('share_templates', function ($query) {
                $query->where('i_ref_user_role_id', auth()->user()->users_details->i_ref_role_id);
            });
        }


        $temp_listings = $temp_listings->get();

        $selectGroups = ['id', 'vc_name'];
        $groups = $this->P2bService->getGroupOnly($selectGroups);

        $whereUsers = [
            ['id', '!=', $userId],
            ['user_type', '!=', "supplier"],
        ];
        // $selectUsers = ['id', 'vc_title', 'vc_fname', 'vc_mname', 'vc_lname', 'email'];
        // $users = $this->P2bService->getallUsers($whereUsers, $selectUsers);
        $users = $this->P2bService->getAllCompanyUsers($whereUsers);

        $active = 'template';
        return view('admin.template.index', compact('temp_listings', 'active', 'users', 'groups'));
    }

    public function createTemplate($folder_id = null)
    {
        $today_date = date("Y-m-d");
        $userId = Auth::id();
        //get the document list
        $doc_listings="";
        if (auth()->check() && auth()->user()->user_type == 'employee') {
            $userId = auth()->user()->users_details->i_ref_role_id;
            $doc_listings = Document::select('id', 'title', 'file_name', 'file_type')->whereNull("expires_at")->
            orWhere("expires_at", ">=", $today_date)
            ->whereRaw("(use_in_mobile = true OR i_ref_owner_role_id = $userId)")->latest()->take(20)->get();
        }else{
            $doc_listings = Document::select('id', 'title', 'file_name', 'file_type')->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->latest()->take(20)->get();
        }

        $folder = new FolderController;

        $folder_id = encrypt_decrypt('decrypt', $folder_id);
        $parent_id = !empty($id) ? $id : 0;
        $folders = Folder::where('parent_folder_id', $parent_id)->with('sub_folders')->get();
        // pr($folders);die;
        $all_folders = Folder::select('id', 'name', 'parent_folder_id')->get()->makeHidden(['encrypted_id'])->toArray();
        $newArray = $folder->countParentFolder($all_folders, $parent_id);
        array_push($this->ids, (int) $parent_id);
        $folder->getParentfolder($newArray);
        $ids = array_unique($this->ids);
        $parent_data = $this->parent_data;
        $total_child =  count($ids);
        $userId = auth()->user()->users_details->i_ref_role_id;
        if (auth()->check() && auth()->user()->user_type == 'employee') {
            $documents = Document::select('id', 'title', 'file_name', 'file_type')->where('folder_id', $parent_id)
            ->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->whereRaw("(use_in_mobile = true OR i_ref_owner_role_id = $userId)")->latest()->take(20)->get();
        } else {
            $documents = Document::select('id', 'title', 'file_name', 'file_type')->where('folder_id', $parent_id)->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->latest()->take(20)->get();
        }
        $active = 'create_template';
        return view('admin.template.create-template', compact('active', 'folders', 'documents', 'parent_id', 'total_child', 'parent_data'));
    }


    /**
     * Save Template data *
     * */
    public function saveTemplate(TemplateRequest $request)
    {
        $question=array_keys($request['question']);
        $section=array_values($request['section']);
        $newsection=array_combine($question,$section);
        $request['section']=$newsection;
     
        // dd(json_decode($request['files'][0][0][0]));
        if ($request->validated()) {

            //save template data
            $template_id = (!empty($request->template_id) ? $request->template_id : "");
            $input = $request->only(['company_id', 'template_name', 'template_prefix', 'color_pin']);
            $input['user_id'] = Auth::id();
            $input['published'] = (!empty($request['publish']) && $request['publish'] == 'Publish') ? 1 : 0;
            //need with template shared user role _id
            // $role_id=UserDetail::Where('i_ref_user_id',"=" , Auth::id())->get();
            // $input['role_id'] = $role_id[0]->i_ref_role_id;
            if (!empty($template_id)) {
                $templateRow = Template::whereId($template_id)->update($input);
            } else {
                $templateRow = Template::Create($input);
                $template_id = $templateRow->id;
            }
            ScopeMethodology::where('template_id', '=', $template_id)->forceDelete();
            Section::where('template_id', '=', $template_id)->forceDelete();
            $ids = Question::Select('id')->where('template_id', '=', $template_id)->get();
            $ques_id = [];
            if (!empty($ids)) {
                foreach ($ids as $id) {
                    // $ques_id .= "" . $id->id . ",";
                    $ques_id []= $id->id;
                }
                // DropdownType::wherein('ques_id', [rtrim($ques_id, ",")])->forceDelete();
                // Guide::wherein('question_id', [rtrim($ques_id, ",")])->forceDelete();
                DropdownType::wherein('ques_id', $ques_id)->forceDelete();
                Guide::wherein('question_id', $ques_id)->forceDelete();
            }
            Question::where('template_id', '=', $template_id)->forceDelete();
         

            if ($templateRow) {

                if (!empty($template_id)) {
                    $scope_methodology = $request['scope_methodology'];
                    //Save scope and methodology data
                    $scopeData = [];
                    foreach ($request['snm_data'] as $snm_data) {
                        // if(!empty($snm_data) && $snm_data != ''){
                        $scopeRow = [
                            "template_id" => $template_id,
                            "snm_name" => $scope_methodology,
                            "snm_data" => $snm_data,
                            "type" => scopeMethodology::TYPE_TEMPLATE,
                        ];
                        array_push($scopeData, $scopeRow);
                        // }
                    }
                    $scopeSave = scopeMethodology::insert($scopeData);
                    //save section data
                    if (!empty($request['section'])) {
                        foreach ($request['section'] as $sec_key => $section) {
                            
                            if (!empty($request['score'][$sec_key])) {
                                $score = $request['score'][$sec_key];
                            } else {
                                $score = '';
                            }
                            if (isset($score) && $score == '1') {
                                $score_value = $score;
                            } else {
                                $score_value = 0;
                            }

                            $sectionRow = Section::create([
                                "template_id" => $template_id,
                                "name" => $section,
                                "score" => $score_value,
                                "type" => Section::TYPE_TEMPLATE,
                            ]);

                            if ($sectionRow) {
                                $section_id = $sectionRow->id;
                                if (!empty($section_id)) {


                                    // save questions data
                                    if (isset($request['question'][$sec_key]) && !empty($request['question'][$sec_key])) {
                                        foreach ($request['question'][$sec_key] as $ques_key => $question) {
                                            $required = isset($request['required'][$sec_key][$ques_key]) ? $request['required'][$sec_key][$ques_key] : '';

                                            $required_value = isset($required) && $required == '1' ? $required : 0;

                                            $option_array = !empty($request['options'][$sec_key][$ques_key]) ? $request['options'][$sec_key][$ques_key] : '';

                                            $options = !empty($option_array) && count($option_array) > 0 ? $option_array : null;

                                            if (!empty($options) && count($options) > 0) {
                                                $option = array_filter($options, function ($option_val) {
                                                    if ($option_val != '') {
                                                        return true;
                                                    }
                                                    return false;
                                                });
                                            }

                                            $questionRow = Question::create([
                                                'template_id' => $template_id,
                                                'section_id' => $section_id,
                                                'text' => $question,
                                                'question_type' => isset($request['type'][$sec_key][$ques_key]) ? $request['type'][$sec_key][$ques_key] : null,
                                                'type_option' => !empty($option) ? $option : null,
                                                'required' => $required_value,
                                                'type' => Question::TYPE_TEMPLATE,
                                            ]);

                                            if ($questionRow) {
                                                $question_id = $questionRow->id;
                                                if (!empty($question_id)) {
                                                    $dropdown_type = '';
                                                    if (isset($request['type'][$sec_key][$ques_key]) && $request['type'][$sec_key][$ques_key] == 2) {
                                                        if (empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                                            $dropdown_type =  $request['dropdown_type'][$sec_key][$ques_key];
                                                            // $option_array = array_values(Template::getdropDownArray());
                                                        } elseif (!empty($request['type_order'][$sec_key][$ques_key]) && empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                                            $dropdown_type =  $request['type_order'][$sec_key][$ques_key];
                                                        }

                                                        if (!empty($dropdown_type)) {
                                                            $dropdownType = DropdownType::create([
                                                                'type_name' => $dropdown_type,
                                                                'selected_type' => 1,
                                                                'ques_id' => $question_id
                                                            ]);

                                                            $saveOption = [];
                                                            if (!empty($request['new_options'][$sec_key][$ques_key][0]) && empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                                                foreach ($request['new_options'][$sec_key][$ques_key] as $option_key => $new_options) {
                                                                    $failed_item = $request['failed_item'][$sec_key][$ques_key];
                                                                    $failed =  ($request['failed_item'][$sec_key][$ques_key] == $option_key) ? 1 : 0;
                                                                    if (!empty($new_options)) {
                                                                        $dropdownOption = [
                                                                            "option_name" => $new_options,
                                                                            "failed_item" => $failed,
                                                                            "color_code" => $request['color_code'][$sec_key][$ques_key][$option_key],
                                                                        ];
                                                                        array_push($saveOption, $dropdownOption);
                                                                    }
                                                                }
                                                            } else {
                                                                $pinColor = array_values(Template::getdropdownColorArray());
                                                                foreach (array_values(Template::getdropDownArray()) as $option_key => $new_options) {
                                                                    if (!empty($new_options)) {
                                                                        $dropdownOption = [
                                                                            "option_name" => $new_options,
                                                                            "failed_item" => 0,
                                                                            "color_code" => $pinColor[$option_key]
                                                                        ];
                                                                        array_push($saveOption, $dropdownOption);
                                                                    }
                                                                }
                                                            }

                                                            $dropdownType->options()->createMany($saveOption);
                                                        }
                                                    }



                                                    $savePicture = [];
                                                    if (!empty($request['files'])) {
                                                        if (isset($request['files'][$sec_key][$ques_key])) {
                                                            $files = $request['files'][$sec_key][$ques_key];
                                                            if (!empty($files)) {
                                                                foreach ($files as $indexin => $name) {
                                                                    if (!empty($name)) {
                                                                        $doc_type = json_decode($name, true);

                                                                        array_push($savePicture, $doc_type);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    $questionRow->guides()->createMany($savePicture);






                                                    if (!empty($request['doc_library_id'][$sec_key][$ques_key]) || !empty($request['notes'][$sec_key][$ques_key])) {

                                                        $document_id = isset($request['doc_library_id'][$sec_key][$ques_key]) ? $request['doc_library_id'][$sec_key][$ques_key] : '';
                                                        $notes = isset($request['notes'][$sec_key][$ques_key]) ? $request['notes'][$sec_key][$ques_key] : '';
                                                        $fileName = "";
                                                        $doc_type = "";
                                                        $document_type = '';


                                                        //save guides data
                                                        if (!empty($file) || !empty($document_id) || !empty($notes)) {
                                                            $saveGuide = [];
                                                            if (!empty($notes)) {
                                                                $notesRow = [
                                                                    "notes" => ($notes) ? $notes : null,
                                                                    "type" => Guide::TYPE_TEMPLATE,
                                                                    "guide_type" => Guide::TYPE_NOTES,
                                                                ];
                                                                array_push($saveGuide, $notesRow);
                                                            }
                                                            if (!empty($document_id)) {
                                                                foreach ($document_id as $doc_library_id) {
                                                                    $documentRow = [
                                                                        "document_id" => ($doc_library_id) ? $doc_library_id : null,
                                                                        "type" => Guide::TYPE_TEMPLATE,
                                                                        "guide_type" => Guide::TYPE_DOCUMENT,
                                                                    ];
                                                                    array_push($saveGuide, $documentRow);
                                                                }
                                                            }
                                                            $questionRow->guides()->createMany($saveGuide);
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
                return redirect()->route('templates')
                    ->with('success', 'Template created successfully!');
            } else {
                return redirect()->route('templates')
                    ->with('error', 'Failed to create Template!');
            }
        }
    }

    /************ archive template ***********/
    public function archive_template(Request $request)
    {
        $id = encrypt_decrypt('decrypt', $request->id);
        if ($id) {
            $data = Template::find($id);

            if ($data->delete()) {
                $request->session()->flash('success', 'Template archived successfully!');
            } else {
                $request->session()->flash('error', 'Failed to archive template!');
            }
        } else {
            $request->session()->flash('error', 'Failed to archive template!');
        }
    }

    /***************** get archive templates listing **************/
    public function getArchiveListing(Request $request)
    {

        $userId = Auth::id();

        $temp_listings = Template::onlyTrashed()->select('id', 'user_id', 'template_name', 'template_prefix', 'created_at', 'published', 'deleted_at');
        $userId = auth()->user()->users_details->i_ref_role_id;
        if ($request->user() && $request->user()->user_type == 'employee') {
            $temp_listings = $temp_listings->where('i_ref_user_role_id', $userId)->orWhereHas('share_templates', function ($query) {
               
                $query->where('i_ref_user_role_id', auth()->user()->users_details->i_ref_role_id);
                $query->whereRaw("`templates`.`deleted_at` IS NOT NULL");
            });
        }


        $temp_listings = $temp_listings->orderBy('deleted_at', 'desc')->get();

        // $temp_listings = Template::onlyTrashed()->where('user_id', Auth::id())->orderBy('deleted_at', 'desc')->get();

        $active = 'archive_template';
        return view('admin.template.deleted-template', compact('temp_listings', 'active'));
    }
    /********** restore template ****************/
    public function restore_template(Request $request)
    {
        if ($request->id) {
            $data = Template::withTrashed()->find($request->id);
            if ($data->restore()) {
                $request->session()->flash('success', 'Template restored successfully!');
            } else {
                $request->session()->flash('error', 'Failed to restore template!');
            }
        } else {
            $request->session()->flash('error', 'Failed to restore template!');
        }
    }

    /*************** edit template ********************/
    public function editTemplate(Request $request, $id)
    {
        $id = encrypt_decrypt('decrypt', $id);
        $today_date = date("Y-m-d");
        $userId = Auth::id();
        if (!empty($id)) {
            //get the document list
            $doc_listings = Document::select('id', 'title', 'file_name', 'file_type');
            if (auth()->check() && auth()->user()->user_type == 'employee') {
                $userId = auth()->user()->users_details->i_ref_role_id;
                $doc_listings = $doc_listings->whereRaw("(use_in_mobile = true OR i_ref_owner_role_id = $userId)")->latest()->take(20)->get();
            }else{
                $doc_listings = $doc_listings->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->latest()->take(20)->get();
            }
    
            $tempdetail = Template::with(['scopeMethodology', 'sections.questions.guides.documents', 'sections.questions.dropdown_type.options'])->where('id', $id)->first();
            // dd($tempdetail->sections);die;
            $active = 'edit_template';
            return view('admin.template.edit-template', compact('active', 'tempdetail', 'doc_listings'));
        } else {
            return redirect()->route('templates')->with('error', 'Template doesnot exist!');
        }
    }

    public function updateTemplate(TemplateRequest $request)
    {
        if ($request->validated()) {

            $saved_sections = (!empty($request->input('saved_data')) ? json_decode($request->input('saved_data'), true) : []);


            $question=array_keys($request['question']);
            $section=array_values($request['section']);
            $newsection=array_combine($question,$section);
            $request['section']=$newsection;
            // dd($request['section']);die;

                        // dd($request->all());die;
           $saved_sections= array_values($saved_sections);
        //    dd($saved_sections[0]);
            // foreach ($request['section'] as $sec_key => $section) {
               
            //     $section_id = !empty($saved_sections[$sec_key]) ? $saved_sections[$sec_key] : '';
            // dd($section_id);
            // }


            // dd($saved_sections);
            // dd($request->all());die;
            // dd(
            //     $files_id = !empty($request['remove_file']) ? explode(",",$request['remove_file'][0]): '');
            //update template data

            $template_id = $request->template_id;
            $input = $request->only(['company_id', 'template_name', 'template_prefix', 'color_pin']);
            $input['user_id'] = Auth::id();
            $input['published'] = (!empty($request['publish']) && $request['publish'] == 'Publish') ? 1 : 0;
            // print_r($input);die;
            $templateRow = Template::whereId($template_id)->update($input);

            if ($templateRow) {
                $scope_methodology = $request['scope_methodology'];
                //update scope and methodology data
                if (!empty($request['snm_data'])) {
                    foreach ($request['snm_data'] as $snm_key => $snm_data) {
                        $scope_id = !empty($request->scope_methodology_id[$snm_key]) ? $request->scope_methodology_id[$snm_key] : '';
                        $scope = scopeMethodology::updateOrCreate(
                            ['id' => $scope_id],
                            ['template_id' => $template_id, 'snm_name' => $scope_methodology, 'snm_data' => $snm_data, 'type' => scopeMethodology::TYPE_TEMPLATE]
                        );
                    }
                }
                $ids = Question::Select('id')->where('template_id', '=', $template_id)->get();
                $ques_id =[];
                // dd($ids);
                if (!empty($ids)) {
                    foreach ($ids as $id) {
                        $ques_id []=  $id->id;
                    }
                    DropdownType::wherein('ques_id', $ques_id)->forceDelete();
                
                }
                Question::where('template_id', '=', $template_id)->forceDelete();


                //update section data
                if (isset($request['section'])) {
                    foreach ($request['section'] as $sec_key => $section) {
                        // $section_id = !empty($request['section_id'][$sec_key]) ? $request['section_id'][$sec_key] : '';
                        $section_id = !empty($saved_sections[$sec_key]) ? $saved_sections[$sec_key] : '';

                        $score = !empty($request['score'][$sec_key]) ? $score = $request['score'][$sec_key] : null;
                        $score_value = isset($score) && $score == '1' ? $score : 0;

                        $section = Section::updateOrCreate(
                            ['id' => $section_id],
                            ['template_id' => $template_id, 'name' => $section, 'score' => $score_value, 'type' => Section::TYPE_TEMPLATE]
                        );

                        $section_id = $section->id;

                        if (!empty($request['question'][$sec_key])) {
                            // update questions data
                            foreach ($request['question'][$sec_key] as $ques_key => $question) {
                                $required = isset($request['required'][$sec_key][$ques_key]) ? $request['required'][$sec_key][$ques_key] : '';
                                $required_value = isset($required) && $required == '1' ? $required_value = $required : 0;


                                $option_array = isset($request['options'][$sec_key][$ques_key]) ? $request['options'][$sec_key][$ques_key] : '';


                                $options = isset($option_array) && !empty($option_array) ? $option_array : null;
                                $option = null;
                                if (!empty($options) && count($options) > 0) {
                                    $option = array_filter($options, function ($option_val) {
                                        if ($option_val != '') {
                                            return true;
                                        }
                                        return false;
                                    });
                                }
                                $question_id = isset($request['question_id'][$sec_key][$ques_key]) ? $request['question_id'][$sec_key][$ques_key] : '';
                                $question_id = "";

                                $question = Question::updateOrCreate(
                                    ['id' => $question_id],
                                    [
                                        'template_id' => $template_id,
                                        'section_id' => $section_id,
                                        'text' => $question,
                                        'question_type' => isset($request['type'][$sec_key][$ques_key]) ? $request['type'][$sec_key][$ques_key] : 0,
                                        'type_option' => $option,
                                        'required' => $required_value,
                                        'type' => Question::TYPE_TEMPLATE,
                                    ]
                                );

                                $question_id = $question->id;


                                //save and update dropdown data 

                                $dropdown_type = '';
                                $dropdown_type_id = '';
                                $dropdown_type = '';
                                if (isset($request['type'][$sec_key][$ques_key]) && $request['type'][$sec_key][$ques_key] == 2) {
                                    if (empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                        $dropdown_type =  $request['dropdown_type'][$sec_key][$ques_key];
                                        // $option_array = array_values(Template::getdropDownArray());
                                    } elseif (!empty($request['type_order'][$sec_key][$ques_key]) && empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                        $dropdown_type =  $request['type_order'][$sec_key][$ques_key];
                                    }

                                    if (!empty($dropdown_type)) {
                                        $dropdownType = DropdownType::create([
                                            'type_name' => $dropdown_type,
                                            'selected_type' => 1,
                                            'ques_id' => $question_id
                                        ]);

                                        $saveOption = [];
                                        if (!empty($request['new_options'][$sec_key][$ques_key][0]) && empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                            foreach ($request['new_options'][$sec_key][$ques_key] as $option_key => $new_options) {
                                                $failed_item = $request['failed_item'][$sec_key][$ques_key];
                                                $failed =  ($request['failed_item'][$sec_key][$ques_key] == $option_key) ? 1 : 0;
                                                if (!empty($new_options)) {
                                                    $dropdownOption = [
                                                        "option_name" => $new_options,
                                                        "failed_item" => $failed,
                                                        "color_code" => $request['color_code'][$sec_key][$ques_key][$option_key],
                                                    ];
                                                    array_push($saveOption, $dropdownOption);
                                                }
                                            }
                                        } else {
                                            $pinColor = array_values(Template::getdropdownColorArray());
                                            foreach (array_values(Template::getdropDownArray()) as $option_key => $new_options) {
                                                if (!empty($new_options)) {
                                                    $dropdownOption = [
                                                        "option_name" => $new_options,
                                                        "failed_item" => 0,
                                                        "color_code" => $pinColor[$option_key]
                                                    ];
                                                    array_push($saveOption, $dropdownOption);
                                                }
                                            }
                                        }

                                        $dropdownType->options()->createMany($saveOption);
                                    }
                                }


                                $savePicture = [];
                                if (!empty($request['files'])) {
                                    if (isset($request['files'][$sec_key][$ques_key])) {
                                        $files = $request['files'][$sec_key][$ques_key];
                                        if (!empty($files)) {
                                            foreach ($files as $indexin => $name) {
                                                if (!empty($name)) {
                                                    $doc_type = json_decode($name, true);
                                                    Guide::where('document_name', $doc_type['document_name'])->where("question_id", $question_id)->forceDelete();
                                                    array_push($savePicture, $doc_type);
                                                }
                                            }
                                        }
                                    }
                                }
                                $question->guides()->createMany($savePicture);



                                $files_ids = !empty($request['remove_file']) ? explode(",", $request['remove_file'][0]) : '';

                                Guide::whereIn('id', $files_ids)->delete();


                                //end save and update dropdown data

                                // $doc = $request->file('document');
                                // $file = isset($doc[$sec_key][$ques_key]) ? $doc[$sec_key][$ques_key] : '';
                                $document_id = isset($request['doc_library_id'][$sec_key][$ques_key]) ? $request['doc_library_id'][$sec_key][$ques_key] : '';
                                $notes = isset($request['notes'][$sec_key][$ques_key]) ? $request['notes'][$sec_key][$ques_key] : '';

                                // $fileName = "";
                                // $doc_type = "";
                                // $document_type = "";

                                //upload file in uploads folder

                                // if (isset($file) && $file !== "") {
                                //     $fileName = time() . '.' . $file->extension();
                                //     $file->move(public_path('uploads'), $fileName);
                                //     $doc_type = $file->getClientMimeType();
                                // } elseif (!empty($request['document_name'][$sec_key][$ques_key]) && !empty($request['document_type'][$sec_key][$ques_key])) {
                                //     $fileName = $request['document_name'][$sec_key][$ques_key];
                                //     $document_type = $request['document_type'][$sec_key][$ques_key];
                                // }



                                //update guides data
                                $notes_id = !empty($request['notes_id'][$sec_key][$ques_key]) ? $request['notes_id'][$sec_key][$ques_key] : '';

                                $library_ids = !empty($request['library_id'][$sec_key][$ques_key]) ? $request['library_id'][$sec_key][$ques_key] : '';

                                if (!empty($notes_id) || !empty($library_ids)) {
                                    if (!empty($notes_id) && empty($notes)) {
                                        Guide::find($notes_id)->delete();
                                    } elseif (!empty($notes) || !empty($notes_id)) {
                                        $guide_data = Guide::updateOrCreate(
                                            ['id' => $notes_id],
                                            [
                                                'question_id' => $question_id,
                                                'notes' => $notes,
                                                'guide_type' => Guide::TYPE_NOTES,
                                                'type' => Guide::TYPE_TEMPLATE,
                                            ]
                                        );
                                    }
                                    // if (!empty($fileName) && !empty($files_id)) {
                                    //     $Guide = Guide::find($files_id);
                                    //     $Guide->question_id = $question_id;
                                    //     $Guide->notes = null;
                                    //     $Guide->document_id = null;
                                    //     $Guide->document_name = ($fileName) ? $fileName : null;
                                    //     $Guide->document_type = ($document_type) ? $document_type : null;
                                    //     $Guide->guide_type = 2;
                                    //     $Guide->type = '1';
                                    //     $Guide->update();
                                    // } elseif (!empty($file)) {
                                    //     $Guide = new Guide();
                                    //     $Guide->question_id = $question_id;
                                    //     $Guide->notes = null;
                                    //     $Guide->document_id = null;
                                    //     $Guide->document_name = ($fileName) ? $fileName : null;
                                    //     $Guide->document_type = ($document_type) ? $document_type : null;
                                    //     $Guide->guide_type = 2;
                                    //     $Guide->type = '1';
                                    //     $Guide->save();
                                    // } elseif (!empty($files_id) && empty($file)) {
                                    //     Guide::find($files_id)->delete();
                                    // }
                                    $total_doc = !empty($document_id) ? count($document_id) : 0;
                                    $total_library_id = !empty($library_ids) ? count($library_ids) : 0;
                                    // print_r('total doc'.$total_doc);
                                    // print_r('total library'.$total_library_id);
                                    // die('asda');
                                    if (!empty($document_id) && !empty($library_ids) && $total_doc > 0 && $total_library_id > 0) {
                                        if ($total_doc == $total_library_id) {
                                            foreach ($library_ids as $library_key => $library_id) {
                                                $Guide = Guide::find($library_id);
                                                $Guide->question_id = $question_id;
                                                $Guide->notes = null;
                                                $Guide->document_id = ($document_id[$library_key]) ? $document_id[$library_key] : null;
                                                $Guide->document_name = null;
                                                $Guide->document_type = null;
                                                $Guide->guide_type = 3;
                                                $Guide->type = '1';
                                                $Guide->update();
                                            }
                                        } else if ($total_doc > $total_library_id) {
                                            foreach ($document_id as $doc_key => $doc_id) {
                                                if (!empty($doc_id) && !empty($library_ids[$doc_id])) {
                                                    $Guide = Guide::find($library_ids[$doc_id]);
                                                    $Guide->question_id = $question_id;
                                                    $Guide->notes = null;
                                                    $Guide->document_id = ($doc_id) ? $doc_id : null;
                                                    $Guide->document_name = null;
                                                    $Guide->document_type = null;
                                                    $Guide->guide_type = 3;
                                                    $Guide->type = '1';
                                                    $Guide->update();
                                                } elseif (!empty($doc_id) && empty($library_ids[$doc_id])) {
                                                    $Guide = new Guide();
                                                    $Guide->question_id = $question_id;
                                                    $Guide->notes = null;
                                                    $Guide->document_id = ($doc_id) ? $doc_id : null;
                                                    $Guide->document_name = null;
                                                    $Guide->document_type = null;
                                                    $Guide->guide_type = 3;
                                                    $Guide->type = '1';
                                                    $Guide->save();
                                                }
                                            }
                                        } else if ($total_doc < $total_library_id) {
                                            foreach ($library_ids as $library_key => $library_id) {
                                                if (!empty($document_id[$library_key]) && !empty($library_id)) {
                                                    $Guide = Guide::find($library_id);
                                                    $Guide->question_id = $question_id;
                                                    $Guide->notes = null;
                                                    $Guide->document_id = ($document_id[$library_key]) ? $document_id[$library_key] : null;
                                                    $Guide->document_name = null;
                                                    $Guide->document_type = null;
                                                    $Guide->guide_type = 3;
                                                    $Guide->type = '1';
                                                    $Guide->update();
                                                } elseif (!empty($library_id) && empty($document_id[$library_key])) {
                                                    Guide::find($library_id)->delete();
                                                }
                                            }
                                        }
                                    } elseif (!empty($document_id) && empty($library_ids) && $total_library_id <= 0 && $total_doc > 0) {
                                        foreach ($document_id as $doc_key => $doc_id) {
                                            $Guide = new Guide();
                                            $Guide->question_id = $question_id;
                                            $Guide->notes = null;
                                            $Guide->document_id = ($doc_id) ? $doc_id : null;
                                            $Guide->document_name = null;
                                            $Guide->document_type = null;
                                            $Guide->guide_type = 3;
                                            $Guide->type = '1';
                                            $Guide->save();
                                        }
                                    } elseif (empty($document_id) && !empty($library_ids) && $total_library_id > 0 && $total_doc <= 0) {
                                        foreach ($library_ids as $library_key => $library_id) {
                                            Guide::find($library_id)->delete();
                                        }
                                    }
                                } else {
                                    if (!empty($notes)) {
                                        $Guide = new Guide();
                                        $Guide->question_id = $question_id;
                                        $Guide->notes = ($notes) ? $notes : null;
                                        $Guide->guide_type = 1;
                                        $Guide->type = '1';
                                        $Guide->save();
                                    }
                                    // if (!empty($file)) {
                                    //     $Guide = new Guide();
                                    //     $Guide->question_id = $question_id;
                                    //     $Guide->document_name = ($fileName) ? $fileName : null;
                                    //     $Guide->document_type = ($document_type) ? $document_type : null;
                                    //     $Guide->guide_type = 2;
                                    //     $Guide->type = '1';
                                    //     $Guide->save();
                                    // }
                                    if (!empty($document_id)) {
                                        foreach ($document_id as $doc_key => $doc_id) {
                                            $Guide = new Guide();
                                            $Guide->question_id = $question_id;
                                            $Guide->document_id = ($doc_id) ? $doc_id : null;
                                            $Guide->guide_type = 3;
                                            $Guide->type = '1';
                                            $Guide->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                return redirect()->route('templates')
                    ->with('success', 'Template edit successfully!');
            } else {
                return redirect()->route('templates')
                    ->with('error', 'Failed to edit Template!');
            }
        }
    }
    /**
     * Delete Scope
     * Inthis we can delete the data
     * in scope and methodology in edit template
     */

    public function deleteScope(Request $request)
    {
        if ($request->id) {

            $data = scopeMethodology::find($request->id);

            if ($data->delete()) {
                return response()->json([
                    'response' => 'Deleted',
                ]);
            } else {
                return response()->json([
                    'response' => 'not deleted',
                ]);
            }
        } else {
            return response()->json([
                'response' => 'not deleted',
            ]);
        }
    }

    /**
     * Delete Questions
     * Inthis we can delete the question
     * in sections in edit template
     */

    public function deleteQuestion(Request $request)
    {
        try {
            if ($request->id) {

                $data = Question::find($request->id);

                if ($data->delete()) {
                    return $this->returnResponse(Response::HTTP_OK, true, "Question deleted successfully.");
                } else {
                    return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, true, "Unable to delete the Question!");
                }
            } else {
                return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, true, "Unable to delete the Question!");
            }
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * Delete Sections
     * Inthis we can delete the section
     * in edit template
     */

    public function deleteSection(Request $request)
    {
        if ($request->id) {

            $data = Section::find($request->id);

            if ($data->delete()) {
                return response()->json([
                    'response' => 'Deleted',
                ]);
            } else {
                return response()->json([
                    'response' => 'not deleted',
                ]);
            }
        } else {
            return response()->json([
                'response' => 'not deleted',
            ]);
        }
    }

    /**
     * get data of
     * share templates
     */
    public function share_template(Request $request)
    {
        $id = encrypt_decrypt('decrypt', $request->id);
        if (!empty($id)) {
            $temp_data = Template::select('id', 'template_name', 'template_prefix')->
            with(['share_templates'])->where('id', $id)->first();
            foreach($temp_data->share_templates as $key => $users){
               $temp_data->share_templates->user= CheckUserType($users->i_ref_user_role_id,$users->user_id);
            }
            
            if (!empty($temp_data)) {
                return $temp_data;
            }
        }
    }

    /**
     * share template
     * to user of current
     * P2B system
     */
    public function share_template_with(Request $request)
    {
        try {
            if (!empty($request->user_id) && !empty($request->temp_id) && !empty($request->form)) {
                $when = now()->addMinutes(1);
                if ($request->form == 'group') {
                    $share_temp_data = ShareTemplate::Where('group_id', $request->user_id)->Where('template_id', $request->temp_id)->exists();
                    if ($share_temp_data ==  true) {
                        return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, true, "This template is already shared with this group.");
                    } else {
                        $grp = $this->P2bService->getGroup($request->user_id);
                        if (!empty($grp)) {
                            $shared_data = [];
                            foreach ($grp['group_role'] as $group_role) {
                                $user = $group_role['roles']['user_detail']['user'];
                                if (!empty($user)) {
                                    $share_temp_user = ShareTemplate::Where('user_id', $user['id'])->Where('template_id', $request->temp_id)->exists();
                                    if ($share_temp_user == false) {
                                        $data = array(
                                            'name' => $user['vc_fname'] . ' ' . $user['vc_mname'] . ' ' . $user['vc_lname'],
                                            'temp_id' => $request->temp_id,
                                        );
                                        $mail_id = $user['email'];
                                        $mail = Mail::to($mail_id)->later($when, new SendMail($data));
                                    }
                                    $shareTemp = [
                                        "user_id" => $user['id'],
                                        "i_ref_user_role_id" => $group_role['roles']['id'],
                                        "group_id" => $request->user_id,
                                        "template_id" => $request->temp_id,
                                    ];
                                    $share_temp = ShareTemplate::updateOrCreate(
                                        $shareTemp,
                                        $shared_data
                                    );
                                }
                            }
                            return $this->returnResponse(Response::HTTP_OK, true, "Template shared successfully.");
                        } else {
                            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, true, "There is no user in this group!");
                        }
                    }
                } else {
                    
                    $userroleid=CheckUserTypeAndGetRoleID($request->user_id);
                    if($userroleid['user_type']!="supplier"){
                        $share_temp_data = ShareTemplate::Where('i_ref_user_role_id', $userroleid['role_id'])->Where('template_id', $request->temp_id)->exists();
                    }else{
                        $share_temp_data = ShareTemplate::Where('user_id', $request->user_id)->Where('template_id', $request->temp_id)->exists();
                    }
                    
                    

                    if ($share_temp_data) {
                        return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, true, "This template is already shared with this user.");
                    }
                    $user = $this->P2bService->getUser($request->user_id);
                    if (!empty($user)) {
                        $data = array(
                            'name' => $user['vc_fname'] . ' ' . $user['vc_mname'] . ' ' . $user['vc_lname'],
                            'temp_id' => $request->temp_id,
                        );
                        $mail_id = $user['email'];
                        $mail = Mail::to($mail_id)->later($when, new SendMail($data));
                        $SareTemp = new ShareTemplate();
                        $SareTemp->user_id = $request->user_id;
                        $SareTemp->i_ref_user_role_id = $userroleid['role_id'];
                        $SareTemp->template_id = $request->temp_id;
                        $SareTemp->save();
                        return $this->returnResponse(Response::HTTP_OK, true, "Template shared successfully.");
                    }
                }
            } else {
                return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Failed to share template!');
            }
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * delete user
     * from shared template
     */
    public function unshare_template(Request $request)
    {
        try {
            if ($request->id) {
                $data = ShareTemplate::find($request->id);
                if ($data->delete()) {
                    return $this->returnResponse(Response::HTTP_OK, true, "User is removed successfully.");
                }
            } else {
                return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, 'Failed to remove the User!');
            }
        } catch (Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * this function is
     * for testing
     */
    public function testing(Request $request)
    {
        return view('admin.template.testing');
    }

    /**
     * get documents of 
     * document library 
     * @return document
     */
    public function document_library(Request $request)
    {
        $today_date = date("Y-m-d");
        try {
            $doc_listings = '';
            $userId =  Auth::id();
            if (auth()->check() && auth()->user()->user_type == 'employee') {
                $userId =  auth()->user()->users_details->i_ref_role_id;
                $doc_listings = Document::select('id', 'title', 'file_name', 'file_type')->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->where('title', 'LIKE', "%{$request->search}%")->whereRaw("(use_in_mobile = true OR i_ref_owner_role_id = $userId)")->latest()->get();
            } else {
                $doc_listings = Document::select('id', 'title', 'file_name', 'file_type')->where('title', 'LIKE', "%{$request->search}%")->whereNull("expires_at")->orWhere("expires_at", ">=", $today_date)->latest()->get();
            }

            $data['folderStructure'] = view('partials.document_listing', compact('doc_listings'))->render();
            return $this->returnResponse(Response::HTTP_OK, true, "Document Library", $data);
        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }

    /**
     * return drop down 
     * view
     */
    public function dropdown_options(Request $request)
    {
        try {
            $section_no = $request->section;
            $question_no = $request->question;
            $data['dropdown'] = view('partials.template-dropdown', compact(['section_no', 'question_no']))->render();
            return $this->returnResponse(Response::HTTP_OK, true, "Dropdown Options", $data);
        } catch (\Exception $ex) {
            return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
        }
    }



    public function saveQuestion(TemplateRequest $request)
    {
        if ($request->validated()) {

            //update template data
            $template_id = (!empty($request->template_id) ? $request->template_id : "");
            $input = $request->only(['company_id', 'template_name', 'template_prefix', 'color_pin']);
            $input['user_id'] = Auth::id();
            $input['published'] = (!empty($request['publish']) && $request['publish'] == 'Publish') ? 1 : 0;

            $sec_key = $request['section_id'] - 1;
            $ques_key = $request['question_id'] - 1;


            if (!empty($template_id)) {
                $templateRow = Template::whereId($template_id)->update($input);
            } else {
                $templateRow = Template::Create($input);
                $template_id = $templateRow->id;
            }
            try {

                if ($templateRow) {

                    $section = $request['section'][$sec_key];
                    $score = !empty($request['score'][$sec_key]) ? $score = $request['score'][$sec_key] : null;
                    $score_value = isset($score) && $score == '1' ? $score : 0;

                    $saved_sections = (!empty($request->input('saved_data')) ? json_decode($request->input('saved_data'), true) : []);

                    if (!empty($saved_sections) && array_key_exists($request['section_id'], $saved_sections)) {
                        // if(array_key_exists($request['section_id'],$saved_sections)){
                        //     echo "true";
                        // }  
                        $sectionRow = Section::updateOrCreate(
                            ['id' => $saved_sections[$request['section_id']]],
                            ['template_id' => $template_id, 'name' => $section, 'score' => $score_value, 'type' => Section::TYPE_TEMPLATE]
                        );
                    } else {
                        $sectionRow = Section::Create(
                            ['template_id' => $template_id, 'name' => $section, 'score' => $score_value, 'type' => Section::TYPE_TEMPLATE]
                        );
                    }

                    $section_id = $sectionRow->id;
                    $saved_sections_id = $sectionRow->id;


                    $required = isset($request['required'][$sec_key][$ques_key]) ? $request['required'][$sec_key][$ques_key] : '';
                    $required_value = isset($required) && $required == '1' ? $required : 0;

                    $option_array = !empty($request['options'][$sec_key][$ques_key]) ? $request['options'][$sec_key][$ques_key] : '';

                    $options = !empty($option_array) && count($option_array) > 0 ? $option_array : null;

                    if (!empty($options) && count($options) > 0) {
                        $option = array_filter($options, function ($option_val) {
                            if ($option_val != '') {
                                return true;
                            }
                            return false;
                        });
                    }
                    $question = $request['question'][$sec_key][$ques_key];

                    $questionRow = Question::create([
                        'template_id' => $template_id,
                        'section_id' => $section_id,
                        'text' => $question,
                        'question_type' => isset($request['type'][$sec_key][$ques_key]) ? $request['type'][$sec_key][$ques_key] : null,
                        'type_option' => !empty($option) ? $option : null,
                        'required' => $required_value,
                        'type' => Question::TYPE_TEMPLATE,
                    ]);

                    // $saved_question = (!empty($request->input('new_questions')) ? json_decode($request->input('new_questions'), true) : []);


                    $question_id = $questionRow->id;
                    $dropdown_type = '';

                    if (isset($request['type'][$sec_key][$ques_key]) && $request['type'][$sec_key][$ques_key] == 2) {
                        if (empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                            $dropdown_type =  $request['dropdown_type'][$sec_key][$ques_key];
                            // $option_array = array_values(Template::getdropDownArray());
                        } elseif (!empty($request['type_order'][$sec_key][$ques_key]) && empty($request['dropdown_type'][$sec_key][$ques_key])) {
                            $dropdown_type =  $request['type_order'][$sec_key][$ques_key];
                        }

                        if (!empty($dropdown_type)) {
                            $dropdownType = DropdownType::create([
                                'type_name' => $dropdown_type,
                                'selected_type' => 1,
                                'ques_id' => $question_id
                            ]);

                            $saveOption = [];
                            if (!empty($request['new_options'][$sec_key][$ques_key][0]) && empty($request['type_order'][$sec_key][$ques_key]) && !empty($request['dropdown_type'][$sec_key][$ques_key])) {
                                foreach ($request['new_options'][$sec_key][$ques_key] as $option_key => $new_options) {
                                    $failed_item = $request['failed_item'][$sec_key][$ques_key];
                                    $failed =  ($request['failed_item'][$sec_key][$ques_key] == $option_key) ? 1 : 0;
                                    if (!empty($new_options)) {
                                        $dropdownOption = [
                                            "option_name" => $new_options,
                                            "failed_item" => $failed,
                                            "color_code" => $request['color_code'][$sec_key][$ques_key][$option_key],
                                        ];
                                        array_push($saveOption, $dropdownOption);
                                    }
                                }
                            } else {
                                $pinColor = array_values(Template::getdropdownColorArray());
                                foreach (array_values(Template::getdropDownArray()) as $option_key => $new_options) {
                                    if (!empty($new_options)) {
                                        $dropdownOption = [
                                            "option_name" => $new_options,
                                            "failed_item" => 0,
                                            "color_code" => $pinColor[$option_key]
                                        ];
                                        array_push($saveOption, $dropdownOption);
                                    }
                                }
                            }

                            $dropdownType->options()->createMany($saveOption);
                        }
                    }

                    $sec_key = $request['section_id'] - 1;
                    $ques_key = $request['question_id'] - 1;


                    $savePicture = [];
                    if (!empty($request['files'])) {
                        if (isset($request['files'][$sec_key][$ques_key])) {
                            $files = $request['files'][$sec_key][$ques_key];
                            if (!empty($files)) {
                                foreach ($files as $indexin => $name) {
                                    if (!empty($name)) {
                                        $doc_type = json_decode($name, true);

                                        array_push($savePicture, $doc_type);
                                    }
                                }
                            }
                        }
                    }
                    $questionRow->guides()->createMany($savePicture);

                    $sec_key = $request['section_id'] - 1;
                    $ques_key = $request['question_id'] - 1;

                    $document_id = isset($request['doc_library_id'][$sec_key][$ques_key]) ? $request['doc_library_id'][$sec_key][$ques_key] : '';
                    $notes = isset($request['notes'][$sec_key][$ques_key]) ? $request['notes'][$sec_key][$ques_key] : '';

                    if (!empty($document_id) || !empty($notes)) {
                        $saveGuide = [];
                        if (!empty($notes)) {
                            $notesRow = [
                                "notes" => ($notes) ? $notes : null,
                                "type" => Guide::TYPE_TEMPLATE,
                                "guide_type" => Guide::TYPE_NOTES,
                            ];
                            array_push($saveGuide, $notesRow);
                        }
                        if (!empty($document_id)) {
                            foreach ($document_id as $doc_library_id) {
                                $documentRow = [
                                    "document_id" => ($doc_library_id) ? $doc_library_id : null,
                                    "type" => Guide::TYPE_TEMPLATE,
                                    "guide_type" => Guide::TYPE_DOCUMENT,
                                ];
                                array_push($saveGuide, $documentRow);
                            }
                        }
                        $questionRow->guides()->createMany($saveGuide);
                    }

                    $saved_question_data = array(
                        "template_id" => $template_id,
                        "saved_section_id" => $saved_sections_id,
                        "question_id" => $question_id
                    );
                    return json_encode($saved_question_data);
                }
            } catch (\Error $ex) {
                // return $this->returnResponse(Response::HTTP_INTERNAL_SERVER_ERROR, false, $ex->getMessage());
                return json_encode($ex->getMessage());
            }
        }
    }


    public function saveimage(Request $request)
    {

        if (!empty($request['document'])) {

            $doc = $request->file('document');
            $file = isset($doc) ? $doc : '';
            $fileName = "";
            $doc_type = "";
            $document_type = "";
            $filename = "";
            $fileRow = [];
            for ($i = 0; $i < count($file); $i++) {
                //upload file in uploads folder
                if (isset($file) && $file !== "") {
                    $fileName = time() . "." . rand() . "." . $i . '.' . $file[$i]->extension();
                    $filename .= $fileName . ",";
                    $file[$i]->move(public_path('uploads'), $fileName);
                    $doc_type = $file[$i]->getClientMimeType();

                    if (!empty($doc_type)) {
                        $type = explode("/", $doc_type);
                        if ($type[0] == 'image') {
                            $document_type = Guide::TYPE_IMAGE;
                        } elseif ($type[0] == 'audio') {
                            $document_type = Guide::TYPE_AUDIO;
                        } elseif ($type[0] == 'application' && $type[1] == 'pdf') {
                            $document_type = Guide::TYPE_PDF;
                        } elseif ($type[0] == 'application' && $type[1] != 'pdf') {
                            $document_type = Guide::TYPE_DOC;
                        } elseif ($type[0] == 'video') {
                            $document_type = Guide::TYPE_VIDEO;
                        } else {
                            $document_type = '';
                        }
                    }

                    $fileRow = [
                        "document_name" => isset($fileName) ? $fileName : null,
                        "document_type" => isset($document_type) ? $document_type : null,
                        "type" => Guide::TYPE_TEMPLATE,
                        "guide_type" => Guide::TYPE_FILE,
                    ];
                }
            }
            echo json_encode($fileRow);
        }
    }

    public function getThumnails(Request $request)
    {

        $tempdetail = Template::with(['sections.questions.guides.documents'])->where('id', $request->temp_id)->first();
        //   dd($tempdetail);
        if (!empty($tempdetail)) {
            $obj = [];
            if (!empty($tempdetail->sections) && $tempdetail->sections[($request->section - 1)]) {
                if (!empty($tempdetail->sections) && !empty($tempdetail->sections[($request->section - 1)]) && !empty($tempdetail->sections[($request->section - 1)]->questions) && !empty($tempdetail->sections[($request->section - 1)]->questions[($request->question - 1)])) {
                    if ($tempdetail->sections[($request->section - 1)]->questions[($request->question - 1)]->guides) {

                        foreach ($tempdetail->sections[($request->section - 1)]->questions[($request->question - 1)]->guides as $key => $value) {
                            // dd(filesize(public_path('uploads')."/".$value['document_name']));
                            $obj[$key]['id'] = $value['id'];
                            $obj[$key]['name'] = trim($value['document_name']);
                            $obj[$key]['size'] = filesize(public_path('uploads') . "/" . $value['document_name']);
                            $obj[$key]['link'] = url('/') . '/uploads/' . $value['document_name'];
                            $obj[$key]['guide'] = array(
                                'document_type' => $value->document_type,
                                "document_type" => ($value->document_type) ? $value->document_type : null,
                                "type" => $value->type,
                                "guide_type" => $value->guide_type,
                                "document_name" => trim($value['document_name']),
                            );
                        }

                        echo json_encode($obj);

                        die;
                    }
                }
            }
        }
        echo json_encode([]);
        die;
    }
}
