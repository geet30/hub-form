@extends('admin.layout.app')
@section('title','Edit Template')
@section('header_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<style>
    .pre_loader {
        left: 40%;
    }

    .myPlaceholder {
        border: 3px dotted blue;
        height: 30px;
        margin: 10px;
        border: 3px dotted blue;
        height: 30px;
        margin: 10px;
        border: 1px solid green;
        background-color: white;
        -webkit-box-shadow: 0px 0px 10px #888;
        -moz-box-shadow: 0px 0px 10px #888;
        box-shadow: 0px 0px 10px #888;
    }

    .dropzone .dz-preview .dz-image img {
    display: block;
    width: 100%;
    height: 100%;
}

    button.addquestionbutton {
        display: block;
    }


    /* Templete */
.guide_container {
    width: 100%;
    max-width: 535px;
}
.dropzone .dz-message {
    margin: 0;
} 
.dropzone .dz-preview {
    margin: 0;
}
.dropzone.dz-started .dz-message {
    display: block;
}
.well {
    padding: 16px;
    margin-bottom: 0;
}
.dropzone .dz-preview .dz-image {
    position: static;
}
</style>

@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <div class="errorTxt"> </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>{{ trans('label.edit_template') }}</span>
                </li>
            </ul>
        </div>

        {{ Form::open(array('action' => 'TemplateController@updateTemplate', 'method' => 'post', 'id' => 'edit_template', 'enctype' => 'multipart/form-data'))}}
        <div class="pre_loader">
            <img src="{{asset('assets/images/loading.gif')}}" alt="">
        </div>
        <div class="row">
            <div class="wrapper create-temp-wrap">
                <div class="">
                    <div class="page-wrap edit_form_accordion">
                        <div class="adinfull">
                            <div class="addinname">
                                <h1>{{ trans('label.edit_template') }}</h1>
                            </div>
                            <div class="editlogic">
                                <div class="editlogicheading">
                                </div>
                                <div class="variable-type-sub">
                                    <input type="hidden" name="template_id" class="template_id" value="{{$tempdetail->id}}">
                                    <input type="text" name="template_name" class="template_name" placeholder="Template Name" maxlength="30" value="{{$tempdetail->template_name}}">
                                </div>
                                <div class="variable-type-sub">
                                    <input type="text" name="template_prefix" class="template_prefix" placeholder="Template prefix" maxlength="30" value="{{$tempdetail->template_prefix}}">
                                </div>
                                <div class="variable-type-sub">

                                    <select name="color_pin" class="color_pin" id="color_pin" name="color_pin">
                                        <option value="">Set color pin</option>
                                        @foreach (App\Models\Template::getpinColorArray() as $key => $value)
                                        <option value="{{ $value }}" class="{{$key}}-pin" {{ $tempdetail->color_pin == $value ? 'selected' : '' }}>{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="uploaddocadin">
                                    <a href="{{ route('templates') }}">
                                        <input type="button" class="cancel-btn" id="close_button" value="Close">
                                    </a>
                                    <?php if ($tempdetail->published == 0) { ?>
                                        <input type="button" name="save" class="blue-btn save_template" id="save_template" value="Save">
                                    <?php } ?>
                                    <input type="hidden" name="publish" class="blue-btn " id="publish_value" value="">


                                    <input type="submit" name="" class="blue-btn publish_template" id="publish_template" value="Publish">
                                </div>
                            </div>
                            <div class="row">
                            </div>
                            <div class="datadocumentcollaspe">
                                <div class="collaspeinner">
                                    <div id="accordion">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" onclick="stop_submit()">
                                                                <i class="fas fa-caret-down"></i>
                                                                <i class="fas fa-caret-right"></i>
                                                                Overview
                                                            </button>
                                                        </h5>
                                                        <div class="overview-comment"> This section will be filled by the user through the App. </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="collapseOne" class="collapse in ovrviwe" aria-labelledby="headingOne" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="text" placeholder="Title" name="title" maxlength="30" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="userimg"><img src="{{ asset('assets/edit_form/images/defaultpic.jpeg')}}"></div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="company_id" class="company_id" id="company_id" value="1">
                                                                <input type="text" placeholder="Company Name" name="company_name" class="company_name" id="company_name" value="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="text" placeholder="Selected Date" name="selected_date" class="select_date" id="select_date" value="" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="user_id" class="user_id" id="user_id" value="1">
                                                                <input type="text" placeholder="Completed by" name="user_name" class="completed_by" id="completed_by" value="" readonly>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="location_id" class="location_id" id="location_id" value="1">
                                                                <input type="text" placeholder="Location" name="location_name" class="location" id="location" value="" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="business_unit_id" class="business_unit_id" id="business_unit_id" value="1">
                                                                <input type="text" placeholder="Business Unit" name="business_unit_name" id="business_unit" class="business_unit" value="" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="department_id" class="department_id" id="department_id" value="1">
                                                                <input type="text" placeholder="Department" name="department_name" class="deparment" id="deparment" value="" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="filterwidth">
                                                                <input type="hidden" name="project_id" class="project_id" id="project_id" value="1">
                                                                <input type="text" placeholder="Project" name="project_name" class="project" id="project" value="" readonly>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- <div class="adinbutton">
                                                <button>Add New Page</button>
                                            </div>-->
                                                </div>

                                            </div>
                                        </div>


                                        <div class="card">
                                            <!-- <div class="row" style="padding-left:12px"> -->
                                            <div class="card-header" id="headingOne">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" onclick="stop_submit()">
                                                                <i class="fas fa-caret-down"></i>
                                                                <i class="fas fa-caret-right"></i>
                                                            </button>
                                                            <span class="scope_methodology" contenteditable="false">{{count($tempdetail->scopeMethodology)>0? $tempdetail->scopeMethodology[0]->snm_name : 'Scope and Methodology' }}</span>
                                                            <input id="scope_methodology_input" type="hidden" name="scope_methodology" value="{{count($tempdetail->scopeMethodology)>0? $tempdetail->scopeMethodology[0]->snm_name : 'Scope and Methodology' }}">

                                                            <span><i class="fas fa-pencil-alt" id="edit_scope"></i></span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseTwo" class="collapse scope_meth" aria-labelledby="collapseTwo" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="filterwidth scope_container">
                                                            @if(!empty($tempdetail->scopeMethodology))
                                                            @foreach($tempdetail->scopeMethodology as $key=>$scope)
                                                            @if($scope->type == 1)
                                                            <input id="scope_methodology_id" type="hidden" name="scope_methodology_id[]" value="{{($scope->id)? $scope->id : '' }}">
                                                            <div class="scope" id="scope_{{$key+1}}">
                                                                @if ($key >=1) <a class='remove_scope' onclick="delete_scope('{{$key+1}}','{{$scope->id}}')"><span class='glyphicon glyphicon-remove pull-right' alt='Remove'></span></a>&nbsp;@endif
                                                                <textarea placeholder="Type your text here" id="scopetxt_{{$key+1}}" name="snm_data[]" maxlength="200" value="{{$scope->snm_data}}" class="scopetxt">{{$scope->snm_data}}</textarea>
                                                            </div>
                                                            @endif
                                                            @endforeach
                                                            @else
                                                            <div class="scope" id="scope_1">
                                                                <textarea placeholder="Type your text here" id="scopetxt_1" name="snm_data[]" maxlength="200" class="scopetxt"></textarea>
                                                            </div>
                                                            @endif
                                                            <a class="scopeadd" id="add_more_scope">+ Add more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                        </div>
                                        <input type="hidden" id="has_permission" value='@if(auth()->user()->userHasFormPermission("Document Library") ||  auth()->user()->user_type == ' company')1 @else 0 @endif'>
                                        <div class="section_contain">
                                            @if(isset($tempdetail->sections))
                                            <?php $saved_sections = [];
                                            $saved_sectionss = array(); ?>
                                            @foreach ($tempdetail->sections as $key=>$sections)
                                            @if($sections->type == 1)
                                            <?php
                                            $saved_sections[$key] = $sections->id;
                                            $saved_sectionss[($key+1)]= $sections->id;
                                            $queskey=0;
                                            ?>
                                            <div class="section_container" id="section_container_{{$key+1}}">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse{{$key+1}}" aria-expanded="true" aria-controls="collapse{{$key+1}}" onclick="stop_submit()">
                                                                        <i class="fas fa-caret-down"></i>
                                                                        <i class="fas fa-caret-right"></i>
                                                                    </button>
                                                                    <input type="hidden" name="saved_sections" value="<?php echo json_encode($saved_sections); ?>">
                                                                    <input type="hidden" name="saved_questions" id="saved_questions" value="">

                                                                    <input type="hidden" name="new_questions" id="new_questions" value="">

                                                                    <input type="hidden" name="remove_image[]" id="remove_image" value="">

                                                                    <input type="hidden" name="section_id[]" value="{{$sections->id}}">
                                                                    <span class="section" contenteditable="false" id="section_title_{{$key+1}}" onkeypress="section_value('{{$key+1}}')" onkeyup="section_value('{{$key+1}}')"> {{$sections->name}}</span>
                                                                    <input type="hidden" name="section[]" value="{{$sections->name}}" id="section_{{$key+1}}">
                                                                    <span><i class="fas fa-pencil-alt edit_section" onclick="edit_section_title('{{$key+1}}')"></i></span>
                                                                    <div class="dropdown more-btn section-dropdown">
                                                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More</button>
                                                                        <div class="dropdown-menu " aria-labelledby="dropdownMenu2">
                                                                            @if($key!=0)
                                                                            <a class="dropdown-item deletelist" onclick="delete_sectionId('{{$key+1}}', '{{$sections->id}}')">Delete</a>
                                                                            @endif
                                                                            <a class="dropdown-item add_section" onclick="add_section()">Add Section</a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dropdown more-btn">
                                                                        <button class="btn btn-secondary dropdown-toggle scrorebtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Score
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item"> <input type="checkbox" name="score[]" id="score{{$key+1}}" value="1" {{ ($sections->score == 1 ) ? 'checked' : '' }}>Score</a>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="collapse{{$key+1}}" class="collapse" aria-labelledby="collapse{{$key+1}}" data-parent="#accordion">
                                                        <div class="card-body">
                                                            <div class="question_container" id="question_container_{{$key+1}}">
                                                                @if (isset($sections->questions))
                                                                @foreach ($sections->questions as $queskey=>$question)
                                                                <div class="question_section_{{$key+1}}" id="question_section_{{$key+1}}_{{$queskey+1}}" data-questionid="question_section_{{$key+1}}_{{$queskey+1}}"> 
                                                                    <div class="row question-main-sec">
                                                                        <div class="col-md-1 question-action">
                                                                            <span class="question-serial">{{$queskey+1}}</span>
                                                                            <button class="action-edit" onclick="stop_submit()">
                                                                                <i class="fas fa-pencil-alt"></i>
                                                                                Edit
                                                                            </button>
                                                                            <button class="action-edit delete_ques" id="delelte_quest_{{$key+1}}_{{$queskey+1}}" onclick="delete_question('{{$key+1}}', '{{$queskey+1}}')">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-10 question-data">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="namerinterviewfield">
                                                                                        <input type="hidden" class="question_id" value="{{($question->id)?$question->id:''}}" name="question_id[{{$key}}][]">
                                                                                        <input type="text" placeholder="Enter Question" class="question" value="{{($question->text)?$question->text:''}}" name="question[{{$key}}][]">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="variable-type">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label>Type</label>
                                                                                        <div class="yesnobutton">
                                                                                            <div class="row">
                                                                                                <div class="col-md-3">
                                                                                                    <select name="type[{{$key}}][{{$queskey}}]" id="question_type_{{$key+1}}_{{$queskey+1}}" class="question_type" onchange="showoptions('{{$key+1}}', '{{$queskey+1}}', this);">
                                                                                                        @foreach (App\Models\Template::getquestionTypeArray() as $type_key => $type_value)
                                                                                                        <option value="{{ $type_key }}" {{ $question->question_type == $type_key ? 'selected' : '' }}>{{ $type_value }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <div class="customcheckboxmain"><label class="customcheckbox"> <input type="checkbox" class="required_input" name="required[{{$key}}][{{$queskey}}]" value="1" {{ ($question->required == 1 ) ? 'checked' : '' }}>Required</label></div>
                                                                                                    <a onclick="add_option('{{$key+1}}', '{{$queskey+1}}');" class="add_option_{{$key+1}}_{{$queskey+1}} {{ $question->question_type == 6 || $question->question_type == 7 ? 'show-input' : 'hide-input' }}">+Add More Option</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    @if($question->question_type == 2)
                                                                                    <div id="template-dropdown-{{$key+1}}-{{$queskey+1}}" class="template-dropdown {{$question->question_type == 2 ? 'show-input' : 'hide-input' }}">
                                                                                        @include('partials.template-dropdown')
                                                                                      
                                                                                    </div>
                                                                                    @else
                                                                                    <div id="template-dropdown-{{$key+1}}-{{$queskey+1}}" class="template-dropdown" style="display: none">
                                                                                        @include('partials.template-dropdown')
                                                                                    </div>
                                                                                    <div class="option_section_{{$key+1}}_{{$queskey+1}} multiepleoption option_section col-md-12" {{$question->question_type == 6 || $question->question_type == 7 ? 'style=display:block' : 'style=display:none' }}>
                                                                                        @if ($question->question_type == 6 || $question->question_type == 7)
                                                                                        @if(isset($question->type_option) && count($question->type_option) > 0 && !empty($question->type_option))
                                                                                        @foreach ($question->type_option as $opt_key=>$options_array)
                                                                                        <div class="option multieoption option_{{$key+1}}_{{$queskey+1}}" id="option_{{$key+1}}_{{$queskey+1}}_{{$opt_key+1}}">
                                                                                            <div class="col-md-3">
                                                                                                @if($opt_key >=1)
                                                                                                <div class="option-box">
                                                                                                    @endif
                                                                                                    @if($opt_key >=1)
                                                                                                    <a id="remove_option_{{$opt_key+1}}" class="remove_option" onclick="remove_options('{{$key+1}}', '{{$queskey+1}}' ,'{{$opt_key+1}}')"><span class="glyphicon glyphicon-remove pull-right" alt="Remove"></span></a>&nbsp;
                                                                                                    @endif
                                                                                                    <input type="text" placeholder="Write your options" name="options[{{$key}}][{{$queskey}}][{{$opt_key}}]" id="options_ques_{{$key+1}}_{{$queskey+1}}_{{$opt_key+1}}" class="options_ques multieoption_input {{$opt_key == 0?'ques_option_first':'ques_option'}}" value="{{$options_array}}" {{$question->question_type == 6 || $question->question_type == 7 ? 'style=display:block' : 'style=display:none' }}>
                                                                                                    @if($opt_key >=1)
                                                                                                </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>

                                                                                        @endforeach
                                                                                        @else
                                                                                        <div class="option multieoption option_{{$key+1}}_{{$queskey+1}}" id="option_{{$key+1}}_{{$queskey+1}}_1">
                                                                                            <div class="col-md-3">
                                                                                                <div class="option-box">
                                                                                                    <input type="text" placeholder="Write your options" name="options[{{$key}}][{{$queskey}}][0]" id="options_ques_{{$key+1}}_{{$queskey+1}}_1" class="options_ques multieoption_input ques_option_first">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endif
                                                                                        @else
                                                                                        <div class="option multieoption option_{{$key+1}}_{{$queskey+1}}" id="option_{{$key+1}}_{{$queskey+1}}_1">
                                                                                            <div class="col-md-3">
                                                                                                <div class="option-box">
                                                                                                    <input type="text" placeholder="Write your options" name="options[{{$key}}][{{$queskey}}][0]" id="options_ques_{{$key+1}}_{{$queskey+1}}_1" class="options_ques multieoption_input ques_option_first">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="questionul">
                                                                                <ul>
                                                                                    <li>
                                                                                        <a class="add_guide" onclick="add_guide('{{$key+1}}', '{{$queskey+1}}')"><i class="fas fa-lightbulb"></i>Guide</a>
                                                                                    </li>
                                                                                    {{-- <li>
                                                                                            <span onclick="stop_submit()"><i class="fas fa-file-alt"></i>Document</span>
                                                                                        </li>
                                                                                        <li>
                                                                                            <span onclick="stop_submit()"><i class="fas fa-images"></i>Picture</span>
                                                                                        </li>
                                                                                        <li>
                                                                                            <span onclick="stop_submit()"><i class="fas fa-video"></i>Video</span>
                                                                                        </li>
                                                                                        <li>
                                                                                            <span onclick="stop_submit()"><i class="fas fa-volume-up"></i>Audio</span>
                                                                                        </li> --}}
                                                                                </ul>
                                                                                <input type="hidden" name="remove_file[]" id="remove_file">
                                                                                <?php
                                                                                $notes = "";
                                                                                $document = "";
                                                                                $document_id = [];
                                                                                $file_id = [];
                                                                                $library_ids = [];
                                                                                $notes_id = "";
                                                                                $library_name = [];

                                                                                foreach ($question->guides as $guide_key => $guides) {
                                                                                    // for attachment from device
                                                                                    if (!empty($guides->document_name)) {
                                                                                        $document = $guides->document_name;
                                                                                        // $file_id = $guides->id;
                                                                                        array_push($file_id, $guides->id);
                                                                                        $document_type = $guides->document_type;
                                                                                    }

                                                                                    // for document library
                                                                                    if (!empty($guides->document_id)) {
                                                                                        array_push($document_id, $guides->document_id);
                                                                                        array_push($library_ids, $guides->id);
                                                                                        array_push($library_name, $guides->documents->title);
                                                                                    }

                                                                                    //for notes
                                                                                    if (!empty($guides->notes)) {
                                                                                        $notes = $guides->notes;
                                                                                        $notes_id = $guides->id;
                                                                                    }
                                                                                    $file_ids=json_encode($file_id);
                                                                                }

                                                                                // print_r($library_name);die;
                                                                                ?>
                                                                                <div class="guide_container" id="guide_container_{{$key+1}}_{{$queskey+1}}" {{isset($question->guides[0]) || isset($question->guides[1]) || isset($question->guides[2]) ? 'style=display:block' : 'style=display:none' }}>
                                                                                    <div class="guide-upload" id="guide-upload-{{$key+1}}-{{$queskey+1}}">

                                                                                        <input id="guide-id-notes-{{$key+1}}-{{$queskey+1}}" type="hidden" name="notes_id[{{$key}}][]" value="{{isset($notes_id)?$notes_id:''}}" maxlenght="300" />
                                                                                        <input id="guide-id-document-{{$key+1}}-{{$queskey+1}}" type="hidden" name="files_id[{{$key}}][{{$queskey}}][]" value="{{isset($file_ids)?$file_ids:''}}" />


                                                                                        @foreach($library_ids as $library_id)
                                                                                        <input id="guide-id-library-{{$key+1}}-{{$queskey+1}}" type="hidden" name="library_id[{{$key}}][{{$queskey}}][]" value="{{isset($library_id)?$library_id:''}}" />
                                                                                        @endforeach

                                                                                        <div class="files_{{$key+1}}_{{$queskey+1}} dropzone">
                                                                                            <div class="dz-message">
                                                                                                <p class="">Attachment from Device</p>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-file" aria-hidden="true"></i>
                                                                                                    Document
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-picture-o" aria-hidden="true"></i>
                                                                                                    Image
                                                                                                </label>
                                                                                                <label class="">
                                                                                                    <i class="fas fa-play-circle" aria-hidden="true"></i>
                                                                                                    Video
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fa fa-volume-up" aria-hidden="true"></i>
                                                                                                    Audio
                                                                                                </label>

                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="thumbnail-images  thumbnail-images-{{$key+1}}-{{$queskey+1}}">

                                                                                        </div>

                                                                                    </div>

                                                                                    <div class="text-center">OR</div>
                                                                                    <div class="guide-library">
                                                                                        <label class="guide_title">Attachment from Document Library <i class="fa fa-paperclip" aria-hidden="true" onclick="document_library('{{$key}}', '{{$queskey}}','{{$question->id}}')"></i>
                                                                                            <span class="library-name library-name-{{$key+1}}-{{$queskey+1}}">{{!empty($library_name)?implode(",",$library_name):''}} </span></label>
                                                                                        <span class="glyphicon glyphicon-remove pull-right remove_doc_{{$key}}_{{$queskey}}" alt="Remove" {{ !empty($document_id) ? 'style=display:block' : 'style=display:none' }} onclick="cancel_doc('{{$key}}', '{{$queskey}}')">
                                                                                    </div>
                                                                                    <div class="guide-note">
                                                                                        <input type="text" name="notes[{{$key}}][]" class="guide_text" placeholder="Write note here" id="guide_text_{{$key+1}}_{{$queskey+1}}" value="{{!empty($notes)?$notes:''}}" maxlenght="300">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                @endforeach



                                                                @else
                                                                <div class="question_section_1" id="question_section_1_1"  data-questionid="question_section_1_1">
                                                                    <div class="row question-main-sec">
                                                                        <div class="col-md-1 question-action">
                                                                            <span class="question-serial">1</span>
                                                                            <button class="action-edit" onclick="stop_submit()">
                                                                                <i class="fas fa-pencil-alt"></i>
                                                                                Edit
                                                                            </button>
                                                                            <button class="action-edit delete_ques" id="delelte_quest_1_1" onclick="delete_question('1', '1')">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-md-10 question-data">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="namerinterviewfield">
                                                                                        <input type="text" placeholder="Enter Question" class="question" value="" name="question[0][]">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="variable-type">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label>Type</label>
                                                                                        <div class="yesnobutton">
                                                                                            <div class="row">
                                                                                                <div class="col-md-3">
                                                                                                    <select name="type[0][0]" id="question_type_1_1" class="question_type" onchange="showoptions('1','1', this);">
                                                                                                        @foreach (App\Models\Template::getquestionTypeArray() as $ques_type_key => $ques_type_value)
                                                                                                        <option value="{{ $ques_type_key }}" @if($ques_type_key==1) selected @endif>{{ $ques_type_value }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <div class="customcheckboxmain"><label class="customcheckbox"> <input type="checkbox" checked="checked" class="required_input" name="required[0][0]" value="1">Required</label></div>
                                                                                                    <a onclick="add_option('1', '1');" class="add_option_1_1 more-options" style="display:none;">+Add More Option</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="template-dropdown-1-1" style="display: none">
                                                                                        @include('partials.template-dropdown')
                                                                                    </div>
                                                                                    <div class="option_section_1_1 multiepleoption option_section" style="display:none;">
                                                                                        <div class="option_1_1 multieoption option  col-md-12" id="option_1_1_1">
                                                                                            <div class="col-md-3">
                                                                                                <div class="option-box">
                                                                                                    <input type="text" placeholder="Write your options" name="options[0][0][0]" id="options_ques_1_1_1" class="options_ques multieoption_input ques_option_first">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="questionul">
                                                                                <ul>
                                                                                    <li>
                                                                                        <a onclick="add_guide('1', '1')"><i class="fas fa-lightbulb"></i>Guide</a>
                                                                                    </li>
                                                                                    {{-- <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-file-alt"></i>Document</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-images"></i>Picture</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-video"></i>Video</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-volume-up"></i>Audio</span>
                                                                                    </li> --}}
                                                                                </ul>
                                                                            </div>
                                                                            <div class="guide_container" style="display:none" id="guide_container_1_1">
                                                                                <div class="guide-upload" id="guide-upload-1-1">
                                                                                        <div class="files_1_1 dropzone">
                                                                                            <div class="dz-message">
                                                                                                <p class="">Attachment from Device</p>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-file" aria-hidden="true"></i>
                                                                                                    Document
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-picture-o" aria-hidden="true"></i>
                                                                                                    Image
                                                                                                </label>
                                                                                                <label class="">
                                                                                                    <i class="fas fa-play-circle" aria-hidden="true"></i>
                                                                                                    Video
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fa fa-volume-up" aria-hidden="true"></i>
                                                                                                    Audio
                                                                                                </label>

                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="text-center">OR</div>
                                                                                <div class="guide-library">
                                                                                    <label class="guide_title">Attachment from Document Library <i class="fa fa-paperclip" aria-hidden="true" onclick="document_library('0', '0', '0')"></i>
                                                                                    </label>
                                                                                    <span class="library-name library-name-1-1"> </span>
                                                                                </div>
                                                                                <div class="guide-note">
                                                                                    <input type="text" name="notes[0][]" class="guide_text" placeholder="Write note here" id="guide_text_1_1" maxlenght="300">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </div>

                                                            @if (isset($sections->questions))
                                                            @foreach ($sections->questions as $queskey=>$question)

                                                            <!-- Document library list Modal -->
                                                            <div id="doc_library_{{$key}}_{{$queskey}}" class="modal doc_library">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <span class="doc_close" @if(!empty($document_id)) onclick="cancel_doc('{{$key}}', '{{$queskey}}', '1')" @else onclick="cancel_doc('{{$key}}', '{{$queskey}}')" @endif>&times;</span>
                                                                        <div class="doc_library_title">Document Library</div>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div style="height:auto;" class="doc_listing_{{$key}}_{{$queskey}}">
                                                                            @if(!empty($doc_listings) && count($doc_listings) > 0)
                                                                            <div class="pre_loader">
                                                                                <img src="{{asset('assets/images/loading.gif')}}" alt="">
                                                                            </div>
                                                                            <input type="text" class="search-doc search-doc-{{$key}}-{{$queskey}} form-control" placeholder="Search Document" onkeyup="search_document({{$key}},{{$queskey}}, this)">
                                                                            {{-- <input name="doc_library_id[{{$key}}][{{$queskey}}][]" id="document" class="document document_{{$key}}_{{$queskey}}" type="checkbox" value="{{$doc_listing['id']}}" target="" @if(!empty($doc_listing['id']) && !empty($document_id)){{ in_array($doc_listing['id'], $document_id)? 
                                                                                                "checked" : '' }}@endif onkeyup="search_document({{$key}},{{$queskey}}, this)" /> --}}

                                                                            <div style="height:auto;" class="col-md-12 doc_listing doc_listing_{{$key}}_{{$queskey}}">
                                                                                @include('partials.document_listing')
                                                                            </div>
                                                                            <div class="doclibrarybuttons">
                                                                                <input type="button" name="Save" value="Save" class="btn btn-success document_save" onclick="save_doc('{{$key}}', '{{$queskey}}')">
                                                                                <div class="upload-loader" style="display:none"></div>
                                                                                <input type="button" name="Cancel" value="Remove" class="btn btn-success" id="doc_cancel" onclick="cancel_doc('{{$key}}', '{{$queskey}}')">
                                                                            </div>

                                                                            @else
                                                                            <div class="no_doc"> No Document Found! </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endif
                                                            <div class="addquestion">

                                                                <button class="save_question" id="save_questions_{{$key+1}}" onclick="save_question('question_section_{{$key+1}}_{{$queskey+1}}');">
                                                                    <i class="fas fa-save"></i>
                                                                    Save & Next
                                                                </button>
                                                                <button class="addquestionbutton" id="addquestionbutton_{{$key+1}}" onclick="stop_submit()">Add Question <i class="fa fa-caret-down"></i></button>


                                                                <div class="addquestion-area" id="addquestion-area-{{$key+1}}">
                                                                    @foreach (App\Models\Template::getaddQuestionArray() as $add_key => $add_value)
                                                                    <div class="addquestion-sub addquestion_{{$add_key}}" id="addquestion_{{$add_key}}_{{$key+1}}" onclick="add_question('{{$add_key}}', '{{$key+1}}');">{{$add_value}}</div>
                                                                    @endforeach
                                                                </div>


                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            @else
                                            <div class="section_container" id="section_container_1">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <h5 class="mb-0">
                                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree" onclick="stop_submit()">
                                                                        <i class="fas fa-caret-down"></i>
                                                                        <i class="fas fa-caret-right"></i>
                                                                    </button>
                                                                    <span class="section" contenteditable="false" id="section_title_1" onkeypress="section_value('1')" onkeyup="section_value('1')"> Section 1 </span>
                                                                    <input type="text" name="section[]" value="" id="section_1" class="section_name" style="display:none">

                                                                    <span><i class="fas fa-pencil-alt edit_section" onclick="edit_section_title('1')"></i></span>
                                                                    <div class="dropdown more-btn" style="position: static;">
                                                                        <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> More</button>
                                                                        <div class="dropdown-menu " aria-labelledby="dropdownMenu2">
                                                                            <!-- <a class="dropdown-item deletelist" id="deletelist_1">Delete</a> -->
                                                                            <a class="dropdown-item add_section" onclick="add_section()">Add Section</a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dropdown more-btn">
                                                                        <button class="btn btn-secondary dropdown-toggle scrorebtn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                            Score
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item"> <input type="checkbox" name="score[0][]" id="score1" value="1">Score</a>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="collapseThree" class="collapse" aria-labelledby="collapseThree" data-parent="#accordion">
                                                        <div class="card-body">
                                                            <div class="question_container" id="question_container_1">
                                                                <div class="question_section_1" id="question_section_1_1" data-questionid="question_section_1_1">
                                                                    <div class="row question-main-sec">
                                                                        <div class="col-md-1 question-action">
                                                                            <span class="question-serial">1</span>
                                                                            <button class="action-edit" onclick="stop_submit()">
                                                                                <i class="fas fa-pencil-alt"></i>
                                                                                Edit
                                                                            </button>
                                                                            <button class="action-edit delete_ques" id="delelte_quest_1_1" onclick="delete_question('1', '1')">
                                                                                <i class="fas fa-trash"></i>
                                                                                Delete
                                                                            </button>

                                                                        </div>
                                                                        <div class="col-md-10 question-data">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <div class="namerinterviewfield">
                                                                                        <input type="text" placeholder="Enter Question" class="question" value="" name="question[0][]">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="variable-type">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label>Type</label>
                                                                                        <div class="yesnobutton">
                                                                                            <div class="row">
                                                                                                <div class="col-md-3">
                                                                                                    <select name="type[0][0]" id="question_type_1_1" class="question_type" onchange="showoptions('1','1', this);">
                                                                                                        @foreach (App\Models\Template::getquestionTypeArray() as $ques_type_key => $ques_type_value)
                                                                                                        <option value="{{ $ques_type_key }}" @if($ques_type_key==1) selected @endif>{{ $ques_type_value }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <div class="customcheckboxmain"><label class="customcheckbox"> <input type="checkbox" checked="checked" class="required_input" name="required[0][0]" value="1">Required</label></div>
                                                                                                    <a onclick="add_option('1', '1');" class="add_option_1_1 more-options" style="display:none;">+Add More Option</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="template-dropdown-1-1" style="display: none">
                                                                                        @include('partials.template-dropdown')
                                                                                    </div>
                                                                                    <div class="option_section_1_1 multiepleoption option_section" style="display:none;">
                                                                                        <div class="option_1_1 multieoption option  col-md-12" id="option_1_1_1">
                                                                                            <div class="col-md-3">
                                                                                                <div class="option-box">
                                                                                                    <input type="text" placeholder="Write your options" name="options[0][0][0]" id="options_ques_1_1_1" class="options_ques multieoption_input  ques_option_first">
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="questionul">
                                                                                <ul>
                                                                                    <li>
                                                                                        <a onclick="add_guide('1', '1')"><i class="fas fa-lightbulb"></i>Guide</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-file-alt"></i>Document</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-images"></i>Picture</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-video"></i>Video</span>
                                                                                    </li>
                                                                                    <li>
                                                                                        <span onclick="stop_submit()"><i class="fas fa-volume-up"></i>Audio</span>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="guide_container" style="display:none" id="guide_container_1_1">
                                                                                <div class="guide-upload" id="guide-upload-1-1">

                                                                                <div class="files_1_1 dropzone">
                                                                                            <div class="dz-message">
                                                                                                <p class="">Attachment from Device</p>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-file" aria-hidden="true"></i>
                                                                                                    Document
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fas fa-picture-o" aria-hidden="true"></i>
                                                                                                    Image
                                                                                                </label>
                                                                                                <label class="">
                                                                                                    <i class="fas fa-play-circle" aria-hidden="true"></i>
                                                                                                    Video
                                                                                                </label>

                                                                                                <label class="">
                                                                                                    <i class="fa fa-volume-up" aria-hidden="true"></i>
                                                                                                    Audio
                                                                                                </label>

                                                                                            </div>
                                                                                        </div>
                                                                                </div>
                                                                                <div class="thumbnail-images  thumbnail-images-1-1"></div>
                                                                                <div class="text-center">OR</div>
                                                                                <div class="guide-library">
                                                                                    <label class="guide_title">Attachment from Document Library <i class="fa fa-paperclip" aria-hidden="true" onclick="document_library('0', '0', '0')"></i>
                                                                                    </label>
                                                                                    <span class="library-name library-name-1-1"> </span>
                                                                                </div>
                                                                                <div class="guide-note">
                                                                                    <input type="text" name="notes[0][]" class="guide_text" placeholder="Write note here" id="guide_text_1_1" maxlenght="300">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="addquestion">

                                                            <button class="save_question" id="save_questions_1" onclick="save_question('question_section_1_1');">
                                                                    <i class="fas fa-save"></i>
                                                                    Save & Next
                                                                </button>
                                                                <button class="addquestionbutton" id="addquestionbutton_1" onclick="stop_submit()">Add Question <i class="fa fa-caret-down"></i></button>


                                                                <div class="addquestion-area" id="addquestion-area-1">
                                                                    @foreach (App\Models\Template::getaddQuestionArray() as $add_key => $add_value)
                                                                    <div class="addquestion-sub addquestion_{{$add_key}}" id="addquestion_{{$add_key}}_1" onclick="add_question('{{$add_key}}', '1');">{{$add_value}}</div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="editlogic">
                                <div class="uploaddocadin">
                                    <a href="{{ route('templates') }}">
                                        <input type="button" class="cancel-btn" id="close_button" value="Close">
                                    </a>
                                    <input type="hidden" name="saved_data" id="saved_data" value='<?php echo json_encode($saved_sectionss,true); ?>'>
                                    <?php if ($tempdetail->published == 0) { ?>
                                        <input type="button" name="save" class="blue-btn save_template" id="save_template" value="Save">
                                    <?php } ?>
                                    <input type="submit" name="publish" class="blue-btn publish_template" id="publish_template" value="Publish">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($doc_listings))
            @php $document_list = ''; @endphp
            @foreach($doc_listings as $doc_listing)
            <?php $document_list .= '<li class="col-md-6"><label for="document"  style="word-wrap:break-word"><input name="" id="document" class="document"  type="checkbox"  target="" value="' . $doc_listing['id'] . '" />' . $doc_listing['title'] . '</label></li>'; ?>
            @endforeach
            @endif

            <input type="hidden" name="doc_listing" id="doc_listing" value='<?= $document_list; ?>'>
            {{ Form::close() }}
        </div>
    </div>




    <div style="display: none;">

        <div id="template-preview">
            <div class="dz-preview dz-file-preview well" id="dz-preview-template">
                <div class="dz-preview dz-file-preview">
                    <div class="dz-image">
                        <img data-dz-thumbnail />
                    </div>
                    <div class="dz-progress">
                        <span class="dz-upload" data-dz-uploadprogress></span>
                    </div>
                    <div class="dz-success-mark">
                        <span></span>
                    </div>
                    <div class="dz-error-mark">
                        <span></span>
                    </div>

                    <div class="dz-error-message">
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    </div>
                </div>
                <div style="margin: 10px auto;" class="fileinput fileinput_1_1">

                </div>
            </div>
        </div>


    </div>



    @endsection

    @section('footer_scripts')

    <script type="text/javascript">
        $('#publish_template, #save_template').click(function() {
            if ($('#edit_template').valid()) {
                $('#edit_template input').removeClass('temp_change');
                $('#edit_template select').removeClass('temp_change');
                $('#edit_template textarea').removeClass('temp_change');
            }
        });


        // add class if any input is changed 
        $('#edit_template input, #edit_template select, #edit_template textarea').on('keyup change', function() {
            $(this).addClass('temp_change');
        });

        // alert before leaving create template form
        $(window).on('beforeunload', function() {
            if ($('#edit_template input').hasClass('temp_change') || $('#edit_template select').hasClass('temp_change') || $('#edit_template textarea').hasClass('temp_change')) {
                var c = confirm();
                if (c) {
                    return true;
                } else
                    return false;
            }
        });
    </script>

    <script>
        $("#close_button").click(function() {
            var form = $("#edit_template");
            form.validate().resetForm(); // clear out the validation errors
            form[0].reset();
            $('#close_button').attr('formnovalidate', 'formnovalidate');
        });
       var temp_id='<?=$tempdetail->id?>'
    </script>

    {{-- <script src="{{asset('assets/template/js/jquery.min.js ')}}" type="text/javascript "></script> --}}
    {{-- <script src="{{asset('assets/template/js/fontawesome.min.js ')}}" type="text/javascript "></script> --}}
    {{-- <script src="{{asset('assets/template/js/bootstrap.min.js ')}}" type="text/javascript "></script> --}}
  
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script src="{{asset('assets/template/js/jquery.main.js ')}}" type="text/javascript "></script>
    <script src="{{asset('assets/js/template_update.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>

    <script>


    <?php
    if(isset($tempdetail->sections)){
    
        foreach ($tempdetail->sections as $key=>$sections)
        {
            if (isset($sections->questions)){
                foreach ($sections->questions as $queskey=>$question)
                {
                    echo 'appendfileinput('.$key.','.$queskey.');';
                  
                    
                    echo 'createdrozone('.($key+1).' , '.($queskey+1).');';


                    echo 'reorderdQuestion('.($key+1).' , '.($queskey+1).');';
                }
            }
        } 
    }
    ?>
    </script>



    @endsection