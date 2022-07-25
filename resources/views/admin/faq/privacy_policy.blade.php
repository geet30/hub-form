@extends('admin.layout.app')
@section('title'){{ 'Privacy Policy' }}@endsection
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
                    <span>Privacy Policy</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> Privacy Policy </span>
                        </div>
                    </div>
                    @if(!empty($privacy_policy))
                    <div class="policy_file">
                        File : <a href="{{url('/uploads/'.$privacy_policy->file.'')}}" target="_blank">Privacy Policy </a>
                    </div>
                    @endif
                    {{ Form::open(array('action' => 'FaqController@add_privacy_policy', 'method' => 'post', 'id' => 'add_privacy_policy', 'enctype' => 'multipart/form-data'))}}
                        <input type="hidden" name="id" value="{{!empty($privacy_policy)?$privacy_policy->id:''}}" >
                        <div class="policy_div">
                            <input type="file" name="privacy_policy" accept=".pdf">
                            <div class="">
                                <input type = "submit" name="save" value="{{!empty($privacy_policy)?'Change':'Add'}}" class="btn sbold green">
                            </div>
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
@endsection