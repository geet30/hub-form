@extends('admin.layout.app')
@section('title') {{ trans('label.edit_dept') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<style>.select2-container{
    z-index:0;
}
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
                </li>
                {{-- <li>
                    <i class="fa fa-briefcase"></i>
                    <a href="{{ route('business-units.index') }}">{{ trans('label.business_unit') }}</a>
                    <i class="fa fa-circle"></i>
                </li> --}}
                <li>
                    <i class="fa fa-circle"></i>
                    <span>{{ trans('label.edit_dept') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.edit_dept') }}</span>
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('departments.update', $dept_data->id_encrypted) }}" id="createDepartment"
                            method="POST">
                            @csrf
                            {{ method_field('put') }}
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Name" name="vc_name" id="vc_name" value="{{$dept_data->vc_name}}"></div>
                                </div>
                            </div>
                            <input type="hidden"  id="dep_id" value="{{$dept_data->id}}">
                            <div class="col-sm-6">
                                @error('vc_name')
                                <label for="vc_name" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Description</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter description here..." name="vc_description" id="vc_description" value="{{$dept_data->vc_description}}">{{$dept_data->vc_description}}</textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Comments</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter comments here..." name="vc_comment" id="vc_comment" value="{{$dept_data->vc_comment}}">{{$dept_data->vc_comment}}</textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business Unit</label></div>
                                    <div class="col-sm-6">
                                        <input type="hidden" value="{{!empty($dept_data->dept_bu) ? base64_encode(serialize($dept_data->dept_bu)): ''}}" name="old_bu"> 
                                        <select class="form-control business-units" multiple="multiple" theme="bootstrap" name="business_unit_id[]">
                                            @foreach ($business_units as $business_unit)
                                            <option value="{{ $business_unit->id }}" @if(in_array($business_unit->id, $dept_data->dept_bu)) selected @endif> {{ $business_unit->vc_short_name }} </option>
                                            @endforeach
                                        </select>   
                                    </div>
                                    <div class="col-md-3"> <button type="button" class="btn btn-primary business-units-all">Select all</button></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Status</label></div>
                                    <div class="col-sm-5">
                                        <select name="i_status" id="i_status">
                                            @foreach ($status as $row => $value)
                                            <option value="{{ $row }}" {{($dept_data->i_status == $row)?'selected':''}}> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-new-btns"><div class="row">
                                <div class="offset-3 col-5">
                                    <button class="btn btn-primary"  type="submit" id="save-dept">Add</button>&nbsp;
                                    <a href="{{route('departments.index')}}" class="btn btn-default">Cancel</a>
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
        $(".business-units").select2({
            theme: "classic",
            placeholder: "Select a Business Unit",
            allowClear: true
        });
        /**
         * Select All business-units 
         */
        $(".business-units-all").click(function () {
            // if ($(".business-units").find('option:selected').length == 0) {
                $(".business-units > option").prop("selected", "selected");
                $(".business-units").trigger("change");
            // } else {
            //     $(".business-units > option").removeAttr("selected");
            //     $(".business-units").trigger("change");
            // }
        });

        /**
         * Form validation
         */
        $("#createDepartment").validate({
            rules: {
                vc_name: {
                    required: true,
                    maxlength: 32,
                    minlength:2
                }
            }
        });
    });

    /**
    * remove class on save buttom
    * 
    */
    $('#save-dept' ).click(function(){
        $('#createDepartment input').removeClass('form_change');
        $('#createDepartment select').removeClass('form_change');
        $('#createDepartment textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#createDepartment input, #createDepartment select, #createDepartment textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#createDepartment input').hasClass('form_change') || $('#createDepartment select').hasClass('form_change') 
        || $('#createDepartment textarea').hasClass('form_change') ){
            var c=confirm();
            if(c){
            return true;
            }
            else
            return false;
        }
    });


    $('.business-units').on('select2:unselecting', function (e) {
            e.preventDefault();
            var data = {};
            data.bu_id =e.params.args.data.id;
            data.text = e.params.args.data.text;
            console.log(e.params.args.data);  
            data.id =  $('#dep_id').val();
            $("#pre_loader").show();
            $.ajax({
                url: '/admin/cms/check/business/department',
                data: data,
                type: 'POST',
                success: function (data) {
                    if (data == 'false') {
                        
                        bootbox.confirm({
                            message: "Are you sure you want to delete this department, this department is linked?",
                            buttons: {
                                confirm: {
                                    label: 'Yes',
                                    className: 'btn-success'
                                },
                                cancel: {
                                    label: 'No',
                                    className: 'btn-danger'
                                }
                            },
                            callback: function (result) {
                                if(result){
                                    $(".business-units option[value="+e.params.args.data.id+"]").prop("selected", false).parent().trigger("change");
                                }else{
                                }
                               
                            }
                        });
                    }else{
                        $(".business-units option[value="+e.params.args.data.id+"]").prop("selected", false).parent().trigger("change");
                    }
                },
                error: function (xhr, status, error) {

                    // alertError("Something went wrong. Pleasee try again.", 'error');
                }
            });
        });
</script>
@endsection
