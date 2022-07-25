<?php //pr($detail);die('====='); ?>
@extends('admin.layout.app')
@section('title','Create Template')
@section('header_css')
    <link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
    <style>

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
                  <span>View </span>
                </li>
            </ul>
        </div>
        <div class="row">
        <div class="wrapper" style="padding:12px;">
        <div class="">
            <div class="page-wrap edit_form_accordion">
                <div class="adinfull">
                    <div class="addinname">
                        <h1>Completed Forms View</h1>
                    </div>
                    <div class="editlogic">
                        <div class="variable-type-sub">
                            <input type="text" placeholder="Template Name" value="{{isset($detail->Template)?$detail->Template->template_name:''}}" disabled="true">
                        </div>
                        <div class="variable-type-sub">
                            <input type="text" placeholder="completed forms id" value="{{$detail->form_id}}" disabled="true">
                        </div>
                        <div class="uploaddocadin">
                            <a href="{{route('completed_forms')}}"><button class="cancel-btn">Close</button></a>
                            <a href="{{route('report', $detail->id_decrypted)}}">
                                <button class="blue-btn">Report</button>
                            </a>
                            <!-- <button class="blue-btn">Report</button> -->
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
                                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                      <i class="fas fa-caret-down"></i>
                                                      <i class="fas fa-caret-right"></i>
                                                        Overview
                                                    </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="collapseOne" class="collapse in ovrviwe" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="userimg"><img src="{{ asset('assets/edit_form/images/defaultpic.jpeg')}}"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                        <th>Company</th>
                                                        <td>{{ $detail->company_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date</th>
                                                        <td>{{ date('m/d/Y', strtotime($detail->created_at)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Completed by</th>
                                                        <td>{{ $detail->user_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Location</th>
                                                        <td>{{ $detail->location_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Business Unit</th>
                                                        <td>{{ !empty($detail->business)?!empty($detail->business->vc_short_name)?$detail->business->vc_short_name:'-': '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Department</th>
                                                        <td>{{ !empty($detail->dept_data)?!empty($detail->dept_data->vc_name)?$detail->dept_data->vc_name:'-':'-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Project</th>
                                                        <td>{{ !empty($detail->project_data)?!empty($detail->project_data->vc_name)?$detail->project_data->vc_name: '-' : '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="scope_methodolgy" contenteditable="false">Scope and Methodology</span>
                                                      </button>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="collapse scope_meth" aria-labelledby="collapseTwo" data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                            @forelse($detail->scopeMethodology as $scope)
                                                <div class="row">
                                                    <div class="filterwidth scope_container">
                                                        <div class="scope" id="scope_1">
                                                            <textarea placeholder="Scope and Methodology will be here..." disabled="true">{{$scope->snm_data}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
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
                                        <div class="section_container" id="section_container_1">
                                            <div class="card" >
                                                <div class="card-header" id="headingOne">
                                                    <div class="row">
                                                        <div class="col-lg-10">
                                                            <h5 class="mb-0">
                                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_{{$sections->id}}" aria-expanded="true" aria-controls="collapse_{{$sections->id}}">
                                                                    <i class="fas fa-caret-down"></i>
                                                                    <i class="fas fa-caret-right"></i>
                                                                    <span class="section"  contenteditable="false" id="section_title_1"> {{ $sections->name }}  </span>
                                                                </button>
                                                            </h5>
                                                        </div>
                                                        <div class="col-lg-2  text-center">
                                                            @if($sections->score)<b>Marks</b>@endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="collapse_{{$sections->id}}" class="collapse" aria-labelledby="collapse_{{$sections->id}}" data-parent="#accordion">
                                                    @if($sections->questions)
                                                     @foreach ($sections->questions as $question)

                                                    <div class="card-body main-card-body">
                                                        <div class="question_container" id="question_container_1" >
                                                            <div class="question_section_1" id="question_section_1_1">
                                                                <div class="question-main-sec">
                                                                    <div class="question-action-view">
                                                                        <span class="question-serial">{{$no++}}</span>
                                                                    </div>
                                                                    <div class="question-data-view">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="question">
                                                                                    <p>{{$question->text}}</p>
                                                                                </div>
                                                                                <div class="answer">
                                                                                @if(!empty($question->answers))
                                                                                    <!-- for check text -->
                                                                                    @if(in_array($question->question_type,array(config('constants.question_type.multi_choice'),config('constants.question_type.text'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.location'))))
                                                                                        <input type="text" value="{{$question->answers->answer}}" disabled="true">

                                                                                    <!-- for check option -->
                                                                                    @elseif($question->question_type == config('constants.question_type.two_option'))
                                                                                        @if($question->answers->answer ==1)
                                                                                            <input type="text" value="Yes" disabled="true" class="bg-green">
                                                                                        @elseif($question->answers->answer ==0)
                                                                                            <input type="text" value="No" disabled="true" class="bg-danger">
                                                                                        @endif
                                                                                        <!-- for check signature -->
                                                                                    @elseif(in_array($question->question_type, array(config('constants.question_type.signature'))))
                                                                                    <div class="col-md-10">
                                                                                        <img src="{{url('/uploads/'.$question->answers->answer.'')}}"
                                                                                            class="img-responsive "
                                                                                            alt="{{$question->answers->answer}}" style="width: 150px; height:100px"/>
                                                                                    </div>

                                                                                    <!-- for dropdown -->
                                                                                    @elseif($question->question_type == config('constants.question_type.dropdown'))
                                                                                        <input type="text" value="{{$question->answers->answer}}" disabled="true" style="background: #{{$question->answers->dropdown_color}}; color:black">

                                                                                    <!-- for check multiple select -->
                                                                                    @elseif(in_array($question->question_type,array(config('constants.question_type.multi_select'))))
                                                                                        @if($question->answers->type_option)
                                                                                            <input type="text" value="{{ implode(', ',$question->answers->type_option)}}" disabled="true">
                                                                                        @endif
                                                                                    @endif
                                                                                    <!-- {{$question->question_type}} === {{$question->answers->answer}} -->
                                                                                @endif
                                                                                </div>
                                                                                <!--- evidences ---->
                                                                                @if(!empty($question->answers))
                                                                                <div class="evidence evidence-{{ $question->answers->id }}">
                                                                                    @if (auth()->user()->user_type != 'supplier')
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


                                                                        <div class="question-comment">
                                                                            <div class="row">

                                                                                 @if($question->comments)
                                                                                    <div class="col-sm-12">
                                                                                        <hr>
                                                                                        <input type="text" value="{{ $question->comments->comment }}" disabled="true" class="danger" title="Comment">
                                                                                    </div>
                                                                                @else
                                                                                    <div class="col-sm-12">
                                                                                        <hr>
                                                                                       <input type="text" class="comment" value="" placeholder="comment will be here.." disabled="true">
                                                                                   </div>
                                                                                @endif

                                                                            </div>
                                                                        </div>
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
                                                                                                            <img src="{{url('/documentLibrary/'.$guide->documents->file_name.'')}}"class="img-responsive " alt="{{$guide->documents->title}}"/>

                                                                                                       @endif
                                                                                                   <p> <b>{{$guide->documents->description}}</b> </p>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                         <hr>
                                                                                    </div>                                                                                                                                                                        <!--guide media -->
                                                                                    <div class="col-md-12">
                                                                                        <hr>
                                                                                        @foreach ($question->guides as $guide)
                                                                                            @if($guide->document_name)
                                                                                                <div class="col-md-4">
                                                                                                    <div class="Uploads">
                                                                                                        <?php
                                                                                                            $ext = pathinfo($guide->document_name, PATHINFO_EXTENSION);
                                                                                                        ?>
                                                                                                        @if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg')
                                                                                                            <img src="{{url('/uploads/'.$guide->document_name.'')}}"class="img-responsive "/>
                                                                                                        @elseif($ext == 'mp4' || $ext == 'webm')
                                                                                                            <video width="260"  controls>
                                                                                                              <source src="{{url('/uploads/'.$guide->document_name.'')}}" type="video/mp4">
                                                                                                            </video>
                                                                                                        @elseif($ext == 'pdf')
                                                                                                            <iframe src="{{url('/uploads/'.$guide->document_name.'')}}" width="260"></iframe>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                @endif

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="question-action-view">
                                                                        <span class="question-score">
                                                                            @if($sections->score)
                                                                                @if(in_array($question->question_type,array(config('constants.question_type.text'),config('constants.question_type.dropdown'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.multi_choice'),config('constants.question_type.multi_select'),config('constants.question_type.signature'),config('constants.question_type.location'))))
                                                                                    @if((!empty($question->answers->answer)))
                                                                                        <input type="checkbox" disabled="true"  checked >
                                                                                    @else
                                                                                     <input type="checkbox" disabled="true" >
                                                                                    @endif
                                                                                @elseif($question->question_type == config('constants.question_type.two_option') && !empty($question->answers->answer) && ($question->answers->answer ==1))
                                                                                    1
                                                                                @elseif(in_array($question->question_type,array(config('constants.question_type.multi_choice'),config('constants.question_type.multi_select'))) && (!empty($question->answers->type_option)))
                                                                                    <input type="checkbox" disabled="true"  checked >
                                                                                @else
                                                                                    <input type="checkbox" disabled="true">
                                                                                @endif
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
                                    @empty
                                    <div class="section_container" id="section_container_1">
                                            <div class="card" >
                                                <div class="card-header" id="headingOne">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <h5 class="mb-0">
                                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse_na" aria-expanded="true" aria-controls="collapse_collapse_na">
                                                                    <i class="fas fa-caret-down"></i>
                                                                    <i class="fas fa-caret-right"></i>
                                                                    <span class="section"  contenteditable="false" id="section_title_1"> Section. </span>
                                                                </button>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="collapse_na" class="collapse" aria-labelledby="collapse_na" data-parent="#accordion">
                                                    <div class="card-body main-card-body">
                                                        <div class="question_container" id="question_container_1" >
                                                            <div class="question_section_1" id="question_section_1_1">
                                                                 <div class="row">
                                                                    <div class="col-md-12 no-section">
                                                                     <p>No Section added yet.</p>
                                                                    </div>
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
          </div>
        </div>
</div>
@endsection

@section('footer_scripts')

    <!-- <script src="{{asset('assets/template/js/jquery.min.js ')}}" type="text/javascript "></script> -->
    <script src="{{asset('assets/template/js/fontawesome.min.js ')}}" type="text/javascript "></script>
    <!-- <script src="{{asset('assets/template/js/bootstrap.min.js ')}}" type="text/javascript "></script> -->
    <script src="{{asset('assets/template/js/jquery.main.js ')}}" type="text/javascript "></script>

    <script src="{{asset('assets/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/js/template.js')}}"></script>
    <script>
        $(".addquestionbutton ").click(function() {
            // $(".addquestion-area ").slideToggle();
        });
        $(".btn-link").click(function(e) {
          // alert('clicked');
        });
    </script>

@endsection
