@extends('admin.layout.app')
@section('title','Edit Action')
@section('header_css')
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous"> -->
    <!-- <link href="{{ asset('assets/action/css/fontawesome.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('assets/action/css/fontawesome-all.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/action/css/main.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.css" rel="stylesheet"/>
    <link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="{{asset('assets/action/js/jquery.min.js')}}" type="text/javascript"></script>
@endsection
@section('content')
<div class="page-content-wrapper wrapper">
    <div class="page-content page-wrap">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>	
                <strong>{{ $message }}</strong>
        </div>
        @endif
        
        <div class="adinfull">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="">View Action</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="editlogic">
                    <input type="hidden" id="site_url" value="{{asset('assets/action/images/')}}">
                    
                        <div class="reportsetup actionheadingtext">@if($actionCreatedUser!=""){{ isset($actionCreatedUser->vc_fname) ? $actionCreatedUser->vc_fname : '' }} {{ isset($actionCreatedUser->vc_lname) ? $actionCreatedUser->vc_lname : '' }} @endif  created the action
                            @php 
                                $createdAt = strtotime($actionData->created_at);
                                date_default_timezone_set('Asia/Kolkata');
                                $actionCreatedAt = date('d M Y, g:i  A', $createdAt);
                            @endphp 
                            <span class="datetimeaction">{{ isset($actionCreatedAt) ? $actionCreatedAt : '' }}</span>
                        </div>
                        <input type="hidden" name="action_id" id="userActionId" value="{{$actionData->id}}">
                    <div class="chatsection" id="mainChatSection"> 
                        <div class="loadMediaChat">
                            @foreach($chatInfo as $index => $value)
                                @php $document = $chatInfo[$index]['document'];

                                    $message = $document['message_text'];
                                    $mediaUrl = $document['media_url'];
                                    $url = strtok($mediaUrl, '?');
                                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                                    str_replace('%7D','',$ext);
                                    $parsePath = parse_url($mediaUrl, PHP_URL_PATH);
                                    $prePath = basename($parsePath);
                                    $mediaFileName = str_replace('chat_media%2F','',$prePath);
                                    $type = $document['type'];
                                    $mediaType = $document['media_type'];
                                    $sentAt = $document['sent_at'];
                                    $sentDateTime = date('d M, g:i  A', $sentAt/1000);
                                @endphp
                                @if($type != 3)
                                        @php 
                                            $mediaClass = 'innerLeftChat'; 
                                            $centerChatClass = 'rightChat';
                                        @endphp

                                        @if( ($document['sender_role_id']!=0 || $document['sender_role_id']==0) && $document['sender_role_id']!=null 
                                            && ($document['sender_role_id']== auth()->user()->users_details->i_ref_role_id)
                                                && $type!=3 ) 
                                                @php 
                                                $mediaClass = 'innerRightChat'; 
                                                $centerChatClass = 'rightChat';
                                            @endphp
                                        @elseif( !empty($document['sender_id']) && $document['sender_id']== auth()->user()->id && $type != 3)
                                            @php 
                                                $mediaClass = 'innerRightChat'; 
                                                $centerChatClass = 'rightChat';
                                            @endphp
                                        @endif

                                    @else 
                                        @php 
                                            $mediaClass = 'innerChatAction';
                                            $centerChatClass = 'centerChat';
                                        @endphp
                                    @endif


                                    @if( ($document['sender_role_id']!=0 || $document['sender_role_id']==0) && $document['sender_role_id']!=null 
            && ($document['sender_role_id']== auth()->user()->users_details->i_ref_role_id)
                && $type!=3 ) 
                <div class="{{$centerChatClass}}">
        @if($type != 3)
            <div class="namechatperson">
                 {{ isset($document['sender_name']) ? $document['sender_name'] : '' }}
            </div>
        @endif

        @elseif( !empty($document['sender_id']) && $document['sender_id']== auth()->user()->id && $type != 3)
        <div class="{{$centerChatClass}}">
        @if($type != 3)
            <div class="namechatperson">
                 {{ isset($document['sender_name']) ? $document['sender_name'] : '' }}
            </div>
        @endif 
    @else
    <div class="leftChat">
        <div class="namechatperson">
            {{ isset($document['sender_name']) ? $document['sender_name'] : '' }}
        </div>
                                @endif
                                    <div class="{{$mediaClass}} @if($mediaUrl !='') mediaChatBg @endif" >
                                            {{ isset($message) ? $message : '' }}
                                            @if($type == 2 && $mediaUrl !="") 
                                                @if($ext == 'jpg'  || $ext == 'jpg%7D'  || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $mediaType == 1)
                                                    <img src="{{$mediaUrl}}" width="300px;" height="25%" >
                                                @elseif($ext =='pdf' || $mediaType == 3)
                                                <div class="mediaSection">
                                                    <ul>
                                                        <li>
                                                            <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/pdf.png')}}" alt="pdf-media" class="mediaFile"></a>
                                                        </li>
                                                        <li>
                                                            <p class="mediaName">{{$mediaFileName}}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @elseif($ext =='mp4' || $mediaType == 4)
                                                <div class="mediaSection">
                                                    <ul>
                                                        <li>
                                                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/mp4.png')}}" alt="mp4-media" class="mediaFile"></a>
                                                        </li>
                                                        <li>
                                                            <p class="mediaName">{{$mediaFileName}}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @elseif($ext== 'mp3' || $mediaType == 2)
                                                <div class="mediaSection">
                                                    <ul>
                                                        <li>
                                                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/mp3.png')}}" alt="mp3-media" class="mediaFile"></a>
                                                        </li>
                                                        <li>
                                                            <p  class="mediaName">{{$mediaFileName}}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @elseif($ext =='doc' || $ext == 'docx' || $ext=='docm' || $ext =='csv' || $mediaType == 5)
                                                <div class="mediaSection">
                                                    <ul>
                                                        <li>
                                                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/doc.png')}}" alt="doc-media" class="mediaFile"></a>
                                                        </li>
                                                        <li>
                                                            <p  class="mediaName">{{$mediaFileName}}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @else
                                                <div class="mediaSection">
                                                    <ul>
                                                        <li>
                                                        <a href="{{$mediaUrl}}" target="_blank"><img src="{{ asset('assets/action/images/file.png')}}" alt="file-media" class="mediaFile"></a>
                                                        </li>
                                                        <li>
                                                            <p  class="mediaName">{{$mediaFileName}}</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif
                                            @endif
                                            <span class="timingChat @if($mediaUrl !='') mediaTime @endif">
                                                {{ isset($sentDateTime) ? $sentDateTime : '' }}
                                            </span>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            <div class="dynamicAppendChat"></div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="editlogic">
                        <input type="hidden" name="action_id" value="{{$actionData->id}}">
                            <div class="chatheading">
                                <section id="actiontTitle" >Title :- {{ isset($actionData->title) ? $actionData->title : '' }}</section></div>
                         <div class="chatheading2"><section id="actionDescription"><strong>Description :- </strong>{{ isset($actionData->descriptions) ? $actionData->descriptions : '' }}</section></div>
                            <div class="chatheading2">
                            <strong> Recurring Action :- </strong> @if ($actionData->reocurring_actions > 0) Yes @else No @endif 
                            </div>
                            <div class="actionfiter">
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong> Status :- </strong> {{ $actionData->status_name != '' ? $actionData->status_name : '-' }}
                                    </div>
                                    @if($actionData->status == 4)
                                    <div class="filterwidth">
                                    <strong> Closed By :- </strong>{{ isset($actionClosedUser['full_name']) ? $actionClosedUser['full_name'] : '' }}  
                                    </div>
                                    @endif
                                </div>
                                <br><br>
                                <div>
                                <div class="deatilsaction">
                                    Details
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong> Business Unit :- </strong>{{!empty($actionData->business_unit)? $actionData->business_unit->vc_short_name !='' ? $actionData->business_unit->vc_short_name : '-': '-' }}
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong> Department :- </strong>{{!empty($actionData->department)? $actionData->department->vc_name !='' ? $actionData->department->vc_name : '-': '-' }}
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong> Project :- </strong>{{!empty($actionData->project)? $actionData->project->vc_name !='' ? $actionData->project->vc_name : '-': '-' }}
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong> Assign To :- </strong> {{ isset($actionassignedUser['full_name']) ? $actionassignedUser['full_name'] : '' }}
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong>Priority :- </strong> {{ $actionData->priority_name != '' ? $actionData->priority_name: '-' }}
                                    </div>
                                </div>
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    @php $newDueDate = !empty($actionData->due_date)? date("m/d/Y", strtotime($actionData->due_date)) : ''; @endphp
                                      <div> <strong>Due Date :- </strong>{{ $newDueDate != '' ? $newDueDate : '-' }}</div>
                                    </div>
                                </div>
                                @php
                                    $createdDate = date('d M Y', $createdAt);
                                    $createdTime = date('g:i  A', $createdAt);
                                @endphp
                                @if($actionCreatedUser != "")
                                <div class="actionfitertwo">
                                <strong>Created by :- </strong>{{ isset($actionCreatedUser['full_name']) ? $actionCreatedUser['full_name'] : '' }} ,
                                    {{isset($createdDate) ? $createdDate : ''}} at {{isset($createdTime) ? $createdTime : '-'}}
                                </div>
                                @endif 
                                <div class="actionfitertwo">
                                    <div class="filterwidth">
                                    <strong>Comment :- </strong>{{isset($actionData->comments) ? $actionData->comments : '-'}}
                                    </div>
                                </div>
                                <div class="deatilsaction">
                                    Evidence :- <br />
                                    @if(!empty($actionData->evidences))
                                    <?php $evidences = $actionData->evidences; ?>
                                    <x-evidences :evidences="$evidences"/>
                                    @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js "></script>
    <script src="{{asset('assets/action/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/action/js/fontawesome.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/action/js/jquery.main.js')}}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/0.6.5/datepicker.min.js"></script>  
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
    <script src="{{asset('assets/action/js/action.js')}}" type="text/javascript"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
