@extends('admin.layout.app')
@section('title','Create Template')
@section('header_css')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">

<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">

<style>
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
/* .dropzone .dz-preview .dz-image {
    position: static;
} */
.dropzone .dz-preview .dz-image{
    position: none !important;
}
input.multieoption_input.options_ques.ques_option {
    margin-top: 0px;
}

span.glyphicon.glyphicon-remove.pull-right {
    position: absolute;
    right: 0;
    top: 6px;
}
</style>
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
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
                    <span>{{ trans('label.create_template') }} </span>
                </li>
            </ul>
        </div>

        {{ Form::open(array('action' => 'TemplateController@saveTemplate', 'method' => 'post', 'id' => 'create_template', 'enctype' => 'multipart/form-data'))}}
        <div class="pre_loader">
            <img src="{{asset('assets/images/loading.gif')}}" alt="">
        </div>
        <div class="row">
            <div class="wrapper create-temp-wrap">
                <div class="">
                    <div class="page-wrap edit_form_accordion">
                        <div class="adinfull">
                            <div class="addinname">
                                <h1>{{ trans('label.create_template') }} </h1>
                            </div>
                            <div class="editlogic">
                                <div class="editlogicheading">
                                </div>
                                <div class="variable-type-sub">
                                    <input type="text" name="template_name" class="template_name" placeholder="Template Name" maxlength="30">
                                </div>
                                <div class="variable-type-sub">
                                    <input type="text" name="template_prefix" class="template_prefix" placeholder="Template prefix" maxlength="30">
                                </div>
                                <div class="variable-type-sub">

                                    <select name="color_pin" class="color_pin" id="color_pin" name="color_pin">
                                        <option value="">Set color pin</option>
                                        @foreach (App\Models\Template::getpinColorArray() as $key => $value)
                                        <option value="{{ $value }}" class="{{$key}}-pin">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="uploaddocadin">
                                    <a href="{{ route('templates') }}">
                                        <input type="button" class="cancel-btn" id="close_button" value="Close">
                                    </a>
                                    <input type="button" name="save" class="blue-btn save_template" id="save_template" value="Save">
                                    <input type="submit" name="publish" class="blue-btn publish_template" id="publish_template" value="Publish">
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
                                                        <div class="errorTxt"> </div>
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" onclick="stop_submit()">
                                                                <i class="fas fa-caret-down"></i>
                                                                <i class="fas fa-caret-right"></i>
                                                            </button>
                                                            <span class="scope_methodology" contenteditable="false">Scope and Methodology</span>
                                                            <input id="scope_methodology_input" type="hidden" name="scope_methodology" value="">
                                                            <span><i class="fas fa-pencil-alt" id="edit_scope"></i></span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseTwo" class="collapse scope_meth" aria-labelledby="collapseTwo" data-parent="#accordion">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="filterwidth scope_container">
                                                            <div class="scope" id="scope_1">
                                                                <textarea placeholder="Type your text here" id="scopetxt_1" name="snm_data[]" maxlength="200" class="scopetxt"></textarea>
                                                            </div>
                                                            <a class="scopeadd" id="add_more_scope">+ Add more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- </div> -->
                                        </div>

                                        <div class="section_contain">
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
                                                                    <input type="hidden" name="section[]" value="" id="section_1" class="section_name">
                                                                    <span><i class="fas fa-pencil-alt edit_section" onclick="edit_section_title('1')"></i></span>
                                                                    <div class="dropdown more-btn section-dropdown">
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
                                                                            <a class="dropdown-item"> <input type="checkbox" name="score[]" id="score1" value="1">Score</a>
                                                                        </div>
                                                                    </div>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="collapseThree" class="collapse" aria-labelledby="collapseThree" data-parent="#accordion">
                                                        <div class="card-body">
                                                            <div class="question_container" id="question_container_1">
                                                                <div class="question_section_1" id="question_section_1_1" data-questionId="question_section_1_1">
                                                                    <div class="row  question-main-sec">
                                                                        <div class="col-md-1  question-action">
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
                                                                        <div class="col-md-10  question-data">
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
                                                                                                        @foreach (App\Models\Template::getquestionTypeArray() as $key => $value)
                                                                                                        <option value="{{ $key }}" @if($key==1) selected @endif>{{ $value }}</option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="col-md-3">
                                                                                                    <div class="customcheckboxmain"><label class="customcheckbox"> <input class="required_input" type="checkbox" checked="checked" name="required[0][0]" value="1">Required</label></div>
                                                                                                    <a onclick="add_option('1', '1');" class="add_option_1_1 more-options">+Add More Options</a>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="template-dropdown-1-1" class="template-dropdown" style="display: none">
                                                                                    </div>
                                                                                    <div class="option_section_1_1 multiepleoption option_section">
                                                                                        <div class="option  multieoption    option_1_1" id="option_1_1_1">
                                                                                            <div class="col-md-3">
                                                                                                <input type="text" placeholder="Write your options" name="options[0][0][0]" id="options_ques_1_1_1" class="options_ques multieoption_input ques_option_first">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="questionul">
                                                                                <ul>
                                                                                    <li>
                                                                                        <a class="add_guide" onclick="add_guide('1', '1')"><i class="fas fa-lightbulb"></i>Guide</a>
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
                                                                                <input type="hidden" id="has_permission" value='@if(auth()->user()->userHasFormPermission("Document Library") ||  auth()->user()->user_type == ' company')1 @else 0 @endif'>
                                                                                <div class="guide_container" id="guide_container_1_1">
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

                                                                                    <div class="thumbnail-images  thumbnail-images-1-1">
                                                                                    </div>

                                                                                    <div class="errorTxt"></div>
                                                                                    <div class="text-center">OR</div>
                                                                                    <div class="guide-library">
                                                                                        <label class="guide_title">Attachment from Document Library <i class="fa fa-paperclip" aria-hidden="true" id="document_library" onclick="document_library('0', '0', '0')"></i>
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
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="editlogic">
                                <div class="uploaddocadin">
                                    <a href="{{ route('templates') }}">
                                        <input type="button" class="cancel-btn" id="close_button" value="Close">
                                    </a>
                                    <input type="hidden" name="publish" class="blue-btn " id="publish_value" value="">
                                    <input type="hidden" name="template_id" id="template_id" value="">



                                    <input type="hidden" name="saved_data" id="saved_data">
                                    <input type="hidden" name="deletesection" id="deletesection">
                                    <input type="button" name="save" class="blue-btn save_template" id="save_template" value="Save">
                                    <input type="submit" class="blue-btn publish_template" id="publish_template" value="Publish">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.template.document_library')
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
            // dropz.processQueue();
            if ($('#create_template').valid()) {
                //     e.preventDefault();
                //  e.stopPropagation();
                //  dropz.processQueue();
                $('#create_template input').removeClass('temp_change');
                $('#create_template select').removeClass('temp_change');
                $('#create_template textarea').removeClass('temp_change');
            }
        });

        // add class if any input is changed 
        $('#create_template input, #create_template select, #create_template textarea').on('keyup change', function() {
            // $(this).addClass('temp_change');
            if ($(this).val() != '') {
                $(this).addClass('temp_change');
            } else {
                $(this).removeClass('temp_change');
            }
        });

        // alert before leaving create template form
        $(window).on('beforeunload', function() {
            if ($('#create_template input').hasClass('temp_change') || $('#create_template select').hasClass('temp_change') || $('#create_template textarea').hasClass('temp_change')) {
                var c = confirm();
                if (c) {
                    return true;
                } else
                    return false;
            }
        });
    </script>

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <script src="{{asset('assets/template/js/jquery.main.js ')}}" type="text/javascript "></script>
    <script src="{{asset('assets/js/template.js')}}"></script>

    <script>
        $("#close_button").click(function() {
            var form = $("#create_template");
            form.validate().resetForm(); // clear out the validation errors
            form[0].reset();
            $('#close_button').attr('formnovalidate', 'formnovalidate');
        });
    </script>
    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>


    @endsection