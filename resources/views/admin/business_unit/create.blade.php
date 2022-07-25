@extends('admin.layout.app')
@section('title') {{ trans('label.create_bu') }} @endsection
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
                {{-- <li>
                    <i class="fa fa-briefcase"></i>
                    <a href="{{ route('business-units.index') }}">{{ trans('label.business_unit') }}</a>
                    <i class="fa fa-circle"></i>
                </li> --}}
                <li>
                    <i class="fa fa-circle"></i>
                    <span>{{ trans('label.create_bu') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_bu') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('business-units.store') }}" id="createBusinessUnit"
                        method="POST">
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Name" name="vc_short_name" id="vc_short_name"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @error('vc_short_name')
                                <label for="vc_short_name" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Legal Name</label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Legal Name" name="vc_legal_name" id="vc_legal_name"></div>
                                </div>
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
                                    <div class="col-sm-9"><textarea placeholder="Enter comments here..." name="vc_comments" id="vc_comments"></textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Location<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-4">
                                        <select name="i_ref_location_id" id="i_ref_location_id">
                                            <option value=""> Select location </option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"> {{ $location->vc_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                @error('i_ref_location_id')
                                <label for="i_ref_location_id" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Departments</label></div>
                                    <div class="col-sm-6">
                                        <select class="departments" multiple="multiple" theme="bootstrap" name="department_id[]">
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"> {{ $department->vc_name }} </option>
                                            @endforeach
                                        </select>   
                                    </div>
                                    <div class="col-md-3"> <button type="button" class="btn btn-primary departments-all">Select all</button></div>
                                </div>
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
                                    <button class="btn btn-primary"  type="submit" id="save-bu">Save</button>&nbsp;
                                    <a href="{{route('business-units.index')}}" class="btn btn-default">Cancel</a>
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
        // $('.departments').select2();
        $(".departments").select2({
            theme: "classic",
            placeholder: "Select a department",
            allowClear: true
        });
        /**
         * Select All departments 
         */
        $(".departments-all").click(function () {
            // if ($(".departments").find('option:selected').length == 0) {
                $(".departments > option").prop("selected", "selected");
                $(".departments").trigger("change");
            // } else {
            //     $(".departments > option").removeAttr("selected");
            //     $(".departments").trigger("change");
            // }
        });

        /**
         * Form validation
         */
        $("#createBusinessUnit").validate({
            rules: {
                vc_short_name: {
                    required: true,
                    maxlength: 32,
                    minlength:2
                },
                i_ref_location_id: {
                    required: true,
                }
            }
        });
    });

    /**
    * remove class on save buttom
    * 
    */
    $('#save-bu' ).click(function(){
        $('#createBusinessUnit input').removeClass('form_change');
        $('#createBusinessUnit select').removeClass('form_change');
        $('#createBusinessUnit textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#createBusinessUnit input, #createBusinessUnit select, #createBusinessUnit textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#createBusinessUnit input').hasClass('form_change') || $('#createBusinessUnit select').hasClass('form_change') 
        || $('#createBusinessUnit textarea').hasClass('form_change') ){
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
