@extends('admin.layout.app')
@section('title'){{ 'Email Setting' }}@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
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
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Email Setting</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> Email Setting </span>
                        </div>
                    </div>
                    {{ Form::open(array('action' => 'FaqController@email_setting', 'method' => 'post', 'id' => 'email_setting', 'enctype' => 'multipart/form-data'))}}
                        <input type="hidden" name="id" value="{{!empty($email_data)?$email_data->id:''}}" >
                        <div class="col-md-12">
                                <label class="col-md-1"> Email </label>
                            <div class="col-md-6"> 
                                <input type="text" name="email" class="form-control setting_email" value="{{!empty($email_data)?$email_data->email: ''}}">
                                <input type="hidden" name="status" class="form-control" value="{{!empty($email_data)?$email_data->status: 1}}">
                            </div>
                        </div>
                            <div class="col-md-2 email_save">
                                <input type = "submit" name="save" value="{{!empty($email_data)?'Update':'Add'}}" class="btn sbold green">
                            </div>
                        
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
    <script src="{{asset('assets/js/faq.js')}}"></script>
    <script> 
        $(document).ready(function(){
            $('#email_setting').validate({
                rules:{
                    email:{
                        required: true,
                        email: true
                    }
                }
            });
        })
    </script>
@endsection