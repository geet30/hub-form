<div id="role_has_user" class="modal open" style="display: nene; ">
    <!-- Modal content -->
    <div class="modal-content" style="height: auto;overflow: hidden;">
        <div class="modal-header">
            <span class="upload_close">×</span>
            <div class="upload_title">Assign new Role</div>
        </div>
        <div class="modal-body">
            <div class="create_new_role d-none form-new">

                <form role="form" action="javascript(void);" id="assignnewrole" method="POST" class="createRole">
    <input type="hidden" name="id" id="restore_user_id">
                    <div class="row">
                        <div class="col-md-4">
                            <label> Role</label>
                            <select class="form-control" name="i_ref_role_id" id="role">
                                

                            </select>
                        </div>

                        <button type="button" id="assign_new_role" class="btn btn-primary" style="
    margin: 30px;
">Submit</button>
                    </div>




                    {{ Form::close() }}
            </div>



        </div>
    </div>
</div>