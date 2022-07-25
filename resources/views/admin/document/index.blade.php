@extends('admin.layout.app')
@section('title'){{'Document Library'}}@endsection
@section('header_css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  .modal-content{
                height: auto;
            padding-bottom: 30px;
        }
         .new_cat_div .category {
                width: 100%;
                margin-top: 3px;
                float:left;
                max-width: 250px;
            }
            .new_cat_div .button_div, .new_comment_div .button_div {
                width: 62%;
                margin-top: 28px;
            }
                
    div.dataTables_wrapper div.dataTables_filter input {
    padding:0;
    }

    #owner_list {
        width: 336px !important;
    }

    #owner_list_edit {
        width: 336px !important;
    }
    .select2.select2-container.select2-container--default{
        padding-top: 10px !important;
    }

    .select2-selection__arrow {
        margin-top: 10px;
    }

    #filter_folders{ 
        font-weight: 400;
    }
    select#filter_folder .select2.select2-container.select2-container--default{
        font-weight: 400;
    }

    #select2-filter_folder-container{
        font-weight: 400;
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
                    <span>{{ trans('label.doc_library') }} </span>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="fa fa-edit font-dark"></i>
                            <span class="caption-subject bold uppercase"> {{ trans('label.doc_library') }} </span>
                        </div>
                        <!-- <div class="btn-group pull-right">
                            <a id="sample_editable_1_new" class="btn sbold green">Create New Category <i class="fa fa-plus-square" aria-hidden="true"></i></a>
                        </div> -->
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @if($departments)
                        <?php
                        $dept_name = '';
                        foreach ($departments as $department) {
                            $dept_name .= '<option value="' . $department['vc_name'] . '">' . $department['vc_name'] . '</option>';
                        }
                        ?>
                        @endif
                        @if($categories)
                        <?php
                        $cat_name = '';
                        foreach ($categories as $category) {
                            $cat_name .= '<option value="' . $category['name'] . '">' . $category['name'] . '</option>';
                        }
                        ?>
                        @endif
                        @if($all_folders)
                        <?php
                        $folder_name = '';
                        
                        foreach ($parent_data as $key => $values) {
                            $foldername="";
                            foreach(array_reverse($values) as $folder ){
                                
                                $foldername.=$folder['name']."/";
                            }

                            $folder_name .= '<option value="' . trim($foldername,"/") . '">' . trim($foldername,"/") . '</option>';
                          
                        }
                        
                        ?>
                        @endif
                        <input type="hidden" name="dept_name" id="dept_name" value='<?= $dept_name; ?>'>
                        <input type="hidden" name="cat_name" id="cat_name" value='<?= $cat_name; ?>'>
                        <input type="hidden" name="folder_name" id="folder_name" value='<?= (isset($folder_name)) ? $folder_name:''; ?>'>
                        <table id="document_table" style="display:none;" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead">ID, Owner, Title, Business unit, Project</th>
                                    <th class="filterhead">Category</th>
                                    <th class="filterhead">Expiry Date</th>
                                    <th class="filterhead">Department</th>
                                    <th class="filterhead">Folder</th>
                                    <th class="filterhead">Action </th>
                                </tr>
                                <tr class="top-heading">

                                    <th>ID</th>
                                    <th>Folder</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Owner</th>
                                    <th>Expiry Date</th>
                                    <th>Business Unit</th>
                                    <th>Department</th>
                                    <th>Project</th>
                                    <th>Description</th>
                                    <th>Share with Supplier</th>
                                    <th>Use in mobile Device</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $preDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "-1 month")); ?>
                                @foreach($doc_listing as $document)
                                <?php
                                $document->owner=CheckUserType($document->i_ref_owner_role_id,$document->owner_id);
                                

                                // $short_description = '';
                                // if(strlen($document->description) > 15){
                                //     $short_description = substr($document->description, 0, 15).'...';
                                // }else{
                                //     $short_description = $document->description;
                                // }
                                $expiry_date = '';
                                if ($document->expires_at != '') {
                                    $expiry_date =  date("m/d/Y", strtotime($document->expires_at));
                                }
                                ?>
                                <tr>
                                    <td>D-00{{ $document->id ?? ''}}</td>
                                    <td> 

                                    <?php 
                                        if($document->parent_data !=""){
                                            $foldersnames="";
                                            foreach (array_reverse($document->parent_data) as $foldersname){
                                                // print_r($values);
                                                // print_r($das);
                                             $foldersnames.=$foldersname['name']."/";
                                            }

                                            
                                    ?>

                                             <a href="{{ route('folders', $document->folder->encrypted_id) }}">{{trim($foldersnames,'/')}}</a>
                                          <?php 

                                            
                                         
                                        }else{
                                        
                                         echo  "-" ;  
                                        }
                                    ?>
                                    </td>
                                    <td><a href="{{url('admin/download/document/'.$document->id)}}">{{!empty($document->title) ? Str::limit($document->title, 15) : '-'}}</a></td>
                                    <td>{{($document->category !="")?($document->category->name !="")?$document->category->name:"-":"-" }}</td>
                                    <td> {{ ($document->owner !='' )?
                                        
                                            (!empty($document->owner['role_name'])?$document->owner['role_name']:"") ." ".

                                         '['.$document->owner['vc_fname']. ' ' .$document->owner['vc_mname']. ' '.$document->owner['vc_lname'] .']':
                                         '-'
                                        }}

                                    </td>
                                    <td>{{($expiry_date != '') ?$expiry_date: '-'}}</td>
                                    <td>{{($document->business_unit !="")?($document->business_unit->vc_short_name !="")?$document->business_unit->vc_short_name:"-":"-" }}</td>
                                    <td>{{($document->department !="")?($document->department->vc_name !="")?$document->department->vc_name:"-":"-" }}</td>
                                    <td>{{($document->project !="")?($document->project->vc_name !="")?$document->project->vc_name:"-":"-" }}</td>
                                    <td>{{ !empty($document->description)? Str::limit($document->description, 10):'-'}}</td>
                                    <td>
                                        <input type="checkbox" class="form-control share-suplier" data-id="{{ $document->id }}" @if($document->share_with_supplier == 1) checked="checked" @endif>
                                    </td>
                                    <td>
                                        <input type="checkbox" class="form-control mobile-device" data-id="{{ $document->id }}" @if($document->Use_in_mobile == 1) checked="checked" @endif>
                                    </td>
                                    <td>
                                        <div class="dropdown more-btn">
                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span>...</span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                @if(auth()->check() && auth()->user()->user_type == 'company' || $document->owner_id == auth()->user()->id)
                                                <a href="" class="dropdown-item" onclick="edit_doc('{{$document->id}}')"><i class="fa fa-pencil"></i> {{trans('label.edit')}} </a>
                                                <a class="dropdown-item delete_doc" data-id="{{$document->id}}" onclick="delete_doc({{$document->id}})"><i class="fa fa-archive"></i> {{trans('label.archive')}} </a>
                                                <a class="dropdown-item" onclick="activity_log('{{$document->id}}', '{{$document->title}}')"><i class="fa fa-history"></i> {{trans('label.activity_log')}} </a>
                                                @endif
                                                <a class="dropdown-item view-document" data-document='<?php echo json_encode(["document_id" => $document->id, "file_name" => $document->file_name, "file_type" => $document->file_type, "description" => $document->description, "doc_link" => ($document->file_type!=6?$document->doc_link:$document->file_name) ]); ?>'>
                                                    <i class="fa fa-eye"></i> {{ trans('label.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create New Category Modal -->
@include('partials.create-category')
<!-- end New Category Modal -->


<!-- activity log Modal -->
<div id="activity_log_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="activity_close">&times;</span>
            <div class="activity_title">Activity Log</div>
            <div class="document_title"> </div>
        </div>
        <div class="modal-body">
            <div class="activity_div">
                <table class="table activity_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody class="log_data">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- upload documemt Modal -->
<div id="upload_doc_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="upload_close">&times;</span>
            <div class="upload_title">Upload Document</div>
        </div>
        <div class="modal-body">
            <div class="pre_loader" id="pre_loader">
                <img src="{{ asset('assets/images/loading.gif') }}" alt="loader">
            </div>
            <div class="upload_div">
                <form id="create_document">
                    {{-- <!-- {{ Form::open(array('method' => 'post', 'id' => 'create_document', 'enctype' => 'multipart/form-data'))}} --> --}}
                    <div class="label-input">
                        <label class="upload_label"> Title <span style="color:red">*</span></label> <input type="text" name="name" class="form-control upload-doc title" maxlength="100"> <br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Expiry Date</label>
                        <div class="expirydiv"><input type="text" name="expiry_date" class="form-control expiry_date upload-doc" id="expiry_date"><span class="glyphicon glyphicon-calendar expiry-calender"></span></div>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Category <span style="color:red">*</span></label>
                        <select name="category" class=" form-control upload-doc">
                            <option value="">-- Category --</option>
                            @foreach($categories as $category)
                            <option value="{{$category['id']}}">{{$category['name']}}</option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Business Unit <span style="color:red">*</span></label>
                        <select name="business_unit" class=" form-control" id="business_unit">
                            <option value="">-- Business Unit --</option>
                            @foreach($business_unit as $bu)
                            <option value="{{$bu['id']}}">{{$bu['vc_short_name']}}</option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Department <span style="color:red">*</span></label>
                        <select name="department" class=" form-control" id="department">
                            <option value="">-- Department --</option>
                        </select><br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Project </label>
                        <select name="project" class=" form-control" id="project">
                            <option value="">-- Project --</option>
                        </select><br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Owner <span style="color:red">*</span></label>
                        {{-- <input type="text" name="owner" class="form-control upload-doc" maxlength="100" value="{{auth()->user()->vc_fname}} {{auth()->user()->vc_lname}}" disabled> --}}
                        <select name="owner" class="form-control upload-doc owner_list" id="owner_list">
                            <option value="">-- Owner --</option>
                            {{-- @if(isset($users) && !empty($users))
                    @foreach($users as $user)
                    <option value="{{$user['id']}}">{{$user['vc_fname']}} {{$user['vc_lname']}}</option>
                            @endforeach
                            @endif --}}
                        </select>
                        <br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> Description </label>
                        <textarea class="form-control" name="description" maxlength="200"></textarea>

                        <label class="upload_label"> Select Document Folder <span style="color:red">*</span></label>
                        <select name="folder" class="form-control upload-doc">
                            <option value="">-- Document Folder --</option>
                            @foreach($all_folders as $folder)
                            <option value="{{$folder['id']}}">{{$folder['name']}}</option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="label-input">
                        <label class="upload_label"> URL </label>
                        <input type="text" class="form-control online-url" name="url" maxlength="200" />

                    </div>
                    <div class="uploadfilesec">
                        <div class="uploadfileicon"><i class="fas fa-cloud-upload-alt"></i>

                        </div>
                        <div class="filename"></div>
                        <input type="hidden" name="doc_data" id="doc_data">
                        <label for="doc_file" class="uploadtext">Upload file</label>
                        <input type="file" name="file" id="doc_file" style="display:none !important;">
                        <div id="dropbox">
                            <div class="browsefile">Drag and drop your file here or <span class="browsefileinput">Browse <input type="file"></span> </div>
                        </div>
                    </div>
                    <div class="uploadfilebuttons">
                        <input type="submit" name="Save" value="Save" class="btn btn-success save_document">
                        <div class="upload-loader" style="display:none"></div>
                        <input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="upload_cancel">
                    </div>
                </form>
                {{-- <!-- {{ Form::close() }} --> --}}
            </div>
        </div>
    </div>
</div>


<!-- edit documemt Modal -->
<div id="edit_doc_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content" style="    overflow: scroll;">
        <div class="modal-header">
            <span class="edit_close">&times;</span>
            <div class="upload_title">Edit Document</div>
        </div>
        <div class="modal-body">
            <div class="upload_div">
                {{ Form::open(array('action' => 'DocumentsController@update_document','method' => 'post', 'id' => 'edit_document', 'enctype' => 'multipart/form-data'))}}
                <input type="hidden" name="id" id="doc_id">
                <div class="label-input">
                    <label class="upload_label"> Title <span style="color:red">*</span></label> 
                    <input type="text" name="name" class="form-control upload-doc title" maxlength="100" id="edit_title"> <br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Expiry Date</label>
                    <div class="expirydiv"><input type="text" name="expiry_date" class="form-control expiry_date" id="edit_expiry_date"><span class="glyphicon glyphicon-calendar expiry-calender"></span></div>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Category <span style="color:red">*</span></label>
                    <select name="category" class=" form-control upload-doc" id="edit_category">
                        <option value="">-- Category --</option>
                        @foreach($categories as $category)
                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Business Unit <span style="color:red">*</span></label>
                    <select name="business_unit" class=" form-control" id="business_unit_edit" >
                        <option value="">-- Business Unit --</option>
                        @foreach($business_unit as $bu)
                        <option value="{{$bu['id']}}">{{$bu['vc_short_name']}}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Department <span style="color:red">*</span></label>
                    <select name="department" class=" form-control" id="department_edit">
                        <option value="">-- Department --</option>
                    </select><br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Project </label>
                    <select name="project" class=" form-control" id="project_edit">
                        <option value="">-- Project --</option>
                    </select><br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Owner <span style="color:red">*</span></label>
                    <select name="owner" class="form-control upload-doc owner_list" id="owner_list_edit">
                        <option value="">-- Owner --</option>
                    </select>
                    <br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> Description </label>
                    <textarea class="form-control" name="description" maxlength="200" id="doc_description"></textarea>

                    <label class="upload_label"> Select Document Folder <span style="color:red">*</span></label>
                    <select name="folder" class="form-control edit-doc" id="doc_folder">
                        <option value="">-- Select --</option>
                        @foreach($all_folders as $folder)
                        <option value="{{$folder['id']}}">{{$folder['name']}}</option>
                        @endforeach
                    </select><br>
                </div>
                <div class="label-input">
                    <label class="upload_label"> URL </label>
                    <input type="text" class="form-control online-url" id="url_edit" name="url" maxlength="200" />

                </div>
                <div class="uploadfilesec">
                    <div class="uploadfileicon"><i class="fas fa-cloud-upload-alt"></i>

                    </div>
                    <div class="filename" id="edit_filename"></div>
                    <input type="hidden" name="doc_data" id="edit_doc_data">
                    <label for="edit_doc_file" class="uploadtext">Upload file</label>
                    <input type="file" name="file" id="edit_doc_file" class="doc_file_error" style="display:none !important;">
                    <div id="dropbox">
                        <div class="browsefile">Drag and drop your file here or <span class="browsefileinput">Browse <input type="file"></span> </div>
                    </div>
                </div>
                <input type="file" name="editremovefile" id="#editremovefile" style="display:none !important;">
                <div class="eidtfilebuttons">
                    <input type="submit" name="Save" value="Save" class="btn btn-success edit_document">
                    <div class="upload-loader" style="display:none"></div>
                    <input type="button" name="Cancel" value="Cancel" class="btn btn-success" id="edit_cancel">
                </div>
                {{ Form::close() }}
            </div>
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
                <div class="pre_loader" id="pre_view_loader">
                    <img src="{{asset('assets/images/loading.gif')}}" alt="">
                </div>


            </div>
        </div>
    </div>
</div>

@endsection
@section('footer_scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js"></script>
<script src="{{asset('assets/js/document.js')}}"></script>
<script src="{{asset('assets/js/business_unit.js')}}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
<script>
    $(".owner_list").select2({
        // dropdownCssClass : 'owner_list'
        dropdownAutoWidth : true
    });
    $("#filter_folder").select2({
        // dropdownCssClass : 'filter_folders',
        // containerCssClass: 'filter_folders',
    }
        
    );
    
    setTimeout(function(){
        $("#filter_folder").select2({
        // dropdownCssClass : 'filter_folders',
        // containerCssClass: 'filter_folders',
    });
    },1000)

    setTimeout(function(){
        $("#filter_folder").select2({
        // dropdownCssClass : 'filter_folders',
        // containerCssClass: 'filter_folders',
    });
    },2000)

</script>
@endsection