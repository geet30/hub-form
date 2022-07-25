@extends('admin.layout.app')
@section('title') {{ trans('label.edit_roles') }} @endsection
@section('header_css')
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
                <li>
                    <i class="fa fa-circle"></i>
                    <span>{{ trans('label.edit_roles') }} </span>
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.edit_roles') }}</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                    </div>

                    <div class="main-form">
                        <form role="form" action="{{ route('roles.update', $roleData->id) }}" id="updateRole" method="POST" class="updateRole">
                            @csrf
                            {{ method_field('put') }}
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Name <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-7"><input type="text" placeholder="Enter Name" name="vc_name" id="vc_name" value="{{$roleData->vc_name}}"></div>
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
                                    <div class="col-sm-7"><textarea placeholder="Enter description here..." name="vc_description" id="vc_description" value="{{$roleData->vc_description}}">{{$roleData->vc_description}}</textarea></div>
                                </div>
                            </div>
                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Business Unit <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-7">
                                        <select class="form-control" name="i_ref_bu_id" id="business_unit" disabled="disabled" readonly>
                                            <option value=""> Select Business Unit </option>
                                            @foreach ($bu as $business)
                                            <option value="{{ $business->id }}" {{$business->id == $roleData->i_ref_bu_id ? 'selected' : ''}}> {{ $business->vc_short_name }} </option>
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
                                            <option value="{{ $level->id }}" {{$level->id == $roleData->i_ref_level_id ? 'selected' : ''}}> {{ $level->vc_name.' ($'.$level->i_start_limit. '-$'.$level->i_end_limit.' )' }} </option>
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
                                        <input type="hidden" value="{{!empty($roleData->role_permission) ? base64_encode(serialize($roleData->role_permission)): ''}}" name="old_permission">
                                        <select class="permissions" multiple="multiple" theme="bootstrap" name="permission_id[]">
                                            @foreach ($permissions as $permission)
                                            <option value="{{ $permission->id }}" @if(in_array($permission->id, $roleData->role_permission)) selected @endif> {{ $permission->vc_name }} </option>
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
                                        <input type="hidden" value="{{!empty($roleData->role_form_permission) ? base64_encode(serialize($roleData->role_form_permission)): ''}}" name="old_form_permission">
                                        <select class="form_permissions chosen-select" multiple="multiple" theme="bootstrap" name="form_permission_id[]">
                                            @foreach ($form_permissions as $form_permission)
                                            <option value="{{ $form_permission->id }}" @if(in_array($form_permission->id, $roleData->role_form_permission)) selected @endif > {{ $form_permission->vc_name }} </option>
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
                                        <input type="hidden"  class="account_payable_edit"  value="1">
                                        <input type="checkbox" name="account_payable" class="account_payable"  onclick='is_account_payable_exists({{$roleData->id}},{{$company_id}})' {{$roleData->account_payable == 1 ? 'checked' : '' }}> Account Payable
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-6">

                                        <div class="col-md-3"> <label>Supplier Approver</label></div>
                                        <div class="col-md-3">
                                        <input type="hidden"  class="supplier_approver_edit"  value="1">
                                            <input type="checkbox" name="supplier_approver" class="supplier_approver" {{$roleData->supplier_approver == 1 ? 'checked' : ''}} onclick='is_supplier_approver_exists({{$roleData->id}},{{$company_id}})'> Supplier Approver
                                        </div>
                                    </div>
                                    <div class="col-6">

                                        <div class="col-md-3"> <label>Alternative Supplier Approver</label></div>
                                        <div class="col-md-3">
                                        <input type="hidden"  class="alternative_supplier_approver_edit"  value="1">
                                            <input type="checkbox" name="alternative_supplier_approver" class="alternative_supplier_approver" {{$roleData->alternative_supplier_approver == 1 ? 'checked' : ''}} onclick='is_alternative_supplier_approver_exist({{$roleData->id}},{{$company_id}})'> Alternative Supplier Approver
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>System Administrator</label></div>
                                    <div class="col-sm-7">
                                    <input type="hidden"  class="system_administrator_edit"  value="1">
                                        <input type="checkbox" name="system_administrator" class="system_administrator" onclick='is_system_administrator_exists({{$roleData->id}},{{$company_id}})' {{$roleData->system_administrator == 1 ? 'checked' : ''}}> System Administrator
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Parent Role <span class="asterisk">*</span></label></div>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="i_ref_role_id" id="parent_role" {{$roleData->i_ref_role_id == 0 ? 'disabled="disabled" readonly' : ''}}>
                                            <option value=""> Select Parent Role </option>
                                            @if(isset($roleData->business_unit->roles))
                                            @foreach ($roleData->business_unit->roles as $roles)
                                            @if($roleData->id!=$roles->id)
                                            <option value="{{ $roles->id }}" {{$roles->id == $roleData->i_ref_role_id ? 'selected' : ''}}> {{ $roles->vc_name }} </option>
                                            @endif
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if($roleData->i_ref_role_id == 0)
                                    <div class="col-sm-4">
                                        <input type="checkbox" name="self_parent" class="parent self_parent" disabled checked> Parent Role
                                        <p class="alreadyParentBU" style="display: none; color:sandybrown"> Business Unit already has top level role</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-new-inputs">
                                <div class="row">
                                    <div class="col-md-3"> <label>Status</label></div>
                                    <div class="col-sm-7">
                                        <select name="i_status" id="i_status">
                                            @foreach ($status as $row => $value)
                                            <option value="{{ $row }}" {{$row == $roleData->i_status ? 'selected' : ''}}> {{ $value }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-new-btns">
                                <div class="row">
                                    <div class="offset-3 col-5">
                                        <button class="btn btn-primary" type="submit" id="update-roles">Update</button>&nbsp;
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
    });

    // on submit edit completed form
    $('.updateRole').submit(function() {
        $('#business_unit').attr('disabled', false);
        $('#parent_role').attr('disabled', false);
    });

    /**
     * remove class on save buttom
     * 
     */
    $('#update-roles').click(function() {

        $('#updateRole input').removeClass('form_change');
        $('#updateRole select').removeClass('form_change');
        $('#updateRole textarea').removeClass('form_change');
    });

    /**
     * add class if there is change in any
     * input
     */
    $('#updateRole input, #updateRole select, #updateRole textarea').on('keyup change', function() {
        $(this).addClass('form_change');
    });

    /**
     * alert before leaving window 
     */
    $(window).on('beforeunload', function() {
        if ($('#updateRole input').hasClass('form_change') || $('#updateRole select').hasClass('form_change') ||
            $('#updateRole textarea').hasClass('form_change')) {
            var c = confirm();
            if (c) {
                return true;
            } else
                return false;
        }
    });
</script>
@endsection