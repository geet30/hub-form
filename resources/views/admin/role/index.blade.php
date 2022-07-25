@extends('admin.layout.app')
@section('title') {{ trans('label.roles') }} @endsection
@section('header_css') 
<link href="{{asset('assets/jqxtree/css/jqx.base.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/jqxtree/css/jqx.bootstrap.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/jqxtree/css/jqx.light.css')}}" rel="stylesheet"/>
<style>
     
    /* .jqx-listitem-element:nth-child(4){
        display:none;
        height: 0px;
        top: 0px;
        left: 0px;
    }
    .jqx-listitem-element:nth-child(5){
        display:none;
        
        height: 0px;
        top: 0px;
        left: 0px;
    }
    .jqx-listitem-element:nth-child(6){
        display:none;
        
        height: 0px !important;
        top: 0px !important;
        left: 0px !important;
    }
    .jqx-listitem-element:nth-child(7){
        display:none;
        
        height: 0px !important;
        top: 0px !important;
        left: 0px !important;
    }
    .jqx-listitem-element:nth-child(8){
        display:none;
        
        height: 0px !important;
        top: 0px !important;
        left: 0px !important;
    }
    .jqx-listitem-element:nth-child(9){
        display:none;
        
        height: 0px !important;
        top: 0px !important;
        left: 0px !important;
    } */
</style>
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
                    <span>{{ trans('label.roles') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-briefcase font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.roles') }} </span>
                        </div>
                        <div class="btn-group pull-right">
                            <a href="{{ route('roles.create') }}" class="btn sbold green">{{ trans('label.create_roles') }}</a>
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
                            <button class="btn btn-success mt10" id="expandAllButton">Expand all row</button>
                            <button class="btn btn-success mt10" id="collapseAllButton">Collapse all row</button>
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
        getrole('index');
    });
    </script>
@endsection

