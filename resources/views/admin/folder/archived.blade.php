@extends('admin.layout.app')
@section('title'){{'Manage Folder'}}@endsection
@section('header_css')
<style>
    .folders-icon {
    margin-left: 96px;
    position: absolute;
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
                            <span class="caption-subject bold uppercase"> {{ trans('label.archive_folder') }} </span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @include('components.archive-folder-crumps')
                        <div class="folder-structure">
                            <x-archive-folder-hierarchical :folders="$folders" :documents="$documents" :parent_id="$parent_id"/>
                        </div>
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
                <label class="category_label"> Rename Folder </label> <input type="text" name="folder_name"
                    class="folder_name form-control" placeholder="Folder Name" maxlength="100" id="folder_name"><br>
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

@endsection
@section('footer_scripts')
{{-- <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" /> --}}
<script>
    var save_folder = "{{route('save_folder')}}";
</script>
<script src="{{asset('assets/js/folder.js')}}"></script>
<script src="{{asset('assets/js/document.js')}}"></script>
@endsection
