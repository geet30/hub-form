<!-- Create folder Modal -->
<div id="create_folder_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="close_title">Create New Folder</div>
        </div>
        <div class="modal-body">
            <form id="folder_form">
                @csrf
                <div class="close_action_div">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="close_label">Folder Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="hidden" name="parent_folder_id" value="{{$parent_id ?? 0}}">
                                <input type="text" name="name" class="folder_name form-control"
                                    class="folder_name"><br>
                                <span class="error"></span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row subfolder_section">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="close_label">Sub Folder Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="sub_folder_name" class="close_date form-control"
                                    class="sub_folder_name">
                                <span class="error"></span>
                            </div>
                        </div>
                    </div> --}}
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-4">
                                <label class="close_label"></label>
                            </div>
                            <div class="col-md-6">
                                <input type="button" name="close" id="save_folder_form" value="Submit"
                                    class="btn btn-success share-btn">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>