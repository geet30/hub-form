<!-- Close Action Modal -->
<div id="close_action_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="close_title">Complete Action</div>
        </div>
        <div class="modal-body">
            {{ Form::open(array('action' => 'ActionsController@close_action', 'method' => 'post', 'id' => 'close_action_form', 'enctype' => 'multipart/form-data'))}}
            <div class="close_action_div">
                <span class="close-comment">You are not allowed to perform further process on this action. It will be auto archived after 30 days.</span>
                <input type="hidden" name="action_id" id="action_id" value="">
                <label class="close_label"> Completed Date <span style="color:red">*</span> </label>
                <div class="cal-icon-close"><input type="text" name="close_date" class="close_date action_close form-control"> <span
                    class="glyphicon glyphicon-calendar calender-icon"></span> </div><br>
                <label class="close_label"> Completed By <span style="color:red">*</span></label>
                <input type="text" name="close_by" class="close_by form-control action_close" value="{{auth()->user()->vc_fname}} {{auth()->user()->vc_lname}}" disabled="disabled">
                <br>
                <label class="close_label"> Comments <span style="color:red">*</span> </label><input type="text" name="comments"
                    class="comments form-control action_close"><br>
                <label class="close_label"> Evidence </label><input type="file" name="evidence[]"
                    class="evidence form-control action_close" multiple><br><br>
                <div class="uploadfilebuttons">
                    <div class="upload-loader" style="display:none"></div>
                    <input type="submit" name="close" value="Done" class="btn btn-success action-close-btn">
                </div>
            </div>
            {{ Form::close() }}

        </div>
        <!-- <div class="modal-footer">
      <h3>Modal Footer</h3>
    </div> -->
    </div>

</div>