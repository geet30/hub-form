@extends('admin.layout.app')
@section('title','Edit Form')
@section('header_css')
{{-- <link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet"> --}}
<link href="{{ asset('assets/action/css/main.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content" >
        <div class="adinfull">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <i class="fa fa-home"></i>
                        <a href="{{ route('login') }}">{{ trans('label.dashboard') }}</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('completed_forms') }}">{{ trans('Completed Forms') }}</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    <li>
                        <span>Chat </span>
                    </li>
                </ul>
                <a href="{{ route('edit_form', ['id' => $completed_form->id_decrypted]) }}" class="btn btn-success green back-btn">
                    <span class="username username-hide-on-mobile"> Back </span>
                </a>
            </div>
            <!-- response messages -->
            @include('partials.messages')
            <div class="row">
                <div class="col-md-8">
                    <div class="editlogic">
                        <form id="chatForm">
                            @csrf
                            <input type="hidden" id="site_url" value="{{asset('assets/action/images/')}}">
                            <div class="chatsection" id="mainChatSection">
                                <div class="loadMediaChat">
                                    @include('partials.chats_inbox')
                                </div>
                            <div class="dynamicAppendChat"></div>
                            </div>
                            <div class="SendChat">
                                <textarea class="inputsectionChat" id="messageTextarea" name="message_text"
                                    placeholder="Type Your Message Here" required></textarea>
                                <div class="clipImage">
                                    <img id="updloadImage" src="{{asset('assets/action/images/clip.png')}}">
                                </div>
                                <div class="dynamicMediaPreview">
                                    <img width="200px" height="200px" id="preview_image" src="" style="display:none" />
                                    <a href="javascript:removeMediaPreview()" style="text-decoration:none;display:none"
                                        class="removePreviewDynamic">
                                        <i class="glyphicon glyphicon-trash "></i> Remove
                                    </a>&nbsp;&nbsp;
                                </div>
                                <input type="hidden" id="current_time" value="" name="sent_at">
                                <input type="hidden" name="chat_room_id" value="{{ $chatRoom->id }}">
                                <input type="hidden" name="action_id" value="{{ $action->id }}">
                                <input type="hidden" name="receiver_id" value="{{ $action->assined_user_id }}">
                                <input type="hidden" class="mediaExt" name="file_ext" value="">
                                <input type="file" id="file" style="display: none" />
                                <input type="hidden" name="file_name" id="file_name" />
                                <i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw"
                                    style="position: absolute;left: 44%;top: 21%;display: none;background:none;color: #5aa5ad;"></i>
                                <div class="sendChatBtn">
                                    <img id="enableEditChat" src="{{asset('assets/action/images/send.png')}}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="editlogic chat-sidebar">
                        <form action="{{ route('update.form.chat', $action->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <p> <a href="{{ route('show', ['id' => $completed_form->id_decrypted]) }}" target="_blank">{{ $completed_form->form_id }}</a></p>
                            <p>Ques : {{ $question->text }}</p>
                            <?php  $actionObj = new \App\Models\Action(); ?>
                            <div class="filterwidth">
                                <label>Status</label>
                                <select name="status" class="status" id="status">
                                    <option value="">Select Status</option>
                                    @foreach ($actionObj->statusArray as $key => $value)
                                    <option value="{{ $key }}" @if($action->status == $key) selected @endif>{{ $value }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="filterwidth">
                                <div class="detail_sec">Details</div>
                                <div class="form-group">
                                    <div class="form-control">
                                        Site : {{ $action->project->vc_name }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control">
                                        Assigned by : {{ $action->user->full_name }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control">
                                        Due : {{ $action->due_date }}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-control created_by">
                                        Created by : {{ $action->user->full_name }} -
                                        {{ $action->created_at->format("d M Y, H:i A") }}
                                    </div>
                                </div>
                                <div class="filterbutton">
                                    <button type="submit" class="blue-btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('footer_scripts')
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
<script src="{{asset('assets/js/completed_chat.js')}}"></script>
@endsection
