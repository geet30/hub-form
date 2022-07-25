<!-- Share Template Modal -->
<div id="share_temp" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="share_close">&times;</span>
            <div class="id-div">
                <span class="id-circle" style="width: 40px;height: 40px;"></span>
                <span id="temp_id" class="temp_id"></span>
            </div>
            <div class="share_title">Share with people or groups</div>
        </div>
        <div class="modal-body">
            <div class="modal_loader" style="display:none; text-align: center;">
                <img src="{{asset('assets/images/loading.gif')}}" alt="">
            </div>
            <div class="add_people">
                <div class="add_title">Add People</div>
                <div class="share_dropdown">
                    <!-- <select onclick="myFunction()" class="dropbtn" placeholder="Select name or email"></select> -->
                    <input type="hidden" id="shared_temp_id" name="shared_temp_id">
                    <input type="text" placeholder="Enter name or group." id="shareInput"
                        onkeyup="filterFunction()" autocomplete="off" data-id="" data-form="">
                    <i class="fa fa-angle-down" onclick="myFunction()"></i>
                    <div id="shareDropdown" class="dropdown-content">
                        @foreach($users as $user)
                        <a onclick="input_text(this)" target-id="{{$user->id}}" target-form="user">{{$user->full_name}}</a>
                        @endforeach
                        {{-- @foreach($users as $user)
                        <a onclick="input_text(this)" target-id="{{$user->id}}" target-form="email">{{$user->email}}</a>
                        @endforeach --}}
                        @foreach($groups as $group)
                        <a onclick="input_text(this)" target-id="{{$group->id}}" target-form="group">Group-{{$group->vc_name}}</a>
                        @endforeach
                    </div>
                    <input type="button" name="share" value="Share" class="btn btn-success share-btn"
                        onclick="share_template_with();">
                    <div class="share_error error" style="display:none;color:red"> </div>
                </div>
                <div class="current_share">
                    <div class="current_title">Current Shares </div>
                    <div id="share_users"></div>
                </div>
            </div>
        </div>
    </div>
</div>