@extends('admin.layout.app')
@section('title') {{ trans('label.edit_bu') }} @endsection
@section('header_css')
<style>.select2-container{
    z-index:0;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
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
                    <span>{{ trans('label.edit_bu') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.edit_bu') }}</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form role="form" action="{{ route('business-units.update', $bu_data->id_encrypted) }}" id="editBusinessUnit"
                            method="POST">
                            <div class="pre_loader">
                                <img src="{{asset('assets/images/loading.gif')}}" alt="">
                            </div>
                            @csrf
                            {{ method_field('put') }}
                            <input type="hidden" name="bu_id" id="bu_id" value="{{$bu_data->id}}">
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Name" name="vc_short_name" id="vc_short_name" value="{{$bu_data->vc_short_name}}"></div>
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
                                    <div class="col-sm-6"><input type="text" placeholder="Enter Legal Name" name="vc_legal_name" id="vc_legal_name" value="{{$bu_data->vc_legal_name}}"></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Description</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter description here..." name="vc_description" id="vc_description" value="{{$bu_data->vc_description}}">{{$bu_data->vc_description}}</textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Comments</label></div>
                                    <div class="col-sm-9"><textarea placeholder="Enter comments here..." name="vc_comments" id="vc_comments" value="{{$bu_data->vc_comments}}">{{$bu_data->vc_comments}}</textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Location<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-4">
                                        <select name="i_ref_location_id" id="i_ref_location_id">
                                            <option value=""> Select location </option>
                                            @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" {{(old('i_ref_location_id', $bu_data->i_ref_location_id) == $location->id)?'selected':''}}> {{ $location->vc_name }} </option>
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
                                        <input type="hidden" value="{{!empty($bu_data->business_dept) ? base64_encode(serialize($bu_data->business_dept)): ''}}" name="old_department"> 
                                        <select class="departments" multiple="multiple" theme="bootstrap" name="department_id[]">
                                            @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @if(in_array($department->id, $bu_data->business_dept)) selected @endif> {{ $department->vc_name }} </option>
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
                                            <option value="{{ $row }}" {{($bu_data->i_status == $row)?'selected':''}}> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-new-btns"><div class="row">
                                <div class="offset-3 col-5">
                                    <button class="btn btn-primary"  type="submit" id="edit-bu">Update</button>&nbsp;
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
<script src="{{url('assets/js/business_unit.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // $('.departments').select2();
        $(".departments").select2({
            theme: "classic",
            placeholder: "Select a department",
            allowClear: true
        });
        $('.departments').on('select2:unselecting', function (e) {
            e.preventDefault();
            var data = {};
            data.id =e.params.args.data.id;
            data.text = e.params.args.data.text;
            console.log(e.params.args.data);  
            data.bu_id =  $('#bu_id').val();
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
                                    $(".departments option[value="+e.params.args.data.id+"]").prop("selected", false).parent().trigger("change");
                                }else{
                                }
                               
                            }
                        });
                    }else{
                        $(".departments option[value="+e.params.args.data.id+"]").prop("selected", false).parent().trigger("change");
                    }
                },
                error: function (xhr, status, error) {

                    // alertError("Something went wrong. Pleasee try again.", 'error');
                }
            });
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
        $("#editBusinessUnit").validate({
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
    $('#edit-bu' ).click(function(){
        $('#editBusinessUnit input').removeClass('form_change');
        $('#editBusinessUnit select').removeClass('form_change');
        $('#editBusinessUnit textarea').removeClass('form_change');
    });
    
    /**
    * add class if there is change in any
    * input
    */
    $('#editBusinessUnit input, #editBusinessUnit select, #editBusinessUnit textarea').on('keyup change', function(){
        $(this).addClass('form_change');
    });

    /**
    * alert before leaving window 
    */
    $(window).on('beforeunload', function(){
        if($('#editBusinessUnit input').hasClass('form_change') || $('#editBusinessUnit select').hasClass('form_change') 
        || $('#editBusinessUnit textarea').hasClass('form_change') ){
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
