<div id="role_has_user" class="modal open" style="display: nene; ">
    <!-- Modal content -->
    <div class="modal-content" style="height: auto;overflow: hidden;">
        <div class="modal-header">
            <span class="upload_close">×</span>
            <div class="upload_title role_has_user"></div>
        </div>
        <div class="modal-body">

            <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add new Role</button> -->


            <div class="row" style="
                                    padding: 10px;
                                ">
                <div class="col-sm-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input archive_user" type="radio" name="Relive" id="inlineRadio1" value="relive_user">
                        <label class="form-check-label" for="inlineRadio1">Relieve existing user from role</label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="Relive" id="inlineRadio2" value="new_user">
                        <label class="form-check-label" for="inlineRadio2"> Create new role</label>
                    </div>

                </div>
            </div>



            <div class="relive_user" style="display: none;">
                <h2>Are you sure you want to archive this User ?</h2>
                <div class="text-right"><button type="button" class="btn btn-danger bootbox-cancel">No</button>
                    <button type="button" class="btn btn-success bootbox-accept">Yes</button>
                </div>
            </div>


            <div class="create_new_role d-none form-new" style="display: none;">
                <hr style="    margin: 1px 0px;">

                <h4 style="    font-size: 20px;" class="text-center"> Create new Role</h4>
                <form role="form" action="{{ route('roles.store') }}" id="createRole" method="POST" class="createRole">

                    <div class="row ">

                        <div class="col-md-6">
                            <label>Name <span class="asterisk">*</span></label>
                            <input type="text" placeholder="Enter Name" name="vc_name" id="vc_name">
                        </div>
                    </div>
                    <div class="row" style="
                        margin-top: 5px;
                    ">
                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea placeholder="Enter description here..." name="vc_description" id="vc_description" spellcheck="false" class="form_change"></textarea>
                        </div>
                    </div>
                    <div class="row" style="
                        margin-top: 5px;
                    ">
                        <div class="col-md-6">
                            <label>Bustines Unit</label>
                            <input type="hidden" id="i_ref_dep_id">
                            <input type="hidden" name="i_ref_bu_id">
                            <select class="form-control" disabled id="business_unit" name="business_unit" >
                                <option value=""> Select Business Unit </option>
                                @foreach ($business_units as $business)
                                <option value="{{ $business->id }}"> {{ $business->vc_short_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Level <span class="asterisk">*</span></label>
                            <select class="form-control" name="i_ref_level_id" id="i_ref_level_id">
                                <option value=""> Select Level </option>
                                @foreach ($levels as $level)
                                <option value="{{ $level->id }}"> {{ $level->vc_name.' ($'.$level->i_start_limit. '-$'.$level->i_end_limit.' )' }} </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row" style="
                        margin-top: 5px;
                    ">
                        <div class="col-md-6">
                            <label>Permissions <span class="asterisk">*</span></label>
                            <select class="permissions" multiple="multiple" theme="bootstrap" name="permission_id[]">
                                @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}"> {{ $permission->vc_name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Form Permissions <span class="asterisk">*</span></label>
                            <select class="form_permissions chosen-select" multiple="multiple" theme="bootstrap" name="form_permission_id[]">
                                @foreach ($form_permissions as $form_permission)
                                <option value="{{ $form_permission->id }}"> {{ $form_permission->vc_name }} </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row" style="
                        margin-top: 14px;
                    ">
                        <div class="col-sm-3">

                            <input type="checkbox" name="account_payable" class="account_payable" onclick='is_account_payable_exists("",{{$company_id}})'> 
                            <label class="role_user_lable">Account Payable</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="checkbox" name="supplier_approver" class="supplier_approver" onclick='is_supplier_approver_exists("",{{$company_id}})'> 
                       
                            <label class="role_user_lable">Supplier Unit</label>
                        </div>
                        <div class="col-sm-3">

                            <input type="checkbox" name="alternative_supplier_approver" class="alternative_supplier_approver" onclick='is_alternative_supplier_approver_exist("",{{$company_id}})'> 

                            <label class="role_user_lable">Alternative Supplier Approver</label>
                        </div>
                        <div class="col-md-3">
                            <input type="checkbox" name="system_administrator" class="system_administrator" onclick='is_system_administrator_exists("",{{$company_id}})'> 
                       
                            <label class="role_user_lable">System Administrator</label>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Parent Role <span class="asterisk">*</span></label>
                            <select class="form-control" name="i_ref_role_id" id="parent_role">
                                <option value=""> Select Parent Role </option>

                            </select>
                        </div>
                        <div class="col-md-4" style="
    
                            margin-top: 26px;
                        ">
                            <input type="checkbox" name="self_parent" class="parent self_parent"> Parent Role
                            <p class="alreadyParentBU" style="display: none;     margin-left: 4px; color:sandybrown"> Business Unit already has top level role</p>
                       
                        </div>
                        <div class="col-md-4">
                            <label>Status</label>

                            <select name="i_status" id="i_status" class="valid">
                                <option value="1"> Active </option>
                                <option value="0"> Inactive </option>
                            </select>
                        </div>

                    </div>




                    <button type="button" id="new_role" class="btn btn-primary" style="    margin-bottom: 10px;">Submit</button>
                    {{ Form::close() }}
            </div>



        </div>
    </div>
</div>
