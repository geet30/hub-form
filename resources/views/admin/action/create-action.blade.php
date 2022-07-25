@extends('admin.layout.app')
@section('title'){{ 'Create Action' }}@endsection
@section('header_css')
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous"> -->
    <!-- <link href="{{ asset('assets/action/css/fontawesome.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('assets/action/css/fontawesome-all.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/action/css/main.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css" rel="stylesheet"/>
    <script src="{{asset('assets/action/js/jquery.min.js')}}" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<div class="page-content-wrapper wrapper">
    <div class="page-content page-wrap">
        {{-- error and success messages --}}
        @include ('partials.messages')
        <div class="adinfull">
            <div class="pre_loader">
                <img src="{{asset('assets/images/loading.gif')}}" alt="">
            </div>
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('create_action') }}">Create Action</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="editlogic">
                       {{ Form::open(array('action' => 'FirebaseController@initailize_chat', 'method' => 'post', 'id' => 'chatForm'))}}
                        {{-- <div class="reportsetup actionheadingtext">Jack created the action
                            <span class="datetimeaction">08 May 2020, 2:35 PM</span>
                        </div> --}}
                        <div class="chatsection">
                            {{-- <div class="rightChat">
                                <div class="namechatperson">John Doe</div>
                                <div class="innerLeftChat">
                                    Window is dirty need to clean urgently
                                    <span class="timingChat">8 May, 10:22 AM</span>
                                </div>

                            </div> --}}

                            <div class="SendChat" style="bottom: -83%;">
                                <textarea class="inputsectionChat"  name="chat_subject" placeholder="Type Your Message Here" disabled ></textarea>
                                <div class="clipImage"><img src="{{asset('assets/action/images/clip.png')}}"></div>
                                <div class="sendChatBtn"><img src="{{asset('assets/action/images/send.png')}}"></div>
                            </div>
                        </div>
                    {{ Form::close() }}
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="editlogic">
                            {{ Form::open(array('action' => 'ActionsController@saveAction', 'method' => 'post', 'id' => 'createAction'))}}
                            @csrf
                             <input type="hidden" name="user_id" value="@if(auth()->user()) {{auth()->user()->id}}@endif">
                             <!-- <div class="chatheading"><section id="actiontTitle"></section><i class="fa fa-edit" id="titleEdit"></i></div> -->
                             @if ($errors->has('action_title'))
                                <div  class="actionError"><span class="text-danger">{{ $errors->first('action_title') }}</span></div>
                             @endif
                             <input placeholder="Enter Title" type="text" id="actionTitle" class="titleText " value="" name="action_title" />
                             <!-- <div class="chatheading2"><section id="actionDescription"></section> <i class="fa fa-edit" id="editDescription"></i></div> -->
                             @if ($errors->has('action_desc'))
                                <div class="actionError"><span class="text-danger">{{ $errors->first('action_desc') }}</span><div>
                             @endif
                            </br>
                            </br>
                             <input placeholder="Enter Description" type="text" id="actionDesc" class="descText action_desc" value="" name="action_desc" />
                             
                            <div class="chatheading2"><span>Recurring Action</span>
                                <input type="checkbox" name="recurring_action" value=1 onclick="recurring();" id="recurring_action">
                            </div>
                            <div class="actionfiter">
                                {{-- <div class="row">
                                    <div class="col-md-12"><label>Status</label></div>
                                    <div class="col-md-8">
                                        <div class="filterwidth">
                                            <select name="status" id="actionStatus" class="status" >
                                                @foreach (App\Models\Action::getActionStatusArray() as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                     <div class="col-md-4">
                                        <div class="uploaddocadin"><button class="blue-btn">Complete</button></div>
                                    </div>
                                </div> --}}
                                <div class="deatilsaction">
                                    Details
                                </div>
                                {{-- <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Location</label>
                                        <select name="location" class="location" id="actionLocation" >
                                        <option value="">Select Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->vc_name}}</option>
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
                                            <option value="{{$bu->id}}">{{$bu->vc_short_name}}</option>
                                            @endforeach
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Department</label>
                                        <select name="department" class="department" id="department" >
                                        <option value="">-- Department --</option>
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Project</label>
                                        <select name="project" class="project" id="project" >
                                        <option value="">-- Project --</option>
                                         </select>
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Assignee To</label>
                                        <select name="assignee" class="assignee" id="actionAsigne" >
                                            <option value="">-- User --</option>

                                            <?php
                                                foreach($users as $user){
                                            
                                            ?>

                                            <option value="{{$user->id}}"> {{( (!empty($user->bussiness_name) && $user->bussiness_name!="") ?
                                                 $user->bussiness_name : "" ) }} {{((!empty($user->users_details->roles))? $user->users_details->roles->vc_name: "")}}   [{{$user->vc_fname}} {{$user->vc_lname}}]</option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    @if ($errors->has('assignee'))
                                        <span class="text-danger">{{ $errors->first('assignee') }}</span>
                                    @endif
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                        <label>Priority</label>
                                        <select name="priority" class="priority" id="actionPriority" >
                                            @foreach (App\Models\Action::getPriorityArray() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                      </div>
                                      @if ($errors->has('priority'))
                                        <span class="text-danger">{{ $errors->first('priority') }}</span>
                                      @endif
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                      <div> Due Date <input type="text" id="datepicker" class="due_date" name="due_date" /></div>
                                    </div>
                                    @if ($errors->has('due_date'))
                                        <span class="text-danger">{{ $errors->first('due_date') }}</span>
                                    @endif
                                </div>
                                <div class="filterbutton">
                                    <input type="submit" class="blue-btn" id="save_and_assign" name="save_and_assign" value="Save and Assign">
                                </div>
                            </div>
                            <!-- recurring Modal -->
                            <div id="recurring_modal" class="modal">

                                <!-- Modal content -->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="modal_close" onclick="close_modal('cancel')">&times;</span>
                                        <div class="category_title">Recurrence Settings</div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="recurring_div">
                                            <div>
                                            <div class="recurrence_heading">
                                                Recurrence Pattern
                                            </div>
                                            </div>
                                            <div class="first_div">
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="daily_recur recurring_type" onclick="show_data('daily')" value=1> Daily</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="weekly_recur recurring_type" onclick="show_data('weekly')" value=2>Weekly</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="monthly_recur recurring_type" onclick="show_data('monthly')" value=3>Monthly</div>
                                                <div class="inputfieldsrec"><input type="radio" name="recurring_type" class="yearly_recur recurring_type" onclick="show_data('yearly')" value=4>Yearly</div>
                                                <div class="weekly_data" style="display:none">
                                                    Recur every <select name="weekly_week" id="recur" class="weekly_week">{{$i=1}} @for($i==1; $i<=7;  $i++) <option value={{$i}}>{{$i}}</option> @endfor</select> Week(s) on <br>
                                                    <fieldset id="weeklyday_array"><div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='1'> Sunday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='2'> Monday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='3'> Tuesday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='4'> Wednesday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='5'> Thursday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='6'> Friday </div>
                                                    <div class="weekdayscheckboxes"><input type="checkbox" name="weekly_day[]" class="weekly_day" value='7'> Saturday </div></fieldset>
                                                </div>
                                                <div class="monthly_data" style="display:none">
                                                    <input type="radio" name="monthly_pattern" class="monthly_pattern" value="1">  The <select name="month_day" id="month_day" class="month_day"><option value="1">First</option><option value="2">Second</option><option value="3">Third</option><option value="4">Forth</option></select> 
                                                    <select name="month_week" id="month_week" class="month_week"><option value=1>Sunday</option><option value=2>Monday</option><option value=3>Tuesday</option><option value=4>Wednesday</option><option value=5>Thursday</option><option value=6>Friday</option><option value=7>Saturday</option></select> of every 
                                                    <select name="month_month" id="month_month" class="month_month">{{$i=1}} @for($i==1; $i<=12;  $i++) <option value={{$i}}>{{$i}}</option> @endfor</select> month(s) <br><br>
                                                    <input type="radio" name="monthly_pattern" class="monthly_pattern" value="2" >  Day <select name="month_day_sec" id="month_day" class="month_day_sec">{{$i=1}} @for($i==1; $i<=30;  $i++) <option value="{{$i}}">{{$i}}</option> @endfor</select> of every 
                                                    <select name="month_month_sec" id="month_month" class="month_month_sec">{{$i=1}} @for($i==1; $i<=12;  $i++) <option value={{$i}}>{{$i}}</option> @endfor</select> month(s)
                                                </div>
                                                <div class="yearly_data" style="display:none">
                                                <input type="radio" name="yearly_pattern" class="yearly_pattern" value="1"> The <select name="year_day" id="year_day" class="year_day"><option value="1">First</option><option value="2">Second</option><option value="3">Third</option><option value="4">Forth</option> </select>
                                                    <select name="year_week" id="year_week" class="year_week"><option value=1>Sunday</option><option value=2>Monday</option><option value=3>Tuesday</option><option value=4>Wednesday</option><option value=5>Thursday</option><option value=6>Friday</option><option value=7>Saturday</option></select> of 
                                                    <select name="year_month" id="year_month" class="year_month" > 
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
                                                <div class="daily_data" style="display:none">
                                                    Every<select name="daily_day" class="daily_day">{{$i=1}} @for($i==1; $i<=30;  $i++) <option value="{{$i}}">{{$i}}</option> @endfor</select> Day(s)
                                                </div>
                                                <div class="recur_error"> </div>
                                            </div>
                                            <br style="clear:both;"/>
                                            <div class="recurrence_heading">
                                                Range of Recurrence
                                            </div>
                                            <div class="second_div">
                                                <div class="datestartrec">Start Date <input type="date" name="start_date" id="start_recur" min='' onchange="set_end()"></div>
                                                <div class="datestartrec">End Date <input type="date" name="end_date" id="end_recur" min='' ></div>
                                                <div class="date_error"> </div>
                                            </div>
                                            <br style="clear:both;"/>
                                            <div class="button_div"> 
                                                <input type="button" name="ok" value="OK" class="btn btn-success category-btn save_recurring" onclick="close_modal('ok')">
                                                <input type="button" class="btn btn-danger cancel_modal" id="cancel_modal" value="Cancel" onclick="close_modal('cancel')">
                                                <!-- <input type="button" name="remove_recur" value="Remove Recurrence" class="btn btn-warning "> -->

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

@endsection
@section('footer_scripts')
    <script type="text/javascript">
        

        $('#save_and_assign' ).click(function(){
            if($('#createAction').valid()){
                $('.due_date').attr('disabled', false);
                $('#createAction input').removeClass('action_change');
                $('#createAction select').removeClass('action_change');
                $('#createAction textarea').removeClass('action_change');
                $('#createAction section').removeClass('action_change');
                $('#actiontTitle').removeClass('action_change');
                $('#actionDescription').removeClass('action_change');
            }
        });

        // add class if any input is changed 
        $('#createAction input, #createAction select, #createAction textarea, #createAction section, #actionDescription, #actiontTitle').on('keyup change keypress', function(){
            if($(this).val() != ''){
                $(this).addClass('action_change');
            }else{
                $(this).removeClass('action_change');
            }
        });

        // alert before leaving create template form
        $(window).on('beforeunload', function(){
            if($('#createAction input').hasClass('action_change') || $('#createAction select').hasClass('action_change') || 
            $('#createAction section').hasClass('action_change') || $('#createAction textarea').hasClass('action_change') ||
            $('#actiontTitle').hasClass('action_change') || $('#actionDescription').hasClass('action_change')){
                var c=confirm();
                if(c){
                return true;
                }
                else
                return false;
            }
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js "></script> --}}
    {{-- <script src="{{asset('assets/action/js/bootstrap.min.js')}}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('assets/action/js/fontawesome.min.js')}}" type="text/javascript"></script> --}}
    {{-- <script src="{{asset('assets/action/js/jquery.main.js')}}" type="text/javascript"></script> --}}
    <script src="{{asset('assets/js/business_unit.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>
    <script src="{{asset('assets/action/js/action.js')}}" type="text/javascript"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>
                $("#actionAsigne").select2();
        </script>
@endsection
