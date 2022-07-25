@extends('admin.layout.app')
@section('title') {{ trans('label.create_group') }} @endsection
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
                    <span>{{ trans('label.create_group') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_group') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('groups.store') }}" id="createGroup"
                            method="POST">
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
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
                                    <div class="col-md-3"> <label>Roles<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6">
                                        <select class="form-control roles" multiple="multiple" theme="bootstrap" name="role_id[]">
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"> {{ $role->vc_name }} </option>
                                            @endforeach
                                        </select>   
                                    </div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Permissions</label></div>
                                    <div class="col-sm-6">
                                        <select class="form-control permissions" multiple="multiple" theme="bootstrap" name="permission_id[]">
                                            @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}"> {{ $permission->vc_name }} </option>
                                            @endforeach
                                        </select>   
                                    </div>
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
                                    <button class="btn btn-primary"  type="submit" id="save-grp">Save</button>&nbsp;
                                    <a href="{{route('groups.index')}}" class="btn btn-default">Cancel</a>
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
    $(document).ready(function () {;
        $(".roles").select2({
            theme: "classic",
            placeholder: "Select roles",
            allowClear: true
        });

        $(".permissions").select2({
            theme: "classic",
            placeholder: "Select permissions",
            allowClear: true
        });

        /**
         * Form validation
         */
        $("#createGroup").validate({
            rules: {
                vc_name: {
                    required: true,
                },
                'role_id[]':{
                    required: true,
                }
            },
            messages: {
                vc_name: {
                    required: "Please enter Name.",
                },
                'role_id[]':{
                    required: "Please select Roles",
                }
            }
        });
    });

    /**
    * remove class on save buttom
    * 
    */
    $('#save-grp' ).click(function(){
        $('#createGroup input').removeClass('form_change');
        $('#createGroup select').removeClass('form_change');
        $('#createGroup textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#createGroup input, #createGroup select, #createGroup textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#createGroup input').hasClass('form_change') || $('#createGroup select').hasClass('form_change') 
        || $('#createGroup textarea').hasClass('form_change') ){
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
