<?php //pr($detail);die('====='); ?>
<?php //prt($data);die('====='); ?>
@extends('admin.layout.app')
@section('title','Create Template')
@section('header_css')
<style>
    span .cross_file{
        display: none;
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
    <link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
          <div class="uploaddocadin">
            <a href="{{route('edit_form', $detail->id_decrypted)}}">
                <button class="blue-btn" style="margin-top: 2px;">BUILD</button>
            </a>
          </div>
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
                  <span>Report </span>
                </li>
            </ul>
        </div>
        <div class="row">
        <div class="wrapper" style="padding:12px;">
        <div class="">
       
            <div class="page-wrap edit_form_accordion">
                <div class="col-md-9">
                    <div class="addinname">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>{{$errors->first()}}!</strong>
                        </div>
                    @endif
                        <h1>Report Setup and Preview</h1>
                        {{ Form::open(array('route' => array('report_pdf', $detail->id))) }}
                          <input type="hidden" name="report_filter_string" class="report_filter_string" value="{{$data['report_filter_string']}}">
                          <button class="btn-primary report_pdf" style="">PDF</button>
                        {{ Form::close() }}

                    </div>
                    <div class="editlogic">
                        <div class="variable-type-sub">
                            <input type="text" placeholder="Template Name" value="{{isset($detail->Template)?$detail->Template->template_name:''}}" disabled="true">
                        </div>
                        <div class="variable-type-sub">
                            <input type="text" placeholder="completed forms id" value="{{$detail->form_id}}" disabled="true">
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
                                                  @php $compnyProfilePic = $compnyProfilePic @endphp
                                                  @if(!empty($compnyProfilePic))
                                                    @php  $name = P2B_BASE_URL.'/uploads/company_logos/'.$compnyProfilePic; @endphp
                                                    <div class="userimg"><img src= {{$name}}></div>
                                                  @else
                                                    <div class="userimg"><img src="{{ public_path('assets/edit_form/images/defaultpic.jpeg')}}"></div>
                                                  @endif
                                                </div>
                                            </div>
                                            <div class="row">
                                                <table class="table">
                                                    <tr>
                                                      <div class="col-md-4" style="text-align: center; border:1px solid #d9d9d9;">
                                                        <span>Form Score</span><br>
                                                        <span><b>{{$data['form_score_percent']}}%</b></span>
                                                      </div>
                                                      <div class="col-md-4" style="text-align: center; border:1px solid #d9d9d9;">
                                                        <span>Created Action(s)</span><br>
                                                        <span><b>{{$data['total_actions']}}</b></span>
                                                      </div>
                                                      <div class="col-md-4" style="text-align: center; border:1px solid #d9d9d9;">
                                                        <span>Items Needing Review</span><br>
                                                        <span><b>{{$data['failled_items']}}</b></span>
                                                      </div>
                                                    </tr>
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
                                                        <td>Business Unit</td>
                                                        <td colspan="2">{{ !empty($detail->business)?!empty($detail->business->vc_short_name)?$detail->business->vc_short_name:'-': '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Department</td>
                                                        <td colspan="2">{{ !empty($detail->dept_data)?!empty($detail->dept_data->vc_name)?$detail->dept_data->vc_name:'-':'-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Poject</td>
                                                        <td colspan="2">{{ !empty($detail->project_data)?!empty($detail->project_data->vc_name)?$detail->project_data->vc_name: '-' : '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                              <!-- <input type="hidden" name="report_filter_string" class="report_filter_string" value="{{$data['report_filter_string']}}"> -->

                              @if(in_array('scope_method', $data['report_filter']) )
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
                              @endif

                              @if(in_array('Failed_Items', $data['report_filter']) )
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo_fail" aria-expanded="true" aria-controls="collapseTwo_fail">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="scope_methodolgy" contenteditable="false">Items Needing Review</span>
                                                      </button>
                                                </h5>
                                            </div>
                                            <div class="col-lg-2  text-center" style="padding-top: 11px">
                                                <span class="failed_count">{{$data['failled_items']}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseTwo_fail" class="collapse scope_meth" aria-labelledby="collapseTwo_fail" data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                          @php $i = 1 @endphp
                                            @forelse($data['failled_items_list'] as $failled_item)
                                                <div class="row">
                                                    <div class="filterwidth scope_container">
                                                        <div class="scope" id="scope_1">
                                                          <div class="setings">
                                                            {{$i}}. <span class="sec_nam">{{$failled_item['section_name']}}</span>
                                                            <br>
                                                            <span class="ques_desc">{{$failled_item['text']}}</span>
                                                            @if($failled_item['question_type'] == 5 && $failled_item['answers']['answer'] == 0)
                                                                <br><br>
                                                                <span class="ques_respnc">{{'NO'}}</span>
                                                            @endif
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $i++ @endphp
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
                              @endif

                              @if(in_array('Actions', $data['report_filter']) )
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo_actions" aria-expanded="true" aria-controls="collapseTwo_actions">
                                                        <i class="fas fa-caret-down"></i>
                                                        <i class="fas fa-caret-right"></i>
                                                        <span class="scope_methodolgy" contenteditable="false">Actions</span>
                                                      </button>
                                                </h5>
                                            </div>
                                            <div class="col-lg-2  text-center" style="padding-top: 11px">
                                                <span class="actions_count">{{$data['total_actions']}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapseTwo_actions" class="collapse scope_meth action_container" aria-labelledby="collapseTwo_actions" data-parent="#accordion">
                                        <div class="card-body main-card-body">
                                          @php $i = 1 @endphp
                                            @forelse($data['actions_list'] as $action_list)
                                                <div class="question-action-view">
                                                    <span class="question-serial">{{$i}}</span>
                                                </div>
                                                <div class="row">
                                                    <div class="filterwidth scope-method">
                                                        <div class="scope" id="scope_1">
                                                          <div class="setings">
                                                            <a href="{{url('admin/edit-action')}}/{{ encrypt_decrypt("encrypt", $action_list['actions'][0]['id']) }}"><span class="ques_desc">Title:- {{$action_list['actions'][0]['title']}}</span></a>
                                                            <br><br>
                                                            @if ($action_list['actions'][0]['priority'] == 1)
                                                                <span class="action_dscr col-md-12" style="font-size: 14px;">{{$action_list['user']}} has created LOW Priority Action for {{$action_list['aasigned_user']}}</span>
                                                            @elseif ($action_list['actions'][0]['priority'] == 2)
                                                                <span class="action_dscr col-md-12" style="font-size: 14px;">{{$action_list['user']}} has created MEDIUM Priority Action for {{$action_list['aasigned_user']}}</span>
                                                            @else
                                                                <span class="action_dscr col-md-12" style="font-size: 14px;">{{$action_list['user']}} has created HIGH Priority Action for {{$action_list['aasigned_user']}}</span>
                                                            @endif
                                                            <br><br>
                                                            @if ($action_list['actions'][0]['status'] == 1)
                                                              @php $status = 'Pending' @endphp
                                                            @elseif ($action_list['actions'][0]['status'] == 2)
                                                              @php $status = 'In-Progress' @endphp
                                                            @elseif ($action_list['actions'][0]['status'] == 3)
                                                              @php $status = 'Completed' @endphp
                                                            @elseif ($action_list['actions'][0]['status'] == 5)
                                                              @php $status = 'Rejected' @endphp
                                                            @else
                                                              @php $status = 'Rejected' @endphp
                                                            @endif
                                                            <span class="col-md-3" style="font-size: 14px;">
                                                              <div class="action_status">
                                                                {{ $status }}
                                                              </div>
                                                            </span>
                                                            <span class="action_date col-md-3" style="font-size: 14px; margin-left: 40px;"><i class="fa fa-calendar" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($action_list['actions'][0]['created_at'])->format('j M, Y') }}</span>
                                                            <br><br>
                                                            <span class="sec_nam">{{$action_list['section_name']}}</span>
                                                            <br>
                                                            <span class="action_date action-content col-md-12" style="font-size: 14px;color: #29C0D4;">Question:- {{$action_list['text']}}</span>
                                                            <br><br>
                                                                                                                        
                                                          </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @php $i++ @endphp
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
                              @endif

                                <div class="section_contain">
                                    @php $i = 0 @endphp
                                    <div class="col-lg-2  text-center" style="float: right;">
                                        @if(in_array('Marks', $data['report_filter']) )
                                          <b>Marks</b><br>
                                          @php $t_per = $data['section_wise_score']/$data['total_questions']*100 @endphp
                                          <b>{{$data['section_wise_score']}}/{{$data['total_questions']}} (<?php echo round($t_per,1); ?>%)</b>
                                        @endif
                                      </div>
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
                                                                            <div class="col-md-10">
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
                                                                                        @if(!empty($question->answers->answer))
                                                                                        <img src="{{url('/uploads/'.$question->answers->answer.'')}}"
                                                                                            class="img-responsive "
                                                                                            alt="{{$question->answers->answer}}" style="width: 150px; height:100px" />
                                                                                        @endif
                                                                                    </div>

                                                                                    <!-- for check multiple select -->
                                                                                    @elseif(in_array($question->question_type,array(config('constants.question_type.multi_select'))))
                                                                                        @if($question->answers->type_option)
                                                                                            <input type="text" value="{{ implode(', ',$question->answers->type_option)}}" disabled="true">
                                                                                        @endif

                                                                                    <!-- for dropdown -->
                                                                                    @elseif($question->question_type == config('constants.question_type.dropdown'))
                                                                                        @if(in_array('Color_Coded', $data['report_filter']) )
                                                                                        <input type="text" value="{{$question->answers->answer}}" disabled="true" style="background: #{{$question->answers->dropdown_color}}; color:black">
                                                                                        @endif
                                                                                    @endif
                                                                                    <!-- {{$question->question_type}} === {{$question->answers->answer}} -->
                                                                                @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                        <!-- <div class="question-comment">
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
                                                                        </div> -->
                                                                      @if(in_array('Media_Summery', $data['report_filter']) && !empty($question->answers))
                                                                        <!--- evidences ---->
                                                                        <div class="evidence evidence-{{ $question->answers->id }}">
                                                                        @if($question->answers->has('evidences') && count($question->answers->evidences) > 0)
                                                                        <?php $evidencesRows = $question->answers->evidences; ?>
                                                                        @include('partials.evidences')
                                                                        @endif
                                                                        </div>
                                                                        <!--- end evidences ---->
                                                                      @endif
                                                                    </div>
                                                                    <div class="question-action-view">
                                                                        <span class="question-score">
                                                                          <!-- {{$question->answers['answer']}}<br> -->

                                                                          @php $mark = 1 @endphp
                                                                          @if($question->question_type == 5 && $question->answers['answer'] == 0 )
                                                                            @php $mark = 0 @endphp
                                                                          @endif

                                                                          @if($question->question_type == 6 && $question->answers['answer'] == 0 )
                                                                            @php $mark = ' <span><i class="fa fa-remove" style="font-size:36px;color:#EC6C6C"></i></span> ' @endphp
                                                                          @elseif($question->question_type == 6 && $question->answers['answer'] == 1 )
                                                                            @php $mark = ' <span><i class="fa fa-check" style="font-size:36px;color:#29C0D4"></i></span> ' @endphp
                                                                          @endif

                                                                          @if($question->question_type == 7 && empty($question->answers['type_option']) )
                                                                            @php $mark = ' <span><i class="fa fa-remove" style="font-size:36px;color:#EC6C6C"></i></span> ' @endphp
                                                                          @elseif($question->question_type == 7 && !empty($question->answers['type_option']) )
                                                                            @php $mark = ' <span><i class="fa fa-check" style="font-size:36px;color:#29C0D4"></i></span> ' @endphp
                                                                          @endif

                                                                          @if(!in_array($question->question_type, [5,6,7]) && empty($question->answers['answer']) )
                                                                            @php $mark = 0 @endphp
                                                                          @endif

                                                                          @if(in_array($question->question_type, [6,7]) )
                                                                            {!! $mark !!}
                                                                          @else
                                                                            {{$mark}}
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
                                        @php $i++ @endphp
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
                <div class="report_pref col-md-3">
                  <span><h3><b>Report preference</b></h3></span><br>
                  <div class="prefrence col-md-12">
                    Report preference
                    <a href="#" class="edit_pref"><i class="fa fa-edit default_pref"></i>edit</a>
                  </div>
                  <div class="pref_checkbox col-md-12">
                        <b>Filters..</b><br><br>
                        <!-- <form action="#" method="post" id="myForm"> -->
                        {{ Form::open(array('route' => array('report', $detail->id_decrypted))) }}
                            <div>
                                <input type="checkbox" class="rep_checkbox Actions" name="Actions" value="Actions" id="actions">
                                <label for="actions"> Actions</label>
                            </div>
                            {{-- <br> --}}
                          <input type="checkbox" class="rep_checkbox Failed_Items" name="Failed_Items" value="Failed Items" id="failed-items">
                          <label for="failed-items"> Items Needing Review</label><br>
                          <input type="checkbox" class="rep_checkbox Marks" name="Marks" value="Marks" id="marks" >
                          <label for="marks"> Marks</label><br>
                          <input type="checkbox" class="rep_checkbox Media_Summery" name="Media_Summery" value="Media Summery" id="media_summary">
                          <label for="media_summary"> Media Summary</label><br>
                          <input type="checkbox" class="rep_checkbox scope_method" name="scope_method" value="Scope and Methodology" id="scope">
                          <label for="scope"> Scope and Methodology</label><br>
                          <input type="checkbox" class="rep_checkbox Color_Coded" name="Color_Coded" value="Color Coded Dropdown" id="color_coded">
                          <label for="color_coded"> Color Coded Dropdown</label><br><br>

                          <button value="Submit"  class="btn btn-success sbmit" type="submit">Save</button>
                        <!-- </form> -->
                        {{ Form::close() }}

                  </div>

                  <!-- <div class=" col-md-12">
                    <button class="btn btn-primary add_new_pref" type="button"><i class="fa fa-plus"></i> &nbsp;&nbsp;New Prefrence</button>
                  </div> -->

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
    <script src="{{asset('assets/js/report_pref.js')}}"></script>

    <script>
        $(".addquestionbutton ").click(function() {
            // $(".addquestion-area ").slideToggle();
        });
        $(".btn-link").click(function(e) {
          // alert('clicked');
        });
    </script>

@endsection
