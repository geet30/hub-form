@extends('admin.layout.app')
@section('title'){{'Manage Folder'}}@endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
 .select2.select2-container.select2-container--default{
        padding-top: 10px !important;
        width: 336px !important;
    }

    .select2-selection__arrow {
        margin-top: 10px;
    }

    </style>
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
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>{{ trans('label.manage_folder') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-gear font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.manage_folder') }} </span>
                        </div>
                        <div class="caption font-dark p-4" style="
    padding-left: 10px;
">
                            <?php
                            if (isset($_COOKIE['search_selected_folder']) && !empty($_COOKIE['search_selected_folder'])) {
                                $search_selected_folder = $_COOKIE['search_selected_folder'];
                            } else {
                                $search_selected_folder = "";
                            }
                            $folder_name = '';
                            foreach ($select2folder as $key => $values) {
                                $foldername = "";
                                $selected = "";
                                foreach (array_reverse($values) as $folder) {
                                    $foldername .= $folder['name'] . "/";
                                    $encrypted_id = $folder['encrypted_id'];
                                    if (isset($search_selected_folder) && !empty($search_selected_folder)) {
                                        $id = $search_selected_folder;
                                        $selected = ($encrypted_id == $id) ? "selected" : "";
                                        unset($_COOKIE['search_selected_folder']);
                                        setcookie('search_selected_folder', null);
                                    }
                                }

                                $folder_name .= '<option value="' . trim($encrypted_id) . '"  ' . $selected . '    >' . trim($foldername, "/") . '</option>';
                            } ?>
                            <select id="folder_search">
                                <option></option>
                                <?php echo $folder_name; ?>
                            </select>
                        </div>

                        @if($parent_id != '' && $parent_id != 0)
                        @php $parent_folder = encrypt_decrypt('encrypt', $parent_id); @endphp
                        {{-- <div class="btn-group pull-right back-button">
                            <a class="btn sbold green " id="" href="{{ url('admin/folders/'.$parent_folder) }}">Back
                        </a>
                    </div> --}}
                    @endif
                    @if($total_child < 5) @if(count($folders) <=20) <div class="btn-group pull-right">
                        <a class="btn sbold green " id="create-folder">Create Folder
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                        </a>
                </div>
                @endif
                @endif
                @if(count($folders) <= 20 && !empty($parent_data)) <div class="btn-group pull-right" style="
    padding-right: 10px;
">
                    <a class="btn sbold green " id="openupload_doc_modal">Upload Document
                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                    </a>
            </div>
            @endif
        </div>
        <div class="portlet-body">
            <div class="pre_loader">
                <img src="{{asset('assets/images/loading.gif')}}" alt="">
            </div>
            @include('components.folder-crumps')
            <div class="folder-structure">
                <x-folder-structure :folders="$folders" :documents="$documents" :parent_id="$parent_id" />
            </div>
            {{-- @include('admin.folder.table') --}}
            {{-- <x-folder-hierarchical :folders="$folders"/> --}}
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Rename folder Modal -->
<div id="rename_folder" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal_close">&times;</span>
            <div class="category_title">Rename Folder</div>
        </div>
        <div class="modal-body">
            {{ Form::open(array('id' => 'rename_folder_form', 'enctype' => 'multipart/form-data'))}}
            <div class="new_cat_div">
                <input type="hidden" name="folder_id" id="folder_id" value="">
                <label class="category_label"> Rename Folder </label> <input type="text" name="folder_name" class="folder_name form-control" placeholder="Folder Name" maxlength="100" id="folder_name"><br>
                <div class="button_div">
                    <input type="button" name="submit" value="Done" class="btn btn-success category-btn" onclick="edit_folder()">
                    <input type="button" class="btn btn-success cancel_modal" id="cancel_modal" value="Cancel">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

{{-- view documemt Modal --}}
<div id="view_document" class="modal">
    {{-- Modal content --}}
    <div class="modal-content">
        <div class="modal-header">
            <span class="upload_close close-view-document">&times;</span>
            <div class="upload_title">Document View</div>
        </div>
        <div class="modal-body">
            <div class="view_doc">


            </div>
        </div>
    </div>
</div>

@include('admin.folder.create_modal')
@include('admin.folder.create_folder_subfolder')
@include('admin.folder.upload_document_modal')
@endsection
@section('footer_scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>

{{-- <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" /> --}}
<script>
    var save_folder = "{{route('save_folder')}}";

    $(".owner_list").select2({
        // dropdownCssClass : 'owner_list'
        dropdownAutoWidth : true
    });                    
    $("#folder_search").select2();

    $("#folder_search").change(function() {
        $("#folder_search").val();
        document.cookie = "search_selected_folder=" + $("#folder_search").val() + "";
        localStorage.setItem("search_selected_folder", $("#folder_search").val());
        window.open(APP_URL + "/admin/folders/" + $("#folder_search").val(), "_self");
    });

    $(document).ready(function() {
        if (localStorage.getItem("search_selected_folder") != null) {

            $("#folder_search").val(localStorage.getItem("search_selected_folder"));

            setTimeout(function() {
                $("#folder_search").val(localStorage.getItem("search_selected_folder"));
            }, 2000);
        }

    });
</script>
<script src="{{asset('assets/js/folder.js')}}"></script>
<script src="{{asset('assets/js/document.js')}}"></script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>

<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
    $(".folders").draggable({
        revert: "invalid",
        zIndex: 99,
        helper: "clone",
        cursor: "move"
        // start: function() {
        //     console.log("asd");
        // },
        // drag: function() {
        // console.log("asd");
        // },
        // stop: function() {
        // console.log("easda");
        // }

    });


    
    $(".folder-icon").droppable({

        accept: ".folders",
        drop: function(event, ui) {
         
            // console.log("rrrrrrrrr");
            // console.log(event);
            // console.log(ui);
            // console.log($(this));
            if(ui.draggable[0].classList.length==5){
               
                id = ui.draggable[0].classList[2];
                folder_id = $(this)[0].classList[2];
                // console.log(id);
                // console.log(folder_id);
                // return true;
                if(id!="" && folder_id!="" && folder_id!=undefined && id!=undefined){
                    $('.pre_loader').show();
                    $.ajax({
                        url: '/admin/update_document/' + id,
                        type: 'post',
                        data: {
                            folder_id: folder_id
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            var resp = response.message;
                            alertSuccess(resp);
                        },
                        complete: function() {

                            $(ui.draggable[0]).fadeOut(function() {});

                            $('.pre_loader').hide();
                        },
                        error: function(error) {
                            errorHandler(error);
                        }
                     });
                    }
                return true;
            }else{
                id = ui.draggable[0].classList[4];
            
                parent_folder_id = $(this)[0].classList[2];
           
                if(id!="" && parent_folder_id!="" && parent_folder_id!=undefined && id!=undefined){
                    $('.pre_loader').show();
                                $.ajax({
                    url: '/admin/update/' + id,
                    type: 'post',
                    data: {
                        parent_folder_id: parent_folder_id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        var resp = response.message;
                        alertSuccess(resp);
                    },
                    complete: function() {

                        $(ui.draggable[0]).fadeOut(function() {});

                        $('.pre_loader').hide();
                    },
                    error: function(error) {
                        errorHandler(error);
                    }
                });

            }
        }

        }
    });
</script>
@endsection