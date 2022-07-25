@extends('admin.layout.app')
@section('title','Edit Form')
@section('header_css')
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/dropzone.min.css') }}" />
<style>
    .pre_loader{
        left: 50%;
    }

    .main-card{ 
        width:100%;
        float:left; 
    }
    .main-card-body {
        width: 100%;
        float: left;
    }
</style>
@endsection

@section('content')
<div class="pre_loader" id="pre_loader">
    <img src="{{ asset('assets/images/loading.gif') }}" alt="loader">
</div>
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <a href="{{ route('completed_forms') }}">{{ trans('Completed Forms') }}</a>
                    <i class="fa fa-angle-right"></i>
                </li>
                <li>
                    <span>Edit </span>
                </li>
            </ul>
        </div>
        <!-- response messages -->
        @include ('partials.messages')
        {{ Form::open(array('action' => 'CompletedFormController@update', 'method' => 'post', 'id' => 'create_template', 'class' => 'edit_form'))}}
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-user font-dark"></i>
                            <span class="caption-subject bold uppercase"> Edit </span>
                        </div>
                    </div>
                    <div class="editlogic">
                        <div class="variable-type-sub">
                            <input type="text" placeholder="Template Name"
                                value="{{!empty($detail->Template->template_name)?$detail->Template->template_name:''}}"
                                disabled="true">
                        </div>
                        <div class="variable-type-sub">
                            <input type="text" placeholder="completed forms id" value="{{$detail->form_id}}"
                                disabled="true">
                        </div>
                        <div class="uploaddocadin">
                            <a href="{{route('completed_forms')}}"><input type="button" class="cancel-btn close_button"
                                    value="Close"></a>
                            <input type="hidden" id="form_id" name="id" value="{{ $detail->id }}">
                            <input type="submit" class="blue-btn " value="Update" id="save_form">
                            <button type="button" onclick="window.location.href = '{{ route('report', $detail->id_decrypted) }}'";  class="yellow-btn">Report</button>

                        </div>
                    </div>
                    <div class="row">
                    </div>
                    <div class="datadocumentcollaspe">
                        <div class="collaspeinner">
                            <div id="accordion">
                                <div class="card main-card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapseOne" aria-expanded="true"
                                                        aria-controls="collapseOne" onclick="stop_submit()">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        Overview
                                                    </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapseOne" class="collapse in ovrviwe" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <input type="text" placeholder="Title"
                                                            class="title" id="title"
                                                            value="{{ $detail->title }}" disabled="ture">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    @php $compnyProfilePic = $compnyProfilePic @endphp
                                                    @if(!empty($compnyProfilePic))
                                                      @php  $name = 'http://p2bqa.debutinfotech.com/uploads/company_logos/'.$compnyProfilePic; @endphp
                                                      <div class="userimg"><img src= {{$name}}></div>
                                                    @else
                                                      <div class="userimg"><img src="{{ public_path('assets/edit_form/images/defaultpic.jpeg')}}"></div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <input type="text" placeholder="Company Name"
                                                            class="company_name" id="company_name"
                                                            value="{{ $detail->company_name }}" disabled="ture">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <input type="text" placeholder="Selected Date"
                                                            class="select_date" id="select_date"
                                                            value="{{ date('m/d/Y', strtotime($detail->created_at)) }}"
                                                            disabled="ture">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <input type="text" placeholder="Completed by"
                                                            name="completed_by" class="completed_by" id="completed_by"
                                                            value="{{ $detail->user_name }}" disabled="ture">

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <input type="text" placeholder="Location" class="location_id"
                                                            id="location_id" value="{{ $detail->location_name }}"
                                                            disabled="ture">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <select name="business_unit_name" class="business_unit_name" id="business_unit" @if($detail->user_id != Auth::id()) disabled="disabled" readonly @endif >
                                                            <option value=""> --Business unit-- </option>
                                                            @if(count($business_units) && !is_null($business_units))
                                                            @foreach ($business_units as $option)
                                                            <option value="{{$option->id}}" @if($detail->
                                                                business_unit_id==$option->id) selected
                                                                @endif>{{$option->vc_short_name}}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <select name="department_name" class="department_name" id="department" @if($detail->user_id != Auth::id()) disabled="disabled" readonly @endif>
                                                            <option value="">--Department--</option>
                                                            @if(!is_null($detail->business))
                                                            @if(!is_null($detail->business->business_dept) && count($detail->business->business_dept) > 0)
                                                            @foreach ($detail->business->business_dept as $option)
                                                            <option value="{{$option->dept_data->id}}" @if($detail->
                                                                department_id==$option->dept_data->id) selected
                                                                @endif>{{$option->dept_data->vc_name}}</option>
                                                            @endforeach
                                                            @endif
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="filterwidth">
                                                        <select name="project_name" class="project" id="project" @if($detail->user_id != Auth::id()) disabled="disabled"  readonly @endif>
                                                            <option value="">--Project--</option>
                                                            @if(!is_null($detail->business))
                                                            @if(!is_null($detail->business->projects) && count($detail->business->projects) > 0)
                                                            @foreach ($detail->business->projects as $option)
                                                            <option value="{{$option->id}}" @if($detail->
                                                                project_id==$option->id) selected
                                                                @endif>{{$option->vc_name}}</option>
                                                            @endforeach
                                                            @endif
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse"
                                                        data-target="#collapseTwo" aria-expanded="true"
                                                        aria-controls="collapseTwo" onclick="stop_submit()">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="scope_methodolgy" contenteditable="false">Scope and
                                                            Methodology</span>
                                                    </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="collapse scope_meth" aria-labelledby="collapseTwo"
                                        data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                            @forelse($detail->scopeMethodology as $scope)
                                            @if($scope->type == 2)
                                            <div class="row">
                                                <div class="filterwidth scope_container">
                                                    <div class="scope edit-form-scope" id="scope_1">
                                                        <textarea placeholder="Scope and Methodology will be here..."
                                                            disabled="true">{{$scope->snm_data}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @empty
                                            <div class="row">
                                                <div class="filterwidth scope_container">
                                                    <p>Not added yet.</p>
                                                </div>
                                            </div>
                                            @endforelse

                                        </div>
                                    </div>
                                </div>

                                <div class="section_contain">
                                    @forelse ($detail->sections as $sections)
                                    @if($sections->type == 2)
                                    <div class="section_container" id="section_container_1">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed"
                                                                data-toggle="collapse"
                                                                data-target="#collapse_{{$sections->id}}"
                                                                aria-expanded="true"
                                                                aria-controls="collapse_{{$sections->id}}"
                                                                onclick="stop_submit()">
                                                                <i class="fas fa-caret-down"></i>
                                                                <i class="fas fa-caret-right"></i>
                                                                <span class="section" contenteditable="false"
                                                                    id="section_title_1"> {{ $sections->name }} 
                                                                </span>
                                                            </button>
                                                        </h5>
                                                    </div>
                                                    <div class="col-lg-2  text-center">
                                                        <b>Marks</b>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="collapse_{{$sections->id}}" class="collapse"
                                                aria-labelledby="collapse_{{$sections->id}}" data-parent="#accordion">
                                                @if($sections->questions)
                                                @foreach ($sections->questions as $quest_key => $question)
                                                <?php
                                                $checkPer = false;
                                                if(auth()->check() && auth()->user()->user_type == 'supplier'){
                                                    $checkPer = \App\Models\Action::checkQuestionPermission($detail->id, $question->id, $sections->id, Auth::id());
                                                } else if(auth()->check() && auth()->user()->user_type == 'employee'){
                                                    $checkPer = \App\Models\Action::checkQuestionPermission($detail->id, $question->id, $sections->id, Auth::id());
                                                }
                                                ?>
                                                <div class="card-body main-card-body">
                                                    <div class="question_container" id="question_container_1">
                                                        <div class="question_section_1" id="question_section_1_1">
                                                            <div class="question-main-sec">
                                                                <div class="question-action-view">
                                                                    <span class="question-serial">{{ $no++ }}</span>
                                                                </div>
                                                                <div class="question-data-view">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <div class="question">
                                                                                <p>{{$question->text}}</p>
                                                                                @if ($checkPer && auth()->user()->user_type == 'supplier')
                                                                                <a href="{{ route('form.chat', [$detail->id_decrypted, $question->id_decrypted]) }}">
                                                                                    <i class="fas fa-comment-dots"></i>
                                                                                </a>
                                                                                @else 
                                                                                <i class="fas fa-comment-dots disabled-dots-icon"></i>
                                                                                @endif
                                                                            </div>
                                                                            <!--- answers ---->
                                                                            @include('partials.answers')
                                                                            <!--- end answers ---->
                                                                            @if ($checkPer && auth()->user()->user_type == 'supplier')
                                                                            <div class="questionul">
                                                                                <button type="button" 
                                                                                    class="btn btn-primary model-evidence" 
                                                                                    data-toggle="modal" 
                                                                                    data-target="#model-evidence-content"
                                                                                    data-section-id="{{ $sections->id }}"
                                                                                    data-question-id="{{ $question->id }}"
                                                                                    data-answer-id="{{ $question->answers->id }}"
                                                                                    >
                                                                                    Add evidences
                                                                                </button>
                                                                            </div>
                                                                            @endif
                                                                            <!--- evidences ---->
                                                                            @if(!empty($question->answers))
                                                                                <div class="evidence evidence-{{ $question->answers->id }}">
                                                                                @if ($checkPer || auth()->user()->user_type != 'supplier')
                                                                                    @if($question->answers->has('evidences') && count($question->answers->evidences) > 0)
                                                                                    <?php $evidencesRows = $question->answers->evidences; ?>
                                                                                        @include('partials.evidences')
                                                                                    @endif
                                                                                @endif
                                                                                </div>
                                                                            @endif
                                                                            <!--- end evidences ---->
                                                                        </div>
                                                                    </div>
                                                                    @if (auth()->user()->user_type == 'company')
                                                                    <div class="question-comment">
                                                                        <div class="row">
                                                                            @if($question->comments)
                                                                            <div class="col-sm-10">
                                                                                <hr>
                                                                                <input type="text"
                                                                                    value="{{ $question->comments->comment }}"
                                                                                    disabled="true" class="danger">
                                                                            </div>
                                                                            @else
                                                                            <div class="col-sm-12">
                                                                                <hr>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="comment" value="" placeholder="write comment here..">
                                                                                </div>
                                                                            
                                                                                <div class="col-sm-2">
                                                                                    <button class="save_comments btn btn-info"
                                                                                        data-type="1"
                                                                                        data-template="{{!empty($detail->Template->id)?$detail->Template->id:''}}"
                                                                                        data-section="{{$sections->id}}"
                                                                                        data-question="{{$question->id}}"
                                                                                        data-answer="{{!empty($question->answers[$quest_key]->id)?$question->answers[$quest_key]->id:''}}">Save
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    <div class="question-notes">
                                                                        <div class="row">
                                                                            @if(count($question->guides))
                                                                            <div class="col-md-12">
                                                                                <hr>
                                                                                @foreach ($question->guides as $guide)
                                                                                <div class="col-md-4">
                                                                                    <p><b>{{$guide->notes}}</b></p>
                                                                                    <!--documents file -->
                                                                                    @if($guide->documents)
                                                                                    @if($guide->documents->file_type ==1)
                                                                                    <img src="{{url('/documentLibrary/'.$guide->documents->file_name.'')}}"
                                                                                        class="img-responsive "
                                                                                        alt="{{$guide->documents->title}}" />

                                                                                    @endif
                                                                                    <p> <b>{{$guide->documents->description}}</b>
                                                                                    </p>
                                                                                    @endif
                                                                                </div>
                                                                                @endforeach
                                                                            </div>


                                                                            @if ($checkPer || auth()->user()->user_type != 'supplier')
                                                                            <!--guide media -->
                                                                            <div class="col-md-12">
                                                                                <hr>
                                                                                @foreach ($question->guides as $guide)
                                                                                @if($guide->document_name)
                                                                                <div class="col-md-4">
                                                                                    <div class="Uploads">
                                                                                        <?php $ext = pathinfo($guide->document_name, PATHINFO_EXTENSION); ?>
                                                                                        @if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
                                                                                        <img src="{{url('/uploads/'.$guide->document_name.'')}}"
                                                                                            class="img-responsive " />
                                                                                        @elseif($ext == 'mp4' || $ext == 'webm')
                                                                                        <video width="260" controls>
                                                                                            <source
                                                                                                src="{{url('/uploads/'.$guide->document_name.'')}}"
                                                                                                type="video/mp4">
                                                                                        </video>
                                                                                        @elseif($ext == 'pdf')
                                                                                        <iframe src="{{url('/uploads/'.$guide->document_name.'')}}"
                                                                                            width="260"></iframe>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                @endif
                                                                                @if(isset($guide->documents) && !empty($guide->documents))
                                                                                 @php $guide_doc = $guide->documents; @endphp
                                                                                    @if($guide_doc->file_name)
                                                                                    <div class="col-md-4">
                                                                                        <div class="Uploads">
                                                                                            <?php $doc_ext = pathinfo($guide_doc->file_name, PATHINFO_EXTENSION); ?>
                                                                                            @if($doc_ext == 'png' || $doc_ext == 'jpg' || $doc_ext == 'jpeg')
                                                                                            <img src="{{url('/documentLibrary/'.$guide_doc->file_name.'')}}"
                                                                                                class="img-responsive " />
                                                                                            @elseif($doc_ext == 'mp4' || $doc_ext == 'webm')
                                                                                            <video width="260" controls>
                                                                                                <source
                                                                                                    src="{{url('/documentLibrary/'.$guide_doc->file_name.'')}}"
                                                                                                    type="video/mp4">
                                                                                            </video>
                                                                                            @elseif($doc_ext == 'pdf')
                                                                                            <iframe src="{{url('/documentLibrary/'.$guide_doc->file_name.'')}}"
                                                                                                width="260"></iframe>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                @endif
                                                                                @endforeach
                                                                            </div>
                                                                            @endif
                                                                            @endif

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="question-action-view">
                                                                    <span class="question-score">
                                                                        @if(in_array($question->question_type,array(config('constants.question_type.text'),config('constants.question_type.dropdown'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.multi_choice'),config('constants.question_type.multi_select'),config('constants.question_type.signature'),config('constants.question_type.location'))))
                                                                        @if((!empty($question->answers->answer)))
                                                                        <input type="checkbox" disabled="true" checked>
                                                                        @else
                                                                        <input type="checkbox" disabled="true">
                                                                        @endif
                                                                        @elseif($question->question_type ==
                                                                        config('constants.question_type.two_option') &&
                                                                        ($question->answers->answer ==1))
                                                                        1
                                                                        @elseif(in_array($question->question_type,array(config('constants.question_type.multi_choice'),config('constants.question_type.multi_select')))
                                                                        && (!empty($question->answers->type_option)))
                                                                        <input type="checkbox" disabled="true" checked>
                                                                        @else
                                                                        <input type="checkbox" disabled="true">
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    @endif
                                    @empty
                                    <div class="section_container" id="section_container_1">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <h5 class="mb-0">
                                                            <button class="btn btn-link collapsed"
                                                                data-toggle="collapse" data-target="#collapse_na"
                                                                aria-expanded="true"
                                                                aria-controls="collapse_collapse_na"
                                                                onclick="stop_submit()">
                                                                <i class="fas fa-caret-down"></i>
                                                                <i class="fas fa-caret-right"></i>
                                                                <span class="section" contenteditable="false"
                                                                    id="section_title_na"> Section </span>
                                                            </button>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapse_na" class="collapse" aria-labelledby="collapse_na"
                                                data-parent="#accordion">
                                                <div class="card-body main-card-body">
                                                    <div class="question_container" id="question_container_1">
                                                        <div class="question_section_1" id="question_section_1_1">
                                                            <div class="question-main-sec">
                                                                No Section added yet.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

@include('partials.model-evidence')
@endsection

@section('footer_scripts')
<script type="text/javascript">

    $('#save_form' ).click(function(){
        $('#create_template input').removeClass('form_change');
        $('#create_template select').removeClass('form_change');
        $('#create_template textarea').removeClass('form_change');
    });
    
    // add class if any input is changed 
    $('#create_template input, #create_template select, #create_template textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    // alert before leaving create template form
    $(window).on('beforeunload', function(){
        if($('#create_template input').hasClass('form_change') || $('#create_template select').hasClass('form_change') 
        || $('#create_template textarea').hasClass('form_change') ){
            var c=confirm();
            if(c){
            return true;
            }
            else
            return false;
        }
    });
        
</script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>
{{-- <script src="{{asset('assets/template/js/fontawesome.min.js ')}}" type="text/javascript "></script> --}}
<script src="{{asset('assets/template/js/jquery.main.js ')}}" type="text/javascript "></script>
<script src="{{asset('assets/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/js/template.js')}}"></script>
<script src="{{asset('assets/js/dropzone.min.js')}}"></script>
<script src="{{asset('assets/js/completed_from.js')}}"></script>
@endsection
