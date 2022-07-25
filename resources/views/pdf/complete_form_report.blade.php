<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Document</title>
	{{-- <link href="{{ public_path('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/> --}}
	<style>
		body {
			width : 80%;
			margin: auto;
			font-family: DejaVu Sans;
		}
		.box-main{
			width: 100%;
			font-size: 12px !important;
		}
	</style>
</head>

<body>
    <div class="box-main">
		<div style="width: 100%">
			<div style=" width: auto; padding: 15px; border: 1px solid #e4e4e4; margin-bottom: 15px; display: inline-block">
				{{isset($detail->Template)?$detail->Template->template_name:'-'}}
			</div>
			<div
				style=" width: auto; padding: 15px; border: 1px solid #e4e4e4; margin-bottom: 15px; margin-left: 15px; display: inline-block">
				{{isset($detail->form_id)?$detail->form_id:'-'}}
			</div>
		</div>
		
		{{-- Overview --}}

        <strong style="width: 100%; margin: 0; margin-bottom: 10px;">Overview</strong>
        <div style=" width: 100%;">
            @php $compnyProfilePic = $compnyProfilePic @endphp
            @if(!empty($compnyProfilePic))
            @php $name = P2B_BASE_URL.'/uploads/company_logos/'.$compnyProfilePic; @endphp
            <img src={{$name}} style=" width: 50px;">
            @else
            <img src="{{ public_path('assets/edit_form/images/defaultpic.jpeg')}}" style=" width: 50px;">
            @endif
        </div>
        <div style="width: 100%;  border: 1px solid #e4e4e4; margin-top: 20px; display: inline-block;">
            <div style="width:32%; padding:5px 0;  display: inline-block;">
                <div style="width: 100%; margin-top:10px; text-align: center; display: block;color: #8e8e8e; ">Form
                    Score</div>
                <div style="width: 100%; text-align: center; display: block;">
                    {{$data['form_score_percent']}}%</div>
            </div>
            <div style="width:32%; display: inline-block;">
                <div style="width: 100%; margin-top:10px; text-align: center; display: block;  color: #8e8e8e; " >
                    Created Action(s)</div>
                <div style="width: 100%; text-align: center; display: block; ">
                    {{$data['total_actions'] ?? '-'}}</div>
            </div>
            <div style="width:32%; padding:5px 0; display: inline-block;">
                <div style="width: 100%;margin-top:10px; text-align: center; display: block; color: #8e8e8e; ">
                    Items Needing Review</div>
                <div style="width: 100%; text-align: center; display: block;">
                    {{$data['failled_items'] ?? '-'}}</div>
            </div>
        </div>

        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px; display: inline-block;">Company:</div>
            <div style="display: inline-block;">{{ $detail->company_name ?? '-' }}</div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px; display: inline-block;">Date:</div>
            <div style=" display: inline-block;">{{$detail->created ?? '-' }}</div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px;  display: inline-block;">Completed By:</div>
            <div style=" display: inline-block;">{{ $detail->user_name ?? '-' }}</div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px;  display: inline-block;">Location:</div>
            <div style=" display: inline-block;">{{ $detail->location_name ?? '-' }}</div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px;  display: inline-block;">Business Unit:</div>
            <div style=" display: inline-block;">
                {{ !empty($detail->business)?!empty($detail->business->vc_short_name)?$detail->business->vc_short_name:'-': '-' }}
            </div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px;  display: inline-block;">Department:</div>
            <div style=" display: inline-block;">
                {{ !empty($detail->dept_data)?!empty($detail->dept_data->vc_name)?$detail->dept_data->vc_name:'-':'-' }}
            </div>
        </div>
        <div style=" width: 100%; padding: 15px 0; border-bottom: 1px solid #e4e4e4;">
            <div style=" width: auto; margin-right: 15px;  display: inline-block;">Project:</div>
            <div style=" display: inline-block;">
                {{ !empty($detail->project_data)?!empty($detail->project_data->vc_name)?$detail->project_data->vc_name: '-' : '-' }}
            </div>
		</div>

		{{-- scope and methodology --}}

		@if(in_array('scope_method', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
			<div style="display: inline-block; width:100%; padding: 15px 0 15px;">
				<strong style="width: 100%; margin: 0; display: inline-block; ">
					{{!empty($detail->scopeMethodology)? $detail->scopeMethodology[0]->snm_name ? $detail->scopeMethodology[0]->snm_name : '-' : '-'}}
				</strong>
			</div>
			@forelse($detail->scopeMethodology as $scope)
				<div style=" background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f5f5f5 ;margin-top: 10px; width: 100%;  ">
					{{$scope->snm_data}}
				</div>
				@empty
				<div style=" background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f5f5f5; width: 100%;">
					Not added yet.
				</div>
			@endforelse
		@endif
		<div class="clearfix"> </div>
		{{-- failed items --}}

		@if(in_array('Failed_Items', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
			<div style="display: inline-block; width:100%; padding: 15px 0 15px;">
				<strong style=" display: inline-block; width:50%">Items Needing Review </strong>
				<div style="float: right; width: auto; padding: 5px 30px; background: #ffd5d5; color: #af4645; border-radius: 5px; text-align:right;display: inline-block;">
					{{$data['failled_items']}}
				</div>
			</div>
		@forelse($data['failled_items_list'] as $failled_item)
			<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%; ">
				<div style="font-weight: bold; color: #29c0d4; padding: 10px;padding-top: 0;  ">
					{{$failled_item['section_name']}}
				</div>
				<div style="width:100%;padding-left: 10px; ">
					{{$failled_item['text']}}
				</div>
				<div style=" width: auto; padding: 5px 30px; background: #ffd5d5; color: #af4645; margin-top: 15px; border-radius: 5px; ">
					@if($failled_item['question_type'] == 5 && $failled_item['answers']['answer'] == 0)
						{{'NO'}}
					@endif
				</div>
			</div>
		@empty
		<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%; ">
			Not added yet.
		</div>
		@endforelse 
		@endif

		{{-- actions --}}

		@if(in_array('Actions', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
			<div style="display: inline-block; width:100%; padding: 15px 0 15px;">
				<strong style="display: inline-block; width: 50%">Action</strong>
				<div style="float: right; width: auto; padding: 5px 30px; background: #e7f5f7; color: #3ac5d7; border-radius: 5px;  text-align:right;display: inline-block;">
					{{$data['total_actions']}}
				</div>
			</div>
			@forelse($data['actions_list'] as $action_list)
			<?php //print_r($action_list);die; ?>
			@if(count($action_list['question']['actions']) > 0)
-				@foreach ($action_list['question']['actions'] as $actions_data)
						
					<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%;">
						<div style="width:100%; ">{{$actions_data['title']}}</div><br>
						<div style=" width: 100%; ">
							@if ($actions_data['priority'] == 1)
-								{{$actions_data['user']['vc_fname'].' '.$actions_data['user']['vc_lname']}} has created LOW Priority Action for {{$actions_data['assignee_user']['vc_fname'].' '.$actions_data['assignee_user']['vc_lname']}}
-							@elseif ($actions_data['priority'] == 2)
-								{{$actions_data['user']['vc_fname'].' '.$actions_data['user']['vc_lname']}} has created MEDIUM Priority Action for {{$actions_data['assignee_user']['vc_fname'].' '.$actions_data['assignee_user']['vc_lname']}}
							@else
								{{$actions_data['user']['vc_fname'].' '.$actions_data['user']['vc_lname']}} has created HIGH Priority Action for {{$actions_data['assignee_user']['vc_fname'].' '.$actions_data['assignee_user']['vc_lname']}}
							@endif
						</div>
						<div style=" width: 100%;">
						<div style=" width: 25%; padding: 5px 30px; background: #5b99ff; color: #fff; margin-top: 15px; border-radius: 5px; ">
							@if ($actions_data['status'] == 1)
								@php $status = 'Pending' @endphp
							@elseif ($actions_data['status'] == 2)
								@php $status = 'In-Progress' @endphp
							@elseif ($actions_data['status'] == 3)
								@php $status = 'Completed' @endphp
							@elseif ($actions_data['status'] == 5)
								@php $status = 'Rejected' @endphp
							@else
								@php $status = 'Rejected' @endphp
							@endif
							{{ $status }}
						</div>
						<div style="margin-top: 20px; margin-left: 10px; width: 50%">{{ \Carbon\Carbon::parse($actions_data['created_at'])->format('j M, Y') }}</div><br>
						</div>
						<div style="font-weight: bold; color: #29c0d4;  ">{{$action_list['section_name']}}</div>
						<div style=" width: 100%;padding: 10px 0; color: #29c0d4;">{{$action_list['question']['text']}}</div>
					</div>
					@endforeach
				@else
					<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%; ">
						Not added yet.
					</div>
				@endif
			@empty
				<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%;">
					Not added yet.
				</div>
			@endforelse  
		@endif

		{{-- sections --}}
		<div style="display: inline-block; width:100%; padding: 15px 0 20px;">
			<div style="float: right; text-align: right; display: inline-block;">
				@if(in_array('Marks', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
					<b>Marks</b><br>
					@php $t_per = $data['section_wise_score']/$data['total_questions']*100 @endphp
					<b>{{$data['section_wise_score']}}/{{$data['total_questions']}} (<?php echo round($t_per,1); ?>%)</b>
				@endif
			</div>
		</div>
		@forelse ($detail->sections as $sec_key => $sections)
		<div style="display: inline-block; width:100%; padding: 15px 0 20px;">
			<strong style="display: inline-block; width: 50%">{{ $sections->name ?? '-'}}</strong>
		</div>
		@if(count($sections->questions) > 0)
			@foreach ($sections->questions as $ques_key => $question)
			<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 100%;  ">
				<div style=" width: 5%; display: inline-block;">{{$ques_key + 1}}</div>
					<div style=" width: 80%;border-left: 1px solid #e4e4e4; border-right: 1px solid #e4e4e4; display: inline-block; margin-top: 20px;">
						<div style=" width: 100%; padding: 0 10px;  ">{{$question->text ?? '-'}}</div>
						@if(!empty($question->answers))
						<!-- for check text -->
						@if(in_array($question->question_type,array(config('constants.question_type.multi_choice'),config('constants.question_type.text'),config('constants.question_type.date'),config('constants.question_type.number'),config('constants.question_type.location'))))
							<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px;  ">
								{{$question->answers->answer}}
							</div>
						
						<!-- for check option -->
						@elseif($question->question_type == config('constants.question_type.two_option'))
							@if($question->answers->answer ==1)
							<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px; " class="bgGreen">
								Yes
							</div>
							@elseif($question->answers->answer ==0)
							<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px;" class="bgDanger">
								No
							</div>
							@endif

						<!-- for check signature -->
						@elseif(in_array($question->question_type, array(config('constants.question_type.signature'))))
						<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px; ">
							@if(!empty($question->answers->answer))
							<img src="{{(File::exists(public_path('uploads/' . $question->answers->answer.''))) ? public_path('uploads/'.$question->answers->answer.''): ''}}"
								class="img-responsive "
								alt="{{$question->answers->answer}}" style="width: 100px; height:80px " />
							@endif
						</div>
						<!-- for check multiple select -->
						@elseif(in_array($question->question_type,array(config('constants.question_type.multi_select'))))
							@if($question->answers->type_option)
							<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px;">
								{{ implode(', ',$question->answers->type_option)}}
							</div>
							@endif

							<!-- for dropdown -->
							@elseif($question->question_type == config('constants.question_type.dropdown'))
								@if(in_array('Color_Coded', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
								<div style="  width: 80%; padding: 0 10px; border: 1px solid #e4e4e4; padding: 14px; margin-left: 10px; margin-top: 10px; background: #{{$question->answers->dropdown_color}} ; color:black">
									{{$question->answers->answer}}
								</div>
								@endif
							@endif
						@endif
						@if(in_array('Media_Summery', $data['report_filter']) || in_array('app_completed_form', $data['report_filter']) )
							<div style=" width: 100%;  display: inline-block; margin-top: 10px; margin-left: 10px;">
								@php $arr = $question->answers['evidences'] @endphp
								@if(isset($arr[0]))
									@foreach ($arr as $evidences)
										@if($evidences->file_name)
											@switch($evidences->file_type)
												@case(App\Models\Evidence::TYPE_IMAGE)
													<a href="{{ url('uploads/'. $evidences->file_name) }}" target="_blank" style="text-decoration: none;  display: inline-block;">
														<img src="{{ public_path('uploads/'. $evidences->file_name) }}" width="25px" height="25px;"/>
														{{-- <img src="{{ public_path('assets/images/image-solid.png') }}" width="15px" height="15px;"/> --}}
													</a>
													@break
												@case(App\Models\Evidence::TYPE_AUDIO)
													<a href="{{ url('uploads/'. $evidences->file_name) }}" target="_blank" style="text-decoration: none;  display: inline-block;">
														<img src="{{ public_path('assets/images/file-audio-solid.png') }}" width="25px" height="25px;"/>
													</a>
													@break
												@case(App\Models\Evidence::TYPE_VIDEO)
													<a href="{{ url('uploads/'. $evidences->file_name) }}" target="_blank" style="text-decoration: none;  display: inline-block;">	
														<img src="{{ public_path('assets/images/video-slash-solid.png') }}" width="25px" height="25px;"/>
													</a>
													@break
												@case(App\Models\Evidence::TYPE_PDF)
													<a href="{{ url('uploads/'. $evidences->file_name) }}" target="_blank" style="text-decoration: none;  display: inline-block;">	
														<img src="{{ public_path('assets/images/file-pdf-solid.png') }}" width="25px" height="25px;"/>
													</a>
													@break
												@default
													<a href="https://docs.google.com/gview?url={{ url('uploads/'. $evidences->file_name) }}" target="_blank" style="text-decoration: none;  display: inline-block;">
														<img src="{{ public_path('assets/images/file-word-solid.png') }}" width="25px" height="25px;"/>
													</a>
											@endswitch
										@endif
									@endforeach
								@endif
							</div>
						@endif
					</div>
					<div style=" width: 10%; padding: 0 10px;  display: inline-block;">
						@php $mark = '<div style="display: inline-block"> 1 </div>' @endphp
						@if($question->question_type == 5 && $question->answers['answer'] == 0 )
							@php $mark = '<div style="display: inline-block"> 0 </div>' @endphp
						@endif
						@php $cross_icon = public_path('assets/images/cross-red.png');
						 $checked_icon = public_path('assets/images/check-solid.png');
						@endphp
						@if($question->question_type == 6 && $question->answers['answer'] == 0 )
							@php $mark = ' <img src="'.$cross_icon.'" width="20px" height="20px;" style=" display: inline-block;"/> ' @endphp
						@elseif($question->question_type == 6 && $question->answers['answer'] == 1 )
							@php $mark = '<img src="'.$checked_icon.'" width="20px" height="20px;" style=" display: inline-block;"/> ' @endphp
						@endif

						@if($question->question_type == 7 && empty($question->answers['type_option']) )
							@php $mark = ' <img src="'.$cross_icon.'" width="20px" height="20px;" style=" display: inline-block;"/> ' @endphp
						@elseif($question->question_type == 7 && !empty($question->answers['type_option']) )
							@php $mark = ' <img src="'.$checked_icon.'" width="20px" height="20px;" style=" display: inline-block;"/> ' @endphp
						@endif

						@if(!in_array($question->question_type, [5,6,7]) && empty($question->answers['answer']) )
							@php $mark = '<div style="display: inline-block"> 0 </div>' @endphp
						@endif

							{!! $mark !!}
					</div>
				</div>
			@endforeach
		@endif
		@empty
			<strong style="display: inline-block; width:100%; padding: 15px 0 20px;">Section</strong>
			<div style="background: #fff; border-radius: 5px; border: 1px solid #EEEEEE; padding: 10px; color: #21252a; background: #f7faff; width: 99%; display: inline-block;">
				No Section added yet.
			</div>
		@endforelse
    </div>
</body>

</html>
