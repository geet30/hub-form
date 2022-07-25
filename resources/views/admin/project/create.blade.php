@extends('admin.layout.app')
@section('title') {{ trans('label.create_project') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="{{ route('dashboard') }}">{{ trans('label.dashboard') }}</a>
                </li>
                <li>
                    <i class="fa fa-circle"></i>
                    <span>{{ trans('label.create_project') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-briefcase"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_project') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('projects.store') }}" id="createProject" method="POST" class="createProject">
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Name" name="vc_name" id="vc_name"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @error('vc_name')
                                <label for="vc_name" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Description</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter description here..." name="vc_description" id="vc_description"></textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Comments</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter comments here..." name="vc_comment" id="vc_comment"></textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business Unit <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6">
                                        <select class="form-control" name="i_ref_bu_id" id="i_ref_bu_id">
                                            <option value=""> Select Business Unit </option>
                                            @foreach ($business_unit as $business)
                                            <option value="{{ $business->id }}"> {{ $business->vc_short_name }} </option>
                                            @endforeach
                                        </select> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @error('i_ref_bu_id')
                                <label for="i_ref_bu_id" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Status</label></div>
                                    <div class="col-sm-5">
                                        <select name="i_status" id="i_status">
                                            @foreach ($status as $row => $value)
                                            <option value="{{ $row }}"> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-new-btns"><div class="row">
                                <div class="offset-3 col-5">
                                    <button class="btn btn-primary"  type="submit" id="save-project">Save</button>&nbsp;
                                    <a href="{{route('projects.index')}}" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {

        /**
         * Form validation
         */
        $("#createProject").validate({
            rules: {
                vc_name: {
                    required: true,
                    maxlength: 32,
                    minlength:2
                },
                i_ref_bu_id: {
                    required: true,
                }
            }
        });
    });

    /**
    * remove class on save buttom
    * 
    */
    $('#save-project' ).click(function(){
        $('#createProject input').removeClass('form_change');
        $('#createProject select').removeClass('form_change');
        $('#createProject textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#createProject input, #createProject select, #createProject textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#createProject input').hasClass('form_change') || $('#createProject select').hasClass('form_change') 
        || $('#createProject textarea').hasClass('form_change') ){
            var c=confirm();
            if(c){
            return true;
            }
            else
            return false;
        }
    });

</script>
@endsection
