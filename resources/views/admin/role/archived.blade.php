@extends('admin.layout.app')
@section('title') {{ trans('label.archived_role') }} @endsection
@section('header_css') 
<link href="{{asset('assets/jqxtree/css/jqx.base.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/jqxtree/css/jqx.bootstrap.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/jqxtree/css/jqx.light.css')}}" rel="stylesheet"/>
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        @include('partials.messages')
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>{{ trans('label.archived_role') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-briefcase font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.archived_role') }} </span>
                        </div>
                    </div>
                    
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>

                        {{-- <div class="table-responsive"> --}}
                            <div id="roleGrid">
                    
                            </div>
                        {{-- </div> --}}
                        <div class="role-buttons">
                            <button class="btn btn-success mt10" id="printrole"><i class="fa fa-print"></i> Print Role</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> --}}
    <script src="{{asset('assets/js/roles.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxcore.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxbuttons.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxscrollbar.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxdatatable.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxtreegrid.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxlistbox.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxdropdownlist.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxinput.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxtooltip.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxdata.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/jqxdata.export.js')}}"></script>
    <script src="{{asset('assets/jqxtree/js/demos.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script> 
    $(document).ready(function () {
        getrole('archived');
    });
    </script>
@endsection

