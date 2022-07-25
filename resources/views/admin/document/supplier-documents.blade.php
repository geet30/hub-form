@extends('admin.layout.app')
@section('title'){{ 'Document Listing' }}@endsection
@section('content')
<div class="page-content-wrapper">
    <div class="page-content">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
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
                    </div>
                    <div class="portlet-body">
                        <div class="pre_loader">
                            <img src="{{asset('assets/images/loading.gif')}}" alt="">
                        </div>
                        @if($departments)
                        <?php 
                        $dept_name = '';
                        foreach ($departments as $department) {
                            $dept_name .= '<option value="'.$department['vc_name'].'">'.$department['vc_name'].'</option>';
                        }
                        ?>
                        @endif
                        @if($categories)
                        <?php 
                        $cat_name = '';
                        foreach ($categories as $category) {
                          $cat_name .= '<option value="'.$category['name'].'">'.$category['name'].'</option>';
                        }
                      ?>
                        @endif
                        <input type="hidden" name="dept_name" id="dept_name" value='<?= $dept_name; ?>'>
                        <input type="hidden" name="cat_name" id="cat_name" value='<?= $cat_name; ?>'>
                        <table id="document_table" style="display:none;" class="table-responsive">
                            <thead>
                                <tr>
                                    <th class="filterhead">ID, Owner, Title, Business unit, Project</th>
                                    <th class="filterhead">Category</th>
                                    <th class="filterhead">Expiry Date</th>
                                    <th class="filterhead">Department</th>
                                    <th class="filterhead">Folder</th>
                                    <th class="filterhead"> </th>
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $preDate = date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) );
                                ?>
                                @foreach($doc_listing as $document)
                                <?php 
                                    $expiry_date = '';
                                    if($document->expires_at != ''){
                                        $expiry_date =  date("m/d/Y", strtotime($document->expires_at));
                                    }
                                ?>
                                <tr>
                                    <td>D-00{{$document->id ?? ''}}</td>
                                    <td>{{($document->folder !="")?($document->folder->name !="")?$document->folder->name:"-":"-" }}</td>
                                    <td>{{$document->title ?? '-'}}</td>
                                    <td>{{($document->category !="")?($document->category->name !="")?$document->category->name:"-":"-" }}
                                    </td>
                                    <td>{{($document->owner !='')?$document->owner->vc_fname. ' ' .$document->owner->vc_mname. ' '.$document->owner->vc_lname : '-'}}
                                    </td>
                                    <td>{{($expiry_date != '') ?$expiry_date: '-'}}</td>
                                    <td>{{($document->business_unit !="")?($document->business_unit->vc_short_name !="")?$document->business_unit->vc_short_name:"-":"-" }}
                                    </td>
                                    <td>{{($document->department !="")?($document->department->vc_name !="")?$document->department->vc_name:"-":"-" }}
                                    </td>
                                    <td>{{($document->project !="")?($document->project->vc_name !="")?$document->project->vc_name:"-":"-" }}
                                    </td>
                                    <td>{{$document->description ?? '-'}}</td>
                                    <td>
                                        <a class="btn btn-success view-document" data-document='<?php echo json_encode(["file_name" => $document->file_name, "file_type" => $document->file_type, "description" => $document->description, "doc_link" => $document->doc_link]); ?>'>
                                            <i class="fa fa-eye"></i> {{ trans('label.view') }}
                                        </a>
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
<script src="{{asset('assets/js/supplier-document.js')}}"></script>
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script src="{{asset('assets/js/datatable_export/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/jszip.min.js')}}"></script>
<script src="{{asset('assets/js/datatable_export/buttons.html5.min.js')}}"></script>
@endsection
