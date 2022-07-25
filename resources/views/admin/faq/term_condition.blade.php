@extends('admin.layout.app')
@section('title'){{ 'Terms and Conditions' }}@endsection
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
                    <span>Terms and Conditions</span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> Terms and Conditions </span>
                        </div>
                    </div>
                    @if(!empty($terms_conditions))
                    <div class="terms_file">
                        File : <a href="{{url('/uploads/'.$terms_conditions->file.'')}}" target="_blank">Terms and conditions </a>
                    </div>
                    @endif
                    {{ Form::open(array('action' => 'FaqController@add_term_condition', 'method' => 'post', 'id' => 'add_term_condition', 'enctype' => 'multipart/form-data'))}}
                        <input type="hidden" name="id" value="{{!empty($terms_conditions)?$terms_conditions->id:''}}" >
                        <div class="terms_div">
                            <input type="file" name="term_condition" accept=".pdf">
                            <div class="">
                                <input type = "submit" name="save" value="{{!empty($terms_conditions)?'Change':'Add'}}" class="btn sbold green">
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