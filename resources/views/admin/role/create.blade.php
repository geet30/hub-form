@extends('admin.layout.app')
@section('title') {{ trans('label.create_roles') }} @endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('assets/template/css/main.css') }}" rel="stylesheet">
<style>
    .supplier-approver{
  padding-top: 0 !important;
}
</style>

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
                    <span>{{ trans('label.create_roles') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.create_roles') }}</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                    </div>
                    <div class="main-form">
                        <form role="form" action="{{ route('roles.store') }}" id="createRole" method="POST" class="createRole">
                            @csrf
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-7"><input type="text" placeholder="Enter Name" name="vc_name" id="vc_name"></div>
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
                                    <div class="col-sm-7"><textarea placeholder="Enter description here..." name="vc_description" id="vc_description"></textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business Unit <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="i_ref_bu_id" id="business_unit">
                                            <option value=""> Select Business Unit </option>
                                            @foreach ($bu as $business)
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
                                    <div class="col-md-3"> <label>Level <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="i_ref_level_id" id="i_ref_level_id">
                                            <option value=""> Select Level </option>
                                            @foreach ($levels as $level)
                                            <option value="{{ $level->id }}"> {{ $level->vc_name.' ($'.$level->i_start_limit. '-$'.$level->i_end_limit.' )' }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                @error('i_ref_level_id')
                                <label for="i_ref_level_id" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Permissions <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6">
                                        <select class="permissions" multiple="multiple" theme="bootstrap" name="permission_id[]">
                                            @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}"> {{ $permission->vc_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"> <button type="button" class="btn btn-primary permission-all">Select all</button></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                @error('permission_id')
                                <label for="permission_id" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Form Permissions<span class="asterisk">*</span></label></div>
                                    <div class="col-sm-6">
                                        <select class="form_permissions chosen-select" multiple="multiple" theme="bootstrap" name="form_permission_id[]">
                                            @foreach ($form_permissions as $form_permission)
                                            <option value="{{ $form_permission->id }}"> {{ $form_permission->vc_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3"> <button type="button" class="btn btn-primary form-per-all">Select all</button></div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                @error('form_permission_id')
                                <label for="form_permission_id" generated="true" class="error">{{ $message }}</label>
                                @enderror
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Account Payable </label></div>
                                    <div class="col-sm-7">
                                        <input type="checkbox" name="account_payable" class="account_payable" onclick='is_account_payable_exists("",{{$company_id}})'> Account Payable
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-6">

                                        <div class="col-md-3"> <label>Supplier Approver</label></div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="supplier_approver" class="supplier_approver" onclick='is_supplier_approver_exists("",{{$company_id}})'> Supplier Approver
                                        </div>
                                    </div>
                                    <div class="col-6">

                                        <div class="col-md-3"> <label class="supplier-approver">Alternative Supplier Approver</label></div>
                                        <div class="col-md-3">
                                            <input type="checkbox" name="alternative_supplier_approver" class="alternative_supplier_approver" onclick='is_alternative_supplier_approver_exist("",{{$company_id}})'> Alternative Supplier Approver
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>System Administrator</label></div>
                                    <div class="col-sm-7">
                                        <input type="checkbox" name="system_administrator" class="system_administrator" onclick='is_system_administrator_exists("",{{$company_id}})'> System Administrator
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Parent Role <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="i_ref_role_id" id="parent_role">
                                            <option value=""> Select Parent Role </option>

                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="checkbox" name="self_parent" class="parent self_parent"> Parent Role
                                        <p class="alreadyParentBU" style="display: none; color:sandybrown"> Business Unit already has top level role</p>
                                    </div>

                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Status</label></div>
                                    <div class="col-sm-7">
                                        <select name="i_status" id="i_status">
                                            @foreach ($status as $row => $value)
                                            <option value="{{ $row }}"> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden"  class="account_payable_edit"  value="1">
                            <input type="hidden"  class="supplier_approver_edit"  value="1">
                            <input type="hidden"  class="alternative_supplier_approver_edit"  value="1">
                            <input type="hidden"  class="system_administrator_edit"  value="1">

                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary" type="submit" id="save-roles">Save</button>&nbsp;
                                        <a href="{{route('roles.index')}}" class="btn btn-default">Cancel</a>
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
<script src="{{url('assets/js/roles.js')}}"></script>
<script src="{{url('assets/js/business_unit.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {

        $(".form_permissions").select2({
            theme: "classic",
            placeholder: "Select a Form Permissions",
            allowClear: true
        });
        /**
         * Select All form permissions 
         */
        $(".form-per-all").click(function() {
            $(".form_permissions > option").prop("selected", "selected");
            $(".form_permissions").trigger("change");
        });

        $(".permissions").select2({
            theme: "classic",
            placeholder: "Select a Permissions",
            allowClear: true
        });
        /**
         * Select All permisiions 
         */
        $(".permission-all").click(function() {
            $(".permissions > option").prop("selected", "selected");
            $(".permissions").trigger("change");
        });

        $(".parent").click(function() {

            var parent_class = $(this).closest('.checked');
            // console.log(parent_class.length);

            if (parent_class.length == 0) {

                $(".permissions > option").removeAttr("selected");
                $(".permissions").trigger("change");

            } else {

                $(".permissions > option").prop("selected", "selected");
                $(".permissions").trigger("change");

            }

        });

        /**
         * Form validation
         */
        $("#createRole").validate({
            rules: {
                vc_name: {
                    required: true,
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
    $('#save-roles').click(function() {
        $('#createRole input').removeClass('form_change');
        $('#createRole select').removeClass('form_change');
        $('#createRole textarea').removeClass('form_change');
    });

    /**
     * add class if there is change in any
     * input
     */
    $('#createRole input, #createRole select, #createRole textarea').on('keyup change', function() {
        $(this).addClass('form_change');
    });

    /**
     * alert before leaving window 
     */
    $(window).on('beforeunload', function() {
        if ($('#createRole input').hasClass('form_change') || $('#createRole select').hasClass('form_change') ||
            $('#createRole textarea').hasClass('form_change')) {
            var c = confirm();
            if (c) {
                return true;
            } else
                return false;
        }
    });
</script>
@endsection