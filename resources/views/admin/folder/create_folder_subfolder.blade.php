<div id="create_subfolder_folder" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="subfolder_title">Create Folder/Sub Folder</div>
        </div>
        <div class="modal-body">
            <form id="sub_folder_form" role="form">
                <!-- {{ Form::open(array('action' => 'FolderController@save', 'method' => 'post', 'id' => 'sub_folder_form', 'enctype' => 'multipart/form-data'))}} -->
                @csrf
                <div class="close_action_div">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6">
                                <input type="button" name="close" value="Folder"
                                    class="btn btn-success float-left folder-btn">
                            </div>
                            <div class="col-md-6">
                                <input type="button" name="close" value="Sub folder"
                                    class="btn btn-success sub-folder-btn">
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-2">
                            <label class="close_label"></label>
                        </div>
                        <div class="col-md-8">
                            <input type="hidden" id="new_fol_sub" value=1 name="new_fol_sub">
                            <input type="hidden" id="folder_id" value='' name="folder_id">
                            <input type="hidden" id="parent_folder_id" value='' name="parent_folder_id">
                            <input type="text" name="folder_name" placeholder="Folder" class="form-control folder_name folder-textfeild" id="folder_name" style="display:none;">
                            <input type="text" name="sub_folder_name" placeholder="Sub Folder" class="form-control sub_folder_name sub-folder-textfeild" id="sub_folder_name" style="display:none;">
                            <span class="folder_error" style="color:red"> </span>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-25">
                    <div class="form-group btn_section">
                        <div class="col-md-2">
                            <label class="close_label"></label>
                        </div>
                        <div class="col-md-8 ">
                            <input type="button" name="save" value="Save" class="btn btn-success  sub-folder-submit-btn" style="display:none;">
                        </div>
                        <div class="col-md-2">
                            <label class="close_label"></label>
                        </div>
                    </div>
                </div>
            </form>
            <!-- {{ Form::close() }} -->
        </div>
        <!-- <div class="modal-footer">
      <h3>Modal Footer</h3>
    </div> -->
    </div>
</div>
