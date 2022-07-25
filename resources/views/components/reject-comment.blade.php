<!-- Rename folder Modal -->
<div id="reject_comment" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close close_comment_modal">&times;</span>
            <div class="category_title">Add comments</div>
        </div>
        <div class="modal-body">
            <div class="new_comment_div">
                <input type="hidden" name="folder_id" id="folder_id" value="">
                {{-- <label class=" comment_label"> Comments </label>  --}}
                <textarea name="comments" rows="4" cols="50"
                    class="comments form-control" placeholder="Here...." maxlength="200" id="comments">
                </textarea>    
                <br>
                <label class="error comment_error"> </label>
                
                <div class="button_div">
                    <input type="button" value="Done" target="" class="btn btn-success comment-btn" data-id="" onclick="accept_reject_action(this, 2)">
                    <input type="button" class="btn btn-success close_comment_modal" id="close_comment_modal" value="Cancel">
                </div>
            </div>
        </div>
    </div>
</div>