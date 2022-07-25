@extends('admin.layout.app')
@section('title'){{'Edit Action'}} @endsection
@section('header_css')
    <link href="{{ asset('assets/action/css/main.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css" rel="stylesheet"/>
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="{{asset('assets/action/js/jquery.min.js')}}" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        
    </style>
@endsection
@section('content')
<div class="page-content-wrapper wrapper">
    <div class="page-content page-wrap" >
        @include('partials.messages')
        <div class="pre_loader">
            <img src="{{asset('assets/images/loading.gif')}}" alt="">
        </div>

        <div class="adinfull">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="">Edit Action</a>
                    </li>
                </ul>
            </div>
            @php $checkPer = ($actionData->assined_user_id == Auth::id())?true:false; @endphp
            <div class="row">
                <div class="col-md-8">
                    <div class="editlogic">
                    <input type="hidden" id="site_url" value="{{asset('assets/action/images/')}}">
                    {{ Form::open(array('action' => 'FirebaseController@update_chat', 'method' => 'post', 'id' => 'chatForm'))}}
                        <div class="reportsetup actionheadingtext">@if($actionCreatedUser!=""){{ isset($actionCreatedUser->vc_fname) ? $actionCreatedUser->vc_fname : '' }} {{ isset($actionCreatedUser->vc_lname) ? $actionCreatedUser->vc_lname : '' }} @endif  created the action
                            @php
                                $createdAt = strtotime($actionData->created->format(DATE_TIME_FORMAT));
                                // date_default_timezone_set('Asia/Kolkata');
                                $actionCreatedAt = date('d M Y, g:i  A', $createdAt);
                            @endphp 
                            <span class="datetimeaction">{{ isset($actionCreatedAt) ? $actionCreatedAt : '' }}</span>
                        </div>
                        <input type="hidden" name="action_id" id="userActionId" value="{{$actionData->id}}">
                    <div class="chatsection" id="mainChatSection">
                    <div class="chat_pre_loader_{{$actionData->id}}" style="
    text-align: center;
">
                                    <img src="{{asset('assets/images/loading.gif')}}" alt="">
                                </div>
 
                        <div class="loadMediaChat  loadMediaChat_{{$actionData->id}}">
                            </div>
                            <div class="dynamicAppendChat  dynamicAppendChat_{{$actionData->id}}">

                            </div>
                        </div>
                        <div class="SendChat SendChat_{{$actionData->id}}"  style="display:none">
                            <textarea class="inputsectionChat "  id="messageTextarea" name="action_message" placeholder="Type Your Message Here"></textarea>
                            <div class="clipImage"><img id="updloadImage" src="{{asset('assets/action/images/clip.png')}}"></div>
                                <div class="dynamicMediaPreview"> 
                                    <img width="200px" height="200px" id="preview_image" src="" style="display:none"/>
                                    <a href="javascript:removeMediaPreview()" style="text-decoration:none;display:none" class="removePreviewDynamic">
                                        <i class="glyphicon glyphicon-trash "></i> Remove
                                    </a>&nbsp;&nbsp;
                                </div>
                            <input type="hidden" class="mediaExt" name="file_ext" value="">
                            <input type="file" id="file" style="display: none"/>
                            <input type="hidden" name="file_name" id="file_name"/>
                            <i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 44%;top: 21%;display: none;background:none;color: #5aa5ad;"></i>
                            <div class="sendChatBtn"><img id="enableEditChat" src="{{asset('assets/action/images/send.png')}}"></div>
                        </div>
                    </div>
                </div>
                    {{ Form::close() }}
                <div class="col-md-4">
                    <div class="editlogic">
                            {{ Form::open(array('action' => 'ActionsController@updateAction', 'method' => 'post', 'id' => 'updateAction'))}}
                            @csrf
                             <input type="hidden" name="action_id" value="{{$actionData->id}}">
                             <input type="hidden" name="user_id" value="@if(auth()->user()) {{auth()->user()->id}}@endif">
                             <!-- <div class="chatheading"><section id="actiontTitle" >{{ isset($actionData->title) ? $actionData->title : '' }}</section>@if(!$checkPer)<i class="fa fa-edit" id="titleEdit"></i>@endif</div> -->
                             @if ($errors->has('action_title'))
                                <div  class="actionError"><span class="text-danger">{{ $errors->first('action_title') }}</span></div>
                             @endif
                             <input placeholder="Enter Title" type="text" id="actionTitle" class="titleText" value="{{ isset($actionData->title) ? $actionData->title : '' }}" name="action_title"/>
                             <!-- <div class="chatheading2"><section id="actionDescription">{{ isset($actionData->descriptions) ? $actionData->descriptions : '' }}</section>@if(!$checkPer) <i class="fa fa-edit" id="editDescription"></i> @endif</div> -->
                             @if ($errors->has('action_desc'))
                                <div class="actionError"><span class="text-danger">{{ $errors->first('action_desc') }}</span><div>
                             @endif
                             <input placeholder="Enter Description" type="text"  id="actionDesc" class="descText" value="{{ isset($actionData->descriptions) ? $actionData->descriptions : '' }}" name="action_desc" maxlength="300"/>
                             <div class="chatheading2"><span>Recurring Action</span>
                                <input type="checkbox" name="recurring_action"  id="recurring_action" onclick="recurring();" value=1 @if($actionData->reocurring_actions == 1) checked @endif @if ($checkPer) disabled="disabled" readonly="readonly" @endif>
                            </div>
                            <div class="actionfiter">
                                <div class="row">
                                    <div class="col-md-12"><label>Status</label></div>
                                    <div class="col-md-8">
                                        <div class="filterwidth">
                                            <select name="status" id="actionStatus">
                                                @if($actionData->status == 2)
                                                    <option value="2" selected >In-Progress</option>
                                                    <option value="5">Rejected</option>
                                                @elseif($actionData->status == 6)
                                                    <option value="6" selected >Overdue</option>
                                                @elseif($actionData->status == 5)
                                                    <option value="5" selected >Rejected</option>
                                                    <option value="1">Pending</option>
                                                @else
                                                    <option value="1" selected >Pending</option>
                                                    <option value="5">Rejected</option>
                                                    <option value="2">Approved</option>
                                                @endif
                                            </select>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                     <i id="completeLoader" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 44%;top: 4%;display: none;background:none;color: #fff;"></i>
                                     @if($actionData->status != 3 && $actionData->status != 4 && $actionData->status != 5)
                                        {{-- <div class="uploaddocadin"><button type="button" class="blue-btn" id="actionComplete" onclick="close_action('{{$actions->id}}')">Complete</button></div> --}}
                                        <div class="uploaddocadin"><button type="button" class="blue-btn" id="" onclick="close_action('{{$actionData->id}}')">Complete</button></div>
                                     @endif
                                    </div>
                                </div>
                                <div class="deatilsaction">
                                    Details
                                </div>
                                {{-- <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Location</label>
                                        <select name="location" id="actionLocation" @if ($checkPer) disabled="disabled" readonly="readonly" @endif>
                                        <option value="">Select Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}" @if($actionData->location_id == $location->id) selected @endif>{{$location->vc_name}}</option>
                                            @endforeach
                                         </select>
                                    </div>
                                    @if ($errors->has('location'))
                                        <span class="text-danger">{{ $errors->first('location') }}</span>
                                    @endif
                                </div> --}}
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Business Unit</label>
                                        <select name="business_unit" class="business_unit" id="business_unit" >
                                        <option value="">-- Business Unit --</option>
                                            @foreach($business_units as $bu)
                                            <option value="{{$bu->id}}" @if($actionData->business_unit_id == $bu->id) selected @endif>{{$bu->vc_short_name}}</option>
                                            @endforeach
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Department</label>
                                        <select name="department" class="department" id="department" >
                                        <option value="">-- Department --</option>
                                        @if(isset($budata->business_dept) && !empty($budata->business_dept))
                                            @foreach($budata->business_dept as $dept)
                                                <option value="{{$dept->department_id}}" @if($actionData->department_id == $dept->department_id) selected @endif>{{$dept->dept_data->vc_name}}</option>
                                            @endforeach
                                        @endif
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Project</label>
                                        <select name="project" class="project" id="project" >
                                        <option value="">-- Project --</option>
                                        @if(isset($budata->projects) && !empty($budata->projects))
                                            @foreach($budata->projects as $project)
                                                <option value="{{$project->id}}" @if($actionData->project_id == $project->id) selected @endif>{{$project->vc_name}}</option>
                                            @endforeach
                                        @endif
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Assignee</label>
                                        <select  name="assignee" id="actionAsigne" @if ($checkPer) disabled="disabled" readonly="readonly" @endif>
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                            <option value="{{$user->id}}"
                                                 @if($actionData->assined_user_id == $user->id) selected @endif>
                                                 {{( (!empty($user->bussiness_name) && $user->bussiness_name!="") ? $user->bussiness_name : "" ) }} 
                                                 {{((!empty($user->users_details->roles))? $user->users_details->roles->vc_name: "")}}
                                                 [{{$user->vc_fname}} {{$user->vc_lname}}]
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('assignee'))
                                        <span class="text-danger">{{ $errors->first('assignee') }}</span>
                                    @endif
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Priority</label>
                                        <select name="priority" id="actionPriority" @if ($checkPer) disabled="disabled" readonly="readonly" @endif>
                                            @foreach (App\Models\Action::getPriorityArray() as $key => $value)
                                                <option value="{{ $key }}" @if($actionData->priority == $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                      </div>
                                      @if ($errors->has('priority'))
                                        <span class="text-danger">{{ $errors->first('priority') }}</span>
                                      @endif
                                </div>
                                <?php 
                                    $due_date = '';
                                    if($actionData->due_date != ''){
                                        $due_date =  date(DATE_FORMAT, strtotime($actionData->due_date));
                                    } 
                                ?>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                      <div> Due Date <input type="text" id="datepicker" class="due_date" name="due_date" value="{{$due_date}}" @if ($checkPer) disabled="disabled" readonly="readonly" @endif /></div>
                                    </div>
                                    @if ($errors->has('due_date'))
                                        <span class="text-danger">{{ $errors->first('due_date') }}</span>
                                    @endif
                                </div>
                                @php
                                    $createdDate = date('d M Y', $createdAt);
                                    $createdTime = date('g:i  A', $createdAt);
                                @endphp
                                @if($actionCreatedUser!="")
                                <div class="actionfitertwo">
                                    Created by {{ isset($actionCreatedUser['full_name']) ? $actionCreatedUser['full_name'] : '' }},
                                    {{ isset($actionCreatedAt) ? $actionCreatedAt : '' }}
                                </div>
                                @endif 
                                @if($actionData->status == 3 )
                                    <div class="actionfitertwo">
                                        <div class="filterwidth">
                                            <label>Comment</label>
                                            <textarea name="action_comment" class="commentSection" rows="4" cols="50" placeholder="Enter comment">{{isset($actionData->comment) ? $actionData->comment : ''}}</textarea>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="action_comment" value="">
                                @endif
                                <div class="filterbutton">
                                    <input type="submit" class="blue-btn" name="update_and_assign" value="Update and Assign" id="update_and_assign">
                                </div>
                            </div>
                            <!-- recurring Modal -->
                            <div id="recurring_modal" class="modal">

                                <!-- Modal content -->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="modal_close" onclick="close_modal('cancel_edit')">&times;</span>
                                        <div class="category_title">Recurrence Settings</div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recurring_div">
                                            <div>
                                            <div class="recurrence_heading">
                                                Recurrence Pattern
                                            </div>
                                            </div>
                                            <?php $start_date = !empty($recurringData)&& !empty($recurringData->start_date) ? date_format($recurringData->start_date,"Y-m-d"): '';
                                                $end_date = !empty($recurringData)&& !empty($recurringData->end_date) ? date_format($recurringData->end_date,"Y-m-d"): '';
                                            ?>
                                            <input type="hidden" name="cancel_edit" id="cancel_edit" value=''>
                                            <input type="hidden" name="recurring_id" id="recurring_id" value='{{!empty($recurringData)?$recurringData->id:""}}'>
                                            <div class="first_div">
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="daily_recur recurring_type" onclick="show_data('daily')" value=1 @if(!empty($recurringData) && $recurringData->recurrence_type==1) checked @endif> Daily</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="weekly_recur recurring_type" onclick="show_data('weekly')" value=2 @if(!empty($recurringData) && $recurringData->recurrence_type==2) checked @endif>Weekly</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="monthly_recur recurring_type" onclick="show_data('monthly')" value=3 @if(!empty($recurringData) && $recurringData->recurrence_type==3) checked @endif>Monthly</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="yearly_recur recurring_type" onclick="show_data('yearly')" value=4 @if(!empty($recurringData) && $recurringData->recurrence_type==4) checked @endif>Yearly</div>
                                                <div class="weekly_data" @if(!empty($recurringData) && $recurringData->recurrence_type==2) style="display:block" @else style="display:none" @endif>
                                                    Recur every <select name="weekly_week" id="recur" class="weekly_week">
                                                        {{$i=1}} @for($i==1; $i<=7;  $i++) <option value={{$i}} @if(!empty($recurringData) && $recurringData->recurrence_type==2 && $recurringData->week == $i) selected @endif>{{$i}}</option> @endfor
                                                    </select> Week(s) on <br>
                                                    <fieldset id="weeklyday_array"><div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='1'> Sunday </div>
                                                    <div class="weekdayscheckboxes"> <input type="checkbox" name="weekly_day[]" class="weekly_day" value='2'> Monday </div>
                                                    <div class="weekdayscheckboxes"> <input type="checkbox" name="weekly_day[]" class="weekly_day" value='3'> Tuesday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='4'> Wednesday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='5'> Thursday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='6'> Friday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='7'> Saturday </div></fieldset>
                                                </div>
                                                <div class="monthly_data" @if(!empty($recurringData) && $recurringData->recurrence_type==3) style="display:block" @else style="display:none" @endif>
                                                    <input type="radio" name="monthly_pattern" class="monthly_pattern" value="1" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && !empty($recurringData->week)) checked @endif>  
                                                        The <select name="month_day" id="month_day" class="month_day">
                                                            <option value="1" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->day == 1) selected @endif>First</option>
                                                            <option value="2" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->day == 2) selected @endif>Second</option>
                                                            <option value="3" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->day == 3) selected @endif>Third</option>
                                                            <option value="4" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->day == 4) selected @endif>Forth</option>
                                                        </select> 
                                                    <select name="month_week" id="month_week" class="month_week">
                                                        <option value=1 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 1) selected @endif>Sunday</option>
                                                        <option value=2 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 2) selected @endif>Monday</option>
                                                        <option value=3 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 3) selected @endif>Tuesday</option>
                                                        <option value=4 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 4) selected @endif>Wednesday</option>
                                                        <option value=5 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 5) selected @endif>Thursday</option>
                                                        <option value=6 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 6) selected @endif>Friday</option>
                                                        <option value=7 @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->week == 7) selected @endif>Saturday</option>
                                                    </select> of every 
                                                    <select name="month_month" id="month_month" class="month_month">{{$i=1}} @for($i==1; $i<=12;  $i++) <option value={{$i}} @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->month == $i) selected @endif>{{$i}}</option> @endfor</select> month(s) <br><br>
                                                    <input type="radio" name="monthly_pattern" class="monthly_pattern" value="2" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && empty($recurringData->week)) checked @endif>  
                                                    Day <select name="month_day_sec" id="month_day" class="month_day_sec">{{$i=1}} @for($i==1; $i<=30;  $i++) <option value="{{$i}}" @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->day == $i) selected @endif>{{$i}}</option> @endfor</select> of every 
                                                    <select name="month_month_sec" id="month_month" class="month_month_sec">{{$i=1}} @for($i==1; $i<=12;  $i++) <option value={{$i}} @if(!empty($recurringData) && $recurringData->recurrence_type==3 && $recurringData->month == $i) selected @endif>{{$i}}</option> @endfor</select> month(s)
                                                </div>
                                                <div class="yearly_data" @if(!empty($recurringData) && $recurringData->recurrence_type==4) style="display:block" @else style="display:none" @endif>
                                                    <input type="radio" name="yearly_pattern" class="yearly_pattern" value="1"> The <select name="year_day" id="year_day" class="year_day">
                                                        <option value="1" @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->week == 1) selected @endif>First</option>
                                                        <option value="2" @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->week == 2) selected @endif>Second</option>
                                                        <option value="3" @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->week == 3) selected @endif>Third</option>
                                                        <option value="4" @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->week == 4) selected @endif>Forth</option> 
                                                    </select>
                                                    <select name="year_week" id="year_week" class="year_week">
                                                        <option value=1 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 1) selected @endif>Sunday</option>
                                                        <option value=2 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 2) selected @endif>Monday</option>
                                                        <option value=3 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 3) selected @endif>Tuesday</option>
                                                        <option value=4 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 4) selected @endif>Wednesday</option>
                                                        <option value=5 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 5) selected @endif>Thursday</option>
                                                        <option value=6 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 6) selected @endif>Friday</option>
                                                        <option value=7 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->day == 7) selected @endif>Saturday</option>
                                                    </select> of 
                                                    <select name="year_month" id="year_month" class="year_month" > 
                                                        <option value=1 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 1) selected @endif>January</option>
                                                        <option value=2 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 2) selected @endif>February</option>
                                                        <option value=3 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 3) selected @endif>March</option>
                                                        <option value=4 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 4) selected @endif>April</option>
                                                        <option value=5 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 5) selected @endif>May</option>
                                                        <option value=6 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 6) selected @endif>June</option>
                                                        <option value=7 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 7) selected @endif>July</option>
                                                        <option value=8 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 8) selected @endif>August</option>
                                                        <option value=9 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 9) selected @endif>September</option>
                                                        <option value=10 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 10) selected @endif>October</option>
                                                        <option value=11 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 11) selected @endif>November</option>
                                                        <option value=12 @if(!empty($recurringData) && $recurringData->recurrence_type==4 && $recurringData->month == 12) selected @endif>December</option>
                                                    </select>
                                                    <br>
                                                <br>
                                                <input type="radio" name="yearly_pattern" class="yearly_pattern" value="2">
                                                <select name="year_day_second" class="year_day_second">{{$i=1}} @for($i==1; $i<=30;  $i++) <option value="{{$i}}">{{$i}}</option> @endfor</select>
                                                <select name="year_month_second" id="year_month_second" class="year_month_second" > 
                                                    <option value=1>January</option>
                                                    <option value=2>February</option>
                                                    <option value=3>March</option>
                                                    <option value=4>April</option>
                                                    <option value=5>May</option>
                                                    <option value=6>June</option>
                                                    <option value=7>July</option>
                                                    <option value=8>August</option>
                                                    <option value=9>September</option>
                                                    <option value=10>October</option>
                                                    <option value=11>November</option>
                                                    <option value=12>December</option>
                                                </select>
                                                </div>
                                                <div class="daily_data" @if(!empty($recurringData) && $recurringData->recurrence_type==1) style="display:block" @else style="display:none" @endif>
                                                    Every<select name="daily_day" class="daily_day">{{$i=1}} @for($i==1; $i<=30;  $i++) <option value="{{$i}}" @if(!empty($recurringData) && $recurringData->recurrence_type==1 && $recurringData->day == $i) selected @endif>{{$i}}</option> @endfor</select> Day(s)
                                                </div>
                                                <div class="recur_error"> </div>
                                            </div>
                                            <br style="clear:both;"/>
                                            <div class="recurrence_heading">
                                                Range of Recurrence
                                            </div>
                                            <div class="second_div">
                                                <div class="datestartrec">Start Date <input type="date" class="ignore" name="start_date" id="start_recur" min="" onchange="set_end()" value="{{$start_date}}"></div>
                                                <div class="datestartrec">End Date <input type="date" class="ignore" name="end_date" id="end_recur" min=''  value="{{$end_date}}"></div>
                                                <div class="date_error"> </div>
                                            </div>
                                            <br style="clear:both;"/>
                                            <div class="button_div"> 
                                                <input type="button" name="ok" value="OK" class="btn btn-success category-btn save_recurring" onclick="close_modal('ok')">
                                                <input type="button" class="btn btn-danger cancel_modal" id="cancel_modal" value="Cancel" onclick="close_modal('cancel_edit')">
                                                @if(!empty($recurringData))
                                                    @if(!empty($recurringData->id))
                                                        <input type="button" name="remove_recur" value="Remove Recurrence" class="btn btn-warning " onclick="remove_recurring('{{$recurringData->id}}', '{{$actionData->id}}')">
                                                    @endif
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.complete-action-modal')

@endsection
@section('footer_scripts')
    <script type="text/javascript">
            
        $('#update_and_assign' ).click(function(){
            console.log($('#updateAction').valid());
            if($('#updateAction').valid()){
                $('#updateAction input').removeClass('action_change');
                $('#updateAction select').removeClass('action_change');
                $('#updateAction textarea').removeClass('action_change');
                $('#updateAction section').removeClass('action_change');
                $('#actiontTitle').removeClass('action_change');
                $('#actionDescription').removeClass('action_change');
            }
        });
        
        // add class if any input is changed 
        $('#updateAction input, #updateAction select, #updateAction textarea, #updateAction section, #actionDescription, #actiontTitle').on('keyup change', function(){
            $(this).addClass('action_change');
        });

        // alert before leaving create template form
        $(window).on('beforeunload', function(){
            if($('#updateAction input').hasClass('action_change') || $('#updateAction select').hasClass('action_change') || 
            $('#updateAction section').hasClass('action_change') || $('#updateAction textarea').hasClass('action_change') ||
            $('#actiontTitle').hasClass('action_change') || $('#actionDescription').hasClass('action_change') ){
                var c=confirm();
                if(c){
                return true;
                }
                else
                return false;
            }
        });
            
    </script>
    <script src="{{asset('assets/js/action.js')}}"></script>
    <script src="{{asset('assets/js/business_unit.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js "></script>
    <script src="{{asset('assets/action/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/action/js/fontawesome.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/action/js/jquery.main.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>  
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <!-- <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script> -->
    <script src="{{asset('assets/action/js/action.js')}}" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    {{-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> --}}


    <script>

                $("#actionAsigne").select2();
        </script>
@endsection